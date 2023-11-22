


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




async function LeerProductos() {
try {
await obtenerId(); // Esperar a que obtenerId se complete antes de continuar
// Realizar la solicitud fetch para obtener productos
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
// Limpiar el contenedor donde se mostrarán los productos
$('#listaProductos').empty();

// Iterar sobre los productos y mostrarlos
for (var i = 0; i < productos.length; i++) {
    var producto = productos[i];

    // Crear un elemento para mostrar el producto
    var elementoProducto = $('<div></div>').html(
        '<strong>' + producto.Nombre + '</strong> - ' +
        'Descripción: ' + producto.Descripcion + ', ' +
        'Precio: ' + producto.precio + ', ' +
        'Vistas: ' + producto.Vistas +
        ' Enlace: <a href="' + producto.DireccionWeb + '" target="_blank">Ver producto</a>'
        // Puedes agregar más campos según tus necesidades
    );

    // Agregar la imagen si está presente
    if (producto.Imagen) {
        var imagenElemento = $('<img>').attr('src', 'data:image/png;base64,' + producto.Imagen);
        elementoProducto.append(imagenElemento);
    }

    // Agregar el elemento del producto al contenedor
    $('#listaProductos').append(elementoProducto);


}
} catch (error) {
console.error('Error al obtener productos:', error);
}
}



var productosVendedor = [];
function mostrarProductos() {
    LeerProductos();
var listaProductos = document.getElementById('listaProductos');
listaProductos.innerHTML = '';

// Crear un contenedor principal para las columnas
var contenedorColumnas = document.createElement('div');
contenedorColumnas.className = 'row';

productosVendedor.forEach(function (producto) {
// Crear un elemento para cada tarjeta de producto
var elementoProducto = document.createElement('div');
elementoProducto.className = 'col-md-4';
elementoProducto.innerHTML = `
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">${producto.nombre}</h5>
            <p class="card-text" style="max-lines: 3;">Precio: $${producto.precio}</p>
            <!-- Agrega más información según sea necesario -->
            <a href="${producto.enlace}" class="btn btn-primary">Ver Artículo</a>
            <a onclick="addToCart('${producto.imagen}', '${producto.nombre}', ${producto.precio})" class="btn btn-primary">Agregar a Carrito</a>
        </div>
        <img src="${producto.imagen}" class="card-img-top" style="height: 50px; width: 50px; cursor: pointer;" alt="${producto.nombre}" onclick="window.location.href='${producto.enlace}'">
    </div>
`;

// Agregar el elemento de la tarjeta al contenedor de columnas
contenedorColumnas.appendChild(elementoProducto);
});

// Agregar el contenedor de columnas al contenedor principal
listaProductos.appendChild(contenedorColumnas);
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
