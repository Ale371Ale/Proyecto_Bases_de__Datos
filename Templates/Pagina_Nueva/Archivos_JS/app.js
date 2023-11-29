var urlParams = new URLSearchParams(window.location.search);
var correo = urlParams.get('correo');

async function ObtenerIDCliente(){
    await fetch('Archivos_PHP/ObtenerIDCliente.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: JSON.stringify({ Correo: correo }),
    }).then(response => response.json()).then(data => {
        window.iDCLIENTE34 = data.idVendedor;
    }).catch(error => {
        console.error('Error:', error);
    });

}

// Agrega esto en tu archivo app.js
function toggleParteDerecha() {
    var parteDerecha = document.getElementById("parteDerecha");

    // Verifica si el elemento existe antes de continuar
    if (!parteDerecha) {
        console.error("Error: No se encontró la parte derecha.");
        return;
    }

    // Muestra u oculta la parte derecha alternando la posición
    parteDerecha.style.right = (parseInt(parteDerecha.style.right, 10) === 0) ? `-${parteDerecha.offsetWidth}px` : "0";
}
async function addToCart(id) {

    try {
        const response = await fetch('Archivos_PHP/ObtenerIDCliente.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: JSON.stringify({ Correo: correo }),
        });

        const data = await response.json();
        window.iDCLIENTE = data.idVendedor;
      

        // Verificar si el producto ya está en el carrito
        const existingProduct = await findProductInCart(id, window.iDCLIENTE);
 
        if (existingProduct !== null || existingProduct === "a") {
            changeQuantity(id,0,0,false);

        } else {
           
            // Realizar fetch para obtener la información actualizada del producto desde CarritoProducto.php
           await fetch('Archivos_PHP/CarritoProducto.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: JSON.stringify({ IDProducto: id, IDCliente: window.iDCLIENTE}),
            }).then(response => response.json()).then(data => {
            }).catch(error => {
                console.error('Error:', error);
            });

            // Realizar fetch para obtener la información actualizada del carrito desde ObtenerCarritoProductos.php
            const cartResponse = await fetch('Archivos_PHP/ObtenerCarritoProductos.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: JSON.stringify({ IDCliente: window.iDCLIENTE }),
            });

            const cartData = await cartResponse.json();

           // Verificar si hay productos en la respuesta
if (cartData.productos && cartData.productos.length > 0) {
    // Obtener el contenedor de productos del carrito
    var cartProductsContainer = document.getElementById('cartProducts');

    // Limpiar solo la sección de productos del carrito
    cartProductsContainer.innerHTML = "";

    // Iterar sobre cada producto en la respuesta
    cartData.productos.forEach(producto => {
        const imageBase64 = producto.Imagen;
        const productName = producto.Nombre;
        const productId = producto.idProducto;
        const productPrice = parseInt(producto.precio);
        var productQuantity = 1; 
        // Crear un nuevo elemento para el producto
        var productElement = document.createElement('div');
   // Crear un nuevo elemento para el producto
var productElement = document.createElement('div');
productElement.classList.add('cart-item');

// Asignar el valor de productId como ID al elemento div
productElement.id = `${productId}`;

productElement.innerHTML = `
    <figure>
        <img src="data:image/png;base64, ${imageBase64}" alt="${productName}" style="width: 70px; height: 70px; margin-top: 20%;">
    </figure>
    <div>
        <p>${productName}</p>
        <p><strong>$${productPrice.toFixed(2)}</strong></p>
    </div>
    <div class="quantitycontainer" id="${productId}">
        <img src="./icons/icon_close.png" alt="close" onclick="removeFromCart(${productId})" style="margin-right: 5%; margin-bottom: 24px; margin-left:10%; width: 20px; height: 20px;">
        <input type="number" id="Contadorcito" class="quantity" value="1" min="1" maxlength="2" onkeydown="return false;" oninput="changeQuantity(${productId},${productQuantity},this.value,true)">

    </div>
`;
        // Agregar el nuevo elemento al contenedor de productos del carrito
        cartProductsContainer.appendChild(productElement);
   

                });
                updateTotal();
                // Actualizar el total después de agregar los productos
               
            } else {
                console.error('La respuesta del servidor no contiene productos válidos.');
            }
        }
        
    } catch (error) {
        console.error('Error al agregar el producto al carrito:', error);
    }
}

