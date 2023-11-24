


var urlParams = new URLSearchParams(window.location.search);
var correo = urlParams.get('correo');

async function GuardarProductos() {
try {
// Obtener los valores del formulario
var nombreProducto = $('#productName').val();
var descripcionProducto = $('#DescriptionProuct').val();
var enlaceProducto = $('#LinkProduct').val();
var precioProducto = $('#productPrice').val();

// Leer la imagen y convertirla a base64
var inputImage = document.getElementById('ImageArchive');
var file = inputImage.files[0];

if (!file) {
    prompt('Por favor selecciona una imagen');
    return;
}

var imagenBase64 = await readImageAsBase64(file);

// Obtener el ID del vendedor
await obtenerId();
var datosProducto = {
idVendedor: window.idVendedor,
nombre: nombreProducto,
descripcion: descripcionProducto,
enlace: enlaceProducto,
precio: precioProducto,
imagen: imagenBase64
};

const response = await fetch('SubirProductos.php', {
method: 'POST',
headers: {
'Content-Type': 'application/json',
},
body: JSON.stringify(datosProducto),
});

if (!response.ok) {
    throw new Error('Respuesta no exitosa: ' + response.statusText);
}

const responseData = await response.json();

// Manejar la respuesta del servidor (puede ser un mensaje de éxito o error)
console.log(responseData);
if (responseData['mensaje'] === "Producto agregado con éxito")  {
    $('#productName').val('');
    $('#DescriptionProuct').val('');
    $('#LinkProduct').val('');
    $('#productPrice').val('');
    $('#ImageArchive').val('');
    $('#addProductModal').modal('hide');
    mostrarProductos();
}else{
    alert(responseData['mensaje']);
}
} catch (error) {
console.error('Error en la función GuardarProductos:', error);
}
}

function readImageAsBase64(file) {
    console.log('Tipo de archivo:', file.type);

    return new Promise((resolve, reject) => {
        var reader = new FileReader();
        reader.onloadend = function () {
            resolve(reader.result);
        };
        reader.onerror = function (error) {
            reject(error);
        };
        reader.readAsDataURL(file);
    });
}


// Asigna la función GuardarProductos al evento click del botón
$(document).ready(function () {
$('#btnAgregarProducto').on('click', GuardarProductos);
});

async function obtenerId() {
try {
const response = await fetch('obtenerID.php', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: 'Correo=' + correo,
});

const data = await response.json();

if (data.idVendedor) {
    window.idVendedor = data.idVendedor;
    idVendedor = window.idVendedor;


} else {
    console.log("Usuario no encontrado en Vendedor");
}
} catch (error) {
console.error('Error al obtener el ID:', error);
}
}



var productosVendedor = [];


async function LeerProductos() {
    try {
        await obtenerId();
        const response = await fetch('LeerProductos.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ idVendedor: window.idVendedor }),
        });

        if (!response.ok) {
            throw new Error('Respuesta no exitosa: ' + response.statusText);
        }

        const productos = await response.json();

        productosVendedor = productos.map(producto => ({
            id: producto.idProducto,
            nombre: producto.Nombre,
            descripcion: producto.Descripcion,
            precio: producto.precio,
            vistas: producto.Vistas,
            enlace: producto.DireccionWeb,
            imagen: producto.Imagen ? 'data:image/png;base64,' + producto.Imagen : null,
        }));

    } catch (error) {
            productosVendedor = [];
       
    }
}

