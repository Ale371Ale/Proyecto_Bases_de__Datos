


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
            nombre: producto.Nombre,
            descripcion: producto.Descripcion,
            precio: producto.precio,
            vistas: producto.Vistas,
            enlace: producto.DireccionWeb,
            imagen: producto.Imagen ? 'data:image/png;base64,' + producto.Imagen : null,
        }));

    } catch (error) {
        console.error('Error al obtener productos:', error);
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

            productosVendedor.forEach(producto => {
                // Crear un elemento para cada tarjeta de producto
                var elementoProducto = document.createElement('div');
                elementoProducto.className = 'col-md-4 mb-4'; // Añadir clase mb-4 para agregar espacio entre las tarjetas

                var card = document.createElement('div');
                card.className = 'card h-100';

                var img = document.createElement('img');
                img.src = producto.imagen;
                img.className = 'card-img-top';
                img.style = 'height: 200px; object-fit: contain; cursor: pointer;';
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

                var addButton = document.createElement('a');
                addButton.onclick = function () {
                    addToCart(producto.imagen, producto.nombre, producto.precio);
                };
                addButton.className = 'btn btn-primary btn-sm';
                addButton.textContent = 'Agregar a Carrito';

                cardBody.appendChild(title);
                cardBody.appendChild(price);
                cardBody.appendChild(viewButton);
                cardBody.appendChild(addButton);

                card.appendChild(img);
                card.appendChild(cardBody);

                elementoProducto.appendChild(card);

                // Agregar el elemento de la tarjeta al contenedor de columnas
                contenedorColumnas.appendChild(elementoProducto);
            });

            listaProductos.appendChild(contenedorColumnas);
        });
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

    mostrarProductos();

    document.addEventListener('DOMContentLoaded', function () {
        var nuevosProductosLink = document.getElementById('nuevosProductosLink');
        nuevosProductosLink.addEventListener('click', function () {
            var addProductModal = new bootstrap.Modal(document.getElementById('addProductModal'));
            addProductModal.show();
        });
    });
        // Llamar a la función al cargar la página (puedes ajustar esto según tus necesidades)