async function CargarCarrito(){
    await ObtenerIDCliente();
    const cartResponse = await fetch('Archivos_PHP/ObtenerCarritoProductos.php', {
        method: 'POST', // Fix: 'method' instead of 'ethod'
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: JSON.stringify({ IDCliente: window.iDCLIENTE34 }),
    });

    const cartData = await cartResponse.json();
        
            // Verificar si hay productos en la respuesta
            if (cartData.productos && cartData.productos.length > 0) {
            // Obtener el contenedor de productos del carrito
            var cartProductsContainer = document.getElementById('cartProducts');

            // Limpiar solo la sección de productos del carrito
            cartProductsContainer.innerHTML = "";
              
            // Iterar sobre cada producto en la respuesta
            cartData.productos.forEach(producto => {
            const imageBase64 = producto.Imagen;
            const productName = producto.Nombre;
            const productId = producto.idProducto;
            const productPrice = parseInt(producto.precio);
            const productQuantity = parseInt(producto.Cantidad);
         
            // Crear un nuevo elemento para el producto
            var productElement = document.createElement('div');
            // Crear un nuevo elemento para el producto
            var productElement = document.createElement('div');
            productElement.classList.add('cart-item');

            // Asignar el valor de productId como ID al elemento div
            productElement.id = `${productId}`;

            productElement.innerHTML = `
            <figure>
            <img src="data:image/png;base64, ${imageBase64}" alt="${productName}" style="width: 70px; height: 70px; margin-top: 20%;">
            </figure>
            <div>
            <p>${productName}</p>
            <p><strong>$${productPrice.toFixed(2)}</strong></p>
            </div>
            <div class="quantitycontainer" id="${productId}">
            <img src="./icons/icon_close.png" alt="close" onclick="removeFromCart(${productId})" style="margin-right: 5%; margin-bottom: 24px; margin-left:10%; width: 20px; height: 20px;">
            <input type="number" id="Contadorcito" class="quantity" value="${productQuantity}" min="1" maxlength="2" onkeydown="return false;" oninput="changeQuantity(${productId},${productQuantity}, this.value,true )">


            </div>
            `;

            // Agregar el nuevo elemento al contenedor de productos del carrito
            cartProductsContainer.appendChild(productElement);
                // Actualizar el total después de agregar los productos
       
});
 // Actualizar el total después de agregar los productos

 await updateTotal();
}else{
    var cartProductsContainer = document.getElementById('cartProducts');
    cartProductsContainer.innerHTML = "";
    await updateTotal();

}

}