function mostrarProductos() {
    try {
        LeerProductos().then(() => {
            var listaProductos = document.getElementById('listaProductos');
            listaProductos.innerHTML = '';

            // Crear un contenedor principal para las columnas
            var contenedorColumnas = document.createElement('div');
            contenedorColumnas.className = 'row';
if(productosVendedor.length === 0){
//imprimir un mensaje de que no hay productos   
      // Crear un elemento para la tarjeta de mensaje
    var elementoMensaje = document.createElement('div');
    elementoMensaje.className = 'col-md-4 mb-4'; // Añadir clase mb-4 para agregar espacio entre las tarjetas

    var card = document.createElement('div');
    card.className = 'card h-100';
    var cardBody = document.createElement('div');
    cardBody.className = 'card-body';

    var message = document.createElement('h5');
    message.className = 'card-title';
    message.textContent = 'Sin productos';

    cardBody.appendChild(message);
    card.appendChild(cardBody);
    elementoMensaje.appendChild(card);

    // Agregar el elemento de la tarjeta al contenedor de columnas
    contenedorColumnas.appendChild(elementoMensaje);
    listaProductos.appendChild(contenedorColumnas);       
}else{

            productosVendedor.forEach(producto => {
                // Crear un elemento para cada tarjeta de producto
                var elementoProducto = document.createElement('div');
                elementoProducto.className = 'col-md-4 mb-4'; // Añadir clase mb-4 para agregar espacio entre las tarjetas

                var card = document.createElement('div');
                card.className = 'card h-100';

                var img = document.createElement('img');
                img.src = producto.imagen;
                img.className = 'card-img-top';
                img.style = 'height: 200px; object-fit: contain; cursor: pointer; margin-top: 5%;';
                img.alt = producto.nombre;
                img.onclick = function () {
                    window.location.href = producto.enlace;
                };

                var cardBody = document.createElement('div');
                cardBody.className = 'card-body';

                var title = document.createElement('h5');
                title.className = 'card-title';
                title.textContent = producto.nombre;

                var price = document.createElement('p');
                price.className = 'card-text';
                price.textContent = 'Precio: $' + producto.precio;

                var viewButton = document.createElement('a');
                viewButton.href = producto.enlace;
                viewButton.className = 'btn btn-primary btn-sm';
                viewButton.textContent = 'Ver Artículo';

                var modifyButton = document.createElement('a');
                modifyButton.onclick = function () {
                    showModifyProductForm(producto.id);
                };
                modifyButton.className = 'btn btn-warning btn-sm';
                modifyButton.textContent = 'Modificar';
                modifyButton.style = 'margin-left: 10px;';
                cardBody.appendChild(title);
                cardBody.appendChild(price);
                cardBody.appendChild(viewButton);
                cardBody.appendChild(modifyButton);

                card.appendChild(img);
                card.appendChild(cardBody);

                elementoProducto.appendChild(card);

                // Agregar el elemento de la tarjeta al contenedor de columnas
                contenedorColumnas.appendChild(elementoProducto);
            });

            listaProductos.appendChild(contenedorColumnas);
    }});
    
    } catch (error) {
        console.error('Error al mostrar productos:', error);
    }

}


    function filtrarProductos(event) {
        var categoriaSeleccionada = event.target.getAttribute('data-categoria');

        var productosFiltrados = [];

        if (categoriaSeleccionada === 'todos') {
            productosFiltrados = productosVendedor;
        } else {
            productosFiltrados = productosVendedor.filter(function (producto) {
                return producto.categoria === categoriaSeleccionada;
            });
        }

        mostrarProductos(productosFiltrados);
    }
    function showModifyProductForm(productoId) {
        // Buscar el producto en la variable productosVendedor por su ID
        var producto = productosVendedor.find(p => p.id === productoId);
    
        if (!producto) {
            console.error('Producto no encontrado');
            return;
        }
    
        // Obtener los elementos del formulario de modificación, incluyendo el nuevo campo para la imagen y la URL
        var nuevoNombreInput = document.getElementById('nuevoNombre');
        var nuevaDescripcionInput = document.getElementById('nuevaDescripcion');
        var nuevoPrecioInput = document.getElementById('nuevoPrecio');
        var nuevaImagenInput = document.getElementById('nuevaImagen'); // Nuevo campo para la imagen
        var nuevaURLInput = document.getElementById('nuevaURL'); // Nuevo campo para la URL
    
        
        // Cargar los datos del producto en el formulario
        nuevoNombreInput.value = producto.nombre;
        nuevaDescripcionInput.value = producto.descripcion;
        nuevoPrecioInput.value = producto.precio;
        nuevaURLInput.value = producto.enlace;
    
        // Lógica para abrir el formulario de modificación
        var modifyProductModal = new bootstrap.Modal(document.getElementById('modifyProductModal'));
        modifyProductModal.show();
    
        // Ejemplo de cómo puedes enviar los datos modificados al servidor al hacer clic en "Modificar"
        var modifyButton = document.getElementById('modifyButton');
        modifyButton.addEventListener('click', function () {
            // Obtener los nuevos datos del formulario de modificación
            var nuevoNombre = nuevoNombreInput.value;
            var nuevaDescripcion = nuevaDescripcionInput.value;
            var nuevoPrecio = nuevoPrecioInput.value;
            var nuevaImagen = nuevaImagenInput.files[0]; // Nueva imagen seleccionada
            var nuevaURL = nuevaURLInput.value; // Nueva URL ingresada
           
            if (nuevaImagen) {
                // Si hay una nueva imagen seleccionada, codifícala a base64
                const reader = new FileReader();
                reader.onloadend = function () {
                    nuevaImagen = reader.result;
                   
                };
                reader.readAsDataURL(nuevaImagen);
            } else {
                nuevaImagen = producto.imagen; // Utilizar la imagen existente
            }
    
            // Construir el objeto con los nuevos datos y el ID del producto
            var datosModificados = {
                id: productoId,
                nuevoNombre: nuevoNombre,
                nuevaDescripcion: nuevaDescripcion,
                nuevoPrecio: nuevoPrecio,
                nuevaImagen: nuevaImagen, // Agregar la nueva imagen al objeto
                nuevaURL: nuevaURL // Agregar la nueva URL al objeto
            };
           
            // Llamar a la función que envía la solicitud fetch al PHP para modificar el producto
            modificarProducto(datosModificados);
    
            // Cerrar el modal después de modificar el producto
            modifyProductModal.hide();
        });





        var modifyButton = document.getElementById('deleteButton');
        modifyButton.addEventListener('click', function () {
            eliminarProducto(productoId);
            modifyProductModal.hide();
        });
    }

   async function modificarProducto(datosModificados) {
        console.log('Imagen recibida en PHP:', datosModificados.nuevaImagen);
        var imagenBase64 = await readImageAsBase64(datosModificados.nuevaImagen);
        fetch('EditarProductos.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                id: datosModificados.id,
                nuevoNombre: datosModificados.nuevoNombre,
                nuevaDescripcion: datosModificados.nuevaDescripcion,
                nuevoPrecio: datosModificados.nuevoPrecio,
                nuevaImagen: imagenBase64,
                nuevaURL: datosModificados.nuevaURL,
            }),
        })
        .then(response => response.json())
        .then(data => {
            console.log(data);
            mostrarProductos();
        })
        .catch(error => console.error('Error al modificar el producto:', error));
    }
    
    function eliminarProducto(productoId) {
       
        fetch('EliminarProducto.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: productoId }),
        })
        .then(response => response.json())
        .then(data => {
            // Lógica para manejar la respuesta del servidor después de eliminar el producto
            console.log(data);
            // Recargar o actualizar la lista de productos después de la eliminación
            mostrarProductos();
        })
        .catch(error => console.error('Error al eliminar el producto:', error));
    }
    
    
    mostrarProductos();

    document.addEventListener('DOMContentLoaded', function () {
        var nuevosProductosLink = document.getElementById('nuevosProductosLink');
        nuevosProductosLink.addEventListener('click', function () {
            var addProductModal = new bootstrap.Modal(document.getElementById('addProductModal'));
            addProductModal.show();
        });
    });
        // Llamar a la función al cargar la página (puedes ajustar esto según tus necesidades)