function obtenerDatosLista() {
    fetch('Archivos_PHP/ObtenerCategorias.php')
        .then(response => response.json())
        .then(data => {
            var dropdownMenu = document.querySelector('.dropdown-menu .col2');

            // Limpiar el contenido actual del menú desplegable
            dropdownMenu.innerHTML = '';

            // Agregar otras categorías al menú desplegable
            data.forEach(item => {
                var li = document.createElement('li');
                var a = document.createElement('a');
                a.href = '#'; // Puedes establecer el enlace adecuado aquí si es necesario
                a.textContent = item.Categoria; // Asegúrate de tener la propiedad correcta para el nombre de la categoría
                a.style.color = 'black'; // Establecer el color del texto

                // Estilos adicionales para mejorar el diseño
                a.style.textDecoration = 'none'; // Eliminar subrayado
                a.style.padding = '8px 16px'; // Añadir padding
                a.style.display = 'block'; // Hacer enlaces bloques para mejor presentación
                // Agregar evento de clic para cargar productos por categoría
                a.addEventListener('click', function() {
                    cargarProductosPorCategoria(item.Categoria);
                });
                li.appendChild(a);
                dropdownMenu.appendChild(li);
            });
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

async function removeFromCart(idProducto) {
    try {
        const idCliente = window.iDCLIENTE34;

        const response = await fetch('Archivos_PHP/BorrarProductodeCarrito.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: JSON.stringify({ IDCliente: idCliente, IDProducto: idProducto }),
        });

        const data = await response.json();

        CargarCarrito();
    } catch (error) {
        console.error('Error en la solicitud fetch:', error);
    }
    CargarCarrito();
}

function changeQuantity( id,ValorInicial,inputElement, origen) {

    // Obtener el elemento de cantidad
        if(origen === "true"){
             // Actualiza el atributo 'data-current-value' con el nuevo valor
    // Check if 'dataset' is defined before setting 'currentValue'
    if (inputElement.dataset) {
        inputElement.dataset.currentValue = newQuantity;
    }

    // Determina si el valor está aumentando o disminuyendo
    if (newQuantity > currentQuantity) {
        // Llama a la función cuando el valor aumenta
        increaseQuantityFunction(id);
    } else if (newQuantity < currentQuantity) {
        // Llama a la función cuando el valor disminuye
        decreaseQuantityFunction(id);
    } else if(newQuantity === currentQuantity) {
        decreaseQuantityFunction(id);
    }
        }
    var newQuantity = inputElement;
    // Check if 'dataset' is defined and if 'currentValue' exists
    var currentQuantity = ValorInicial;

    // If 'data-current-value' attribute is not set, set it to the initial value
    if (isNaN(currentQuantity)) {
        currentQuantity = 1;
        // Check if 'dataset' is defined before setting 'currentValue'
        if (inputElement.dataset) {
            inputElement.dataset.currentValue = currentQuantity;
        }
    }

    // Actualiza el atributo 'data-current-value' con el nuevo valor
    // Check if 'dataset' is defined before setting 'currentValue'
    if (inputElement.dataset) {
        inputElement.dataset.currentValue = newQuantity;
    }

    // Determina si el valor está aumentando o disminuyendo
    if (newQuantity > currentQuantity) {
        // Llama a la función cuando el valor aumenta
        increaseQuantityFunction(id);
    } else if (newQuantity < currentQuantity) {
        // Llama a la función cuando el valor disminuye
        decreaseQuantityFunction(id);
    } else {
        increaseQuantityFunction2(id);
    }
}

async function increaseQuantityFunction(productId) {
    await fetch('Archivos_PHP/ActualizarCantidadCarrito.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: JSON.stringify({ IDProducto: productId, IDCliente: window.iDCLIENTE34, }),
    }).then(response => response.json()).then(data => {

        window.cant = data.cantidad;


    }).catch(error => {console.log(error);});
    //obtener elemento del producto
    var productElement = document.getElementById(productId);
    // Obtener el elemento de cantidad
    var quantityInput = productElement.querySelector(".quantity");

    // Obtener la cantidad actual
    var currentQuantity = parseInt(quantityInput.value, 10);
    // Obtener la cantidad nueva
    var newQuantity = currentQuantity;
    // Actualizar la cantidad
    quantityInput.value = newQuantity;

    // Actualizar la cantidad y el total
    updateTotal();
}

async function increaseQuantityFunction2(productId) {
    await fetch('Archivos_PHP/ActualizarCantidadCarrito.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: JSON.stringify({ IDProducto: productId, IDCliente: window.iDCLIENTE34, }),
    }).then(response => response.json()).then(data => {

    }).catch(error => {console.log(error);});
    //obtener elemento del producto
    var productElement = document.getElementById(productId);
    // Obtener el elemento de cantidad
    var quantityInput = productElement.querySelector(".quantity");

    // Obtener la cantidad actual
    var currentQuantity = parseInt(quantityInput.value, 10);
    // Obtener la cantidad nueva
    var newQuantity = currentQuantity + 1;
    // Actualizar la cantidad
    quantityInput.value = newQuantity;

    // Actualizar la cantidad y el total
    updateTotal();
}

async function decreaseQuantityFunction(productId) {
    await fetch('Archivos_PHP/BajarCarritoMenos.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: JSON.stringify({ IDProducto: productId, IDCliente: window.iDCLIENTE34, }),
    }).then(response => response.json()).then(data => {

    }).catch(error => {console.log(error);});
    //obtener elemento del producto
    var productElement = document.getElementById(productId);
    // Obtener el elemento de cantidad
    var quantityInput = productElement.querySelector(".quantity");
    // Obtener la cantidad actual
    var currentQuantity = parseInt(quantityInput.value, 10);
    // Obtener la cantidad nueva
    var newQuantity = currentQuantity ;
    // Actualizar la cantidad
    quantityInput.value = newQuantity;

    // Actualizar la cantidad y el total
    updateTotal();
}

async function findProductInCart(productId,idCliente) {
 
    // Realizar fetch para verificar si el producto ya está en el carrito en el servidor
    return fetch('Archivos_PHP/leerCarritoProductos.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: JSON.stringify({ IDProducto: productId, IDCliente: idCliente})
    })
    .then(response => response.json())
    .then(data => {
        // Si el producto está en el carrito, devolver el elemento correspondiente
        if (data['mensaje'] ==="Producto ya existe") {
            return "a";
        }else if(data['mensaje'] === "Producto no existe"){
        // Si el producto no está en el carrito, devolver null
        return null;
        }

      
    })
    .catch(error => {
        console.error('Error al verificar el producto en el carrito:', error);
        return null;
    });
}


function updateTotal() {
    var cartProducts = document.querySelectorAll(".cart-item");
    var total = 0;

    cartProducts.forEach(function (product) {
        var priceElement = product.querySelector("div > p > strong");
        var quantityInput = product.querySelector("#Contadorcito")
        if (priceElement && quantityInput) {
            var price = parseFloat(priceElement.textContent.replace("$", ""));
            var quantity = parseInt(quantityInput.value, 10);
            total += price * quantity;
        }
    });


    var cartTotalElement = document.getElementById("cartTotal");

    if (cartTotalElement) {
        cartTotalElement.textContent = "$" + total.toFixed(2);
    }
}

var productosVendedor = [];


async function LeerProductos() {
    try {
        const response = await fetch('Archivos_PHP/LeerProductosGeneral.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
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
        console.error('Error:', error);
        productosVendedor = [];
    }
}

async function mostrarProductosEnTarjetas() {
await LeerProductos();
    // Obtén el contenedor donde se mostrarán los productos
    var listaProductosContainer = document.getElementById("listaProductos");

    // Verifica si el contenedor existe antes de continuar
    if (!listaProductosContainer) {
        console.error("Error: No se encontró el contenedor de productos.");
        return;
    }

    // Limpia el contenido actual del contenedor
    listaProductosContainer.innerHTML = "";

    // Verifica si hay productos para mostrar
    if (productosVendedor.length === 0) {
        // Muestra un mensaje indicando que no hay productos
        listaProductosContainer.innerHTML = "<p>No hay productos disponibles.</p>";
        return;
    }
 
    var i=0;
    // Itera sobre la lista de productos y crea una tarjeta para cada uno
    productosVendedor.forEach(function (producto) {

        var cardElement = document.createElement("div");
        cardElement.className = "col-md-4";
        
        cardElement.innerHTML = `
            <div class="card" id="${producto.idProducto}">
                <img src="${producto.imagen}" class="card-img-top"  alt="${producto.nombre}">
                <div class="card-body">
                    <h5 class="card-title">${producto.nombre}</h5>
                    <p class="card-text">Descripción: ${producto.descripcion}</p>
                    <p class="card-text">Precio: $${producto.precio}</p>
                    <a href="${producto.enlace}" class="btn btn-primary" style="margin-right:50px;">Ver Artículo</a>
                    <a onclick="addToCart(${producto.id})" class="btn btn-primary">Agregar a Carrito</a>
                </div>
            </div>
        `;
        i++;
        // Agrega la tarjeta al contenedor de productos
        listaProductosContainer.appendChild(cardElement);
    });
}
document.addEventListener('DOMContentLoaded', async function() {
 try {
        await CargarProductosRecientes();
        await mostrarProductosEnTarjetas();
        await obtenerDatosLista();
        await CargarCarrito();
    } catch (error) {
        console.error('Error:', error);
    }
});

var productosVendedo3 = [];
async function CargarProductosRecientes() {
    try {
        // Obtener los productos generales
        const response = await fetch('Archivos_PHP/LeerProductosGeneral.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
        });

        if (!response.ok) {
            throw new Error('Respuesta no exitosa: ' + response.statusText);
        }

        const productos = await response.json();

        // Mapear los productos generales
        productosVendedor3 = productos.map(producto => ({
            id: producto.idProducto,
            nombre: producto.Nombre,
            descripcion: producto.Descripcion,
            precio: producto.precio,
            vistas: producto.Vistas,
            enlace: producto.DireccionWeb,
            imagen: producto.Imagen ? 'data:image/png;base64,' + producto.Imagen : null,
        }));

        // Tomar los primeros 3 productos
        const primerosTresProductos = productosVendedor3.slice(0, 3);

        // Crear un nuevo carrusel
        const nuevoCarrusel = document.createElement('div');
        nuevoCarrusel.id = 'carouselExampleControls';
        nuevoCarrusel.classList.add('carousel', 'slide');
        nuevoCarrusel.classList.add('active');
        nuevoCarrusel.setAttribute('data-bs-ride', 'carousel');

        // Agregar dinámicamente nuevos elementos al carrusel
        primerosTresProductos.forEach((producto, index) => {
            const carouselItem = document.createElement('div');
            carouselItem.classList.add('carousel-item');

            if (index === 0) {
                carouselItem.classList.add('active');
            }

            const productoContainer = document.createElement('div');
            productoContainer.classList.add('producto-container');

            const productoInfo = document.createElement('div');
            productoInfo.classList.add('producto-info');

            const imageContainer = document.createElement('div');
            imageContainer.classList.add('Imagecontainer');

            const imagen = document.createElement('img');
            imagen.src = producto.imagen;
            imagen.alt = `Producto ${producto.id}`;
            imagen.classList.add('mx-auto', 'd-block');
            imagen.style.width = '200px';
            imagen.style.height = '200px';

            const textCarouselProducto = document.createElement('div');
            textCarouselProducto.classList.add('Text_Carousel_Producto');

            const titulo = document.createElement('h5');
            titulo.textContent = producto.nombre;

            const precio = document.createElement('p');
            precio.textContent = `Precio: $${producto.precio}`;

            const verMasButton = document.createElement('button');
            verMasButton.textContent = 'Ver Articulo';
            verMasButton.classList.add('btn', 'btn-info');
            verMasButton.onclick = () => window.open(producto.enlace, '_blank');
            verMasButton.style.marginRight = '50px';
            const agregarCarritoButton = document.createElement('button');
            agregarCarritoButton.textContent = 'Agregar a Carrito';
            agregarCarritoButton.classList.add('btn', 'btn-primary');
            agregarCarritoButton.style.marginLeft = '50px';
            agregarCarritoButton.style.marginRight = '0px';
            agregarCarritoButton.onclick = () => addToCart(producto.id);

            textCarouselProducto.appendChild(titulo);
            textCarouselProducto.appendChild(precio);
            textCarouselProducto.appendChild(verMasButton);
            textCarouselProducto.appendChild(agregarCarritoButton);

            imageContainer.appendChild(imagen);

            productoInfo.appendChild(imageContainer);
            productoInfo.appendChild(textCarouselProducto);

            productoContainer.appendChild(productoInfo);

            carouselItem.appendChild(productoContainer);

            nuevoCarrusel.appendChild(carouselItem);
        });

        // Flecha izquierda
        const flechaIzquierda = document.createElement('button');
        flechaIzquierda.classList.add('carousel-control-prev');
        flechaIzquierda.setAttribute('type', 'button');
        flechaIzquierda.setAttribute('data-bs-target', '#carouselExampleControls');
        flechaIzquierda.setAttribute('data-bs-slide', 'prev');
        flechaIzquierda.innerHTML = '<span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="visually-hidden">Previous</span>';
        flechaIzquierda.style.marginTop= '200px';

        // Flecha derecha
        const flechaDerecha = document.createElement('button');
        flechaDerecha.classList.add('carousel-control-next');
        flechaDerecha.setAttribute('type', 'button');
        flechaDerecha.setAttribute('data-bs-target', '#carouselExampleControls');
        flechaDerecha.setAttribute('data-bs-slide', 'next');
        flechaDerecha.innerHTML = '<span class="carousel-control-next-icon" aria-hidden="true"></span><span class="visually-hidden">Next</span>';
        flechaDerecha.style.marginTop= '200px';
        nuevoCarrusel.appendChild(flechaIzquierda);
        nuevoCarrusel.appendChild(flechaDerecha);

        // Reemplazar el carrusel existente con el nuevo carrusel
        const contenedorCarrusel = document.querySelector('.carousel-container');
        const carruselExistente = document.getElementById('carouselExampleControls');
        contenedorCarrusel.replaceChild(nuevoCarrusel, carruselExistente);

        // Reinicializar el carrusel usando Bootstrap
        new bootstrap.Carousel(document.getElementById('carouselExampleControls'), {
            interval: 2000, // Puedes ajustar el intervalo según tus necesidades
            // Otros parámetros de inicialización si es necesario
        });

    } catch (error) {
        console.error('Error:', error);
        productosVendedor = [];
    }
}


async function cargarProductosPorCategoria(categoria) {
    await MostrarProductosIndexados(categoria);
    // Ocultar el carrusel
    document.querySelector('.carousel-container').style.display = 'none';
    
    // Mostrar solo la fila de productos
    document.querySelector('#listaProductos').style.display = 'flex';


    
}
async function MostrarProductosIndexados(categoria) {
    try {
        console.log(categoria);
        const response = await fetch('Archivos_PHP/Obtener_Productos_Indexados.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ Categoria: categoria }),
        });
     
        if (!response.ok) {
            throw new Error('Respuesta no exitosa: ' + response.statusText);
        }
        const productos = await response.json();
                // Limpiar la sección de productos
        const listaProductos = document.getElementById('listaProductos');
        listaProductos.innerHTML = '';
        // Mostrar los productos obtenidos
        productos.forEach(producto => {
            const productoCard = document.createElement('div');
            productoCard.classList.add('col-md-4', 'mb-4');

            // Crear el contenido de la tarjeta del producto (puedes personalizar esto según tu diseño)
            productoCard.innerHTML = `
                <div class="card">
                    <img src="${producto.Imagen}" class="card-img-top" alt="${producto.Nombre}">
                    <div class="card-body">
                        <h5 class="card-title">${producto.Nombre}</h5>
                        <p class="card-text">${producto.Descripcion}</p>
                        <p class="card-text">Precio: $${producto.precio}</p>
                        <button class="btn btn-primary" onclick="addToCart(${producto.idProducto})">Agregar al Carrito</button>
                    </div>
                </div>
            `;

            listaProductos.appendChild(productoCard);
        });
        const tituloProductos = document.querySelector('.container-mt-5 h2');
        tituloProductos.textContent = categoria;
        tituloProductos.style.marginTop = '50px';
    } catch (error) {
        console.error('Error:', error);
    }
}

async function mostrarResultadosBusqueda( terminoBusqueda) {
    try {
        const response = await fetch('Archivos_PHP/Obtener_Productos_Indexando.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({  TerminoBusqueda: terminoBusqueda }),
        });

        if (!response.ok) {
            throw new Error('Respuesta no exitosa: ' + response.statusText);
        }

        const productos = await response.json();
        


        
        try{
            // Limpiar la sección de productos
            const listaProductos = document.getElementById('listaProductos');
            listaProductos.innerHTML = '';
             // Mostrar los productos obtenidos
        productos.forEach(producto => {
            const productoCard = document.createElement('div');
            productoCard.classList.add('col-md-4', 'mb-4');

              // Crear el contenido de la tarjeta del producto (puedes personalizar esto según tu diseño)
              productoCard.innerHTML = `
              <div class="card">
                  <img src="${producto.Imagen}" class="card-img-top" alt="${producto.Nombre}">
                  <div class="card-body">
                      <h5 class="card-title">${producto.Nombre}</h5>
                      <p class="card-text">${producto.Descripcion}</p>
                      <p class="card-text">Precio: $${producto.precio}</p>
                      <button class="btn btn-primary" onclick="addToCart(${producto.idProducto})">Agregar al Carrito</button>
                  </div>
              </div>
          `;

            listaProductos.appendChild(productoCard);
            document.getElementById('mensajeSinCoincidencias').style.display = 'none';
        });
        }catch(error){
             // Mostrar el mensaje si no hay coincidencias
             document.getElementById('mensajeSinCoincidencias').style.display = 'block';
        }
       
    } catch (error) {
        console.error('Error:', error);
    }
}

// Capturar el evento de envío del formulario
document.getElementById('formBusqueda').addEventListener('submit', async function (event) {
    event.preventDefault(); // Evitar el comportamiento predeterminado de recarga de la página

    // Obtener el valor del término de búsqueda
    const terminoBusqueda = document.getElementById('inputBusqueda').value;
  
    // Llamar a la función para mostrar los resultados de la búsqueda
    await mostrarResultadosBusqueda(terminoBusqueda);
    // Ocultar el carrusel
    document.querySelector('.carousel-container').style.display = 'none';
    
    // Mostrar solo la fila de productos
    document.querySelector('#listaProductos').style.display = 'flex';
});

document.getElementById('enlaceLogo').addEventListener('click', function (event) {


    // Agrega el correo como parámetro al enlace
    this.href = "Interfaz_Central.html?correo=" + encodeURIComponent(correo);
});