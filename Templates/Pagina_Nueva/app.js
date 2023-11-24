
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
function addToCart(productId, productName, productPrice) {
    var cartContainer = document.getElementById("shoppingCartContainer");

    if (!cartContainer) {
        console.error("Error: No se encontró el contenedor del carrito.");
        return;
    }

    // Verificar si el producto ya está en el carrito
    var existingProduct = findProductInCart(productId);

    if (existingProduct) {
        // Incrementar la cantidad si ya existe en el carrito
        var quantityInput = existingProduct.querySelector(".quantity");
        var currentQuantity = parseInt(quantityInput.value, 10);
        quantityInput.value = currentQuantity + 1;
    } else {
        // Crear un nuevo elemento si no existe en el carrito
        var productElement = document.createElement("div");
        productElement.className = "shopping-cart";

        var imageUrl = productId;

        productElement.innerHTML = `
            <div class="cart-item">
                <figure>
                    <img src="${imageUrl}" alt="${productName}" style="width: 70px; height: 70px; margin-top: 20%;">
                </figure>
                <div>
                    <p>${productName}</p>
                    <p><strong>$${productPrice.toFixed(2)}</strong></p>
                </div>
                <div class="quantitycontainer">
                    <img src="./icons/icon_close.png" alt="close" onclick="removeFromCart(this)" style="margin-right: 5%; margin-bottom: 24px; margin-left:10%; width: 20px;
                    height: 20px;">
                    <input type="number" class="quantity" value="1" min="1" maxlength="2" onkeydown="return false;" onchange="changeQuantity(this, '${productId}', '${productPrice.toFixed(2)}')">
                </div>
            </div>
        `;

        // Agregar el nuevo elemento antes de la sección de total y el botón
        var orderContent = document.querySelector(".my-order-content");
        orderContent.insertBefore(productElement, orderContent.firstChild);
    }

    updateTotal();
}

function removeFromCart(closeButton) {
    var cartContainer = document.getElementById("shoppingCartContainer");
    var productElement = closeButton.closest(".shopping-cart");

    productElement.parentNode.removeChild(productElement);
    updateTotal();

    if (cartContainer.querySelectorAll(".shopping-cart").length === 0) {
        cartContainer.classList.add("inactive");
    }
}


function changeQuantity(input, productId, productPrice) {
    var newQuantity = parseInt(input.value, 10);

    // Verificar que la cantidad sea un número válido
    if (isNaN(newQuantity) || newQuantity <= 0) {
        alert("Por favor, ingrese una cantidad válida mayor a 0.");
        input.value = 1;
        return;
    }

    // Actualizar la cantidad y el total
    updateTotal();
}

function findProductInCart(productId) {
    // Buscar el producto por ID en el carrito
    var cartProducts = document.querySelectorAll(".shopping-cart");

    for (var i = 0; i < cartProducts.length; i++) {
        var product = cartProducts[i];
        var productIdAttribute = product.querySelector("img").getAttribute("src");

        if (productIdAttribute === productId) {
            return product;
        }
    }

    return null;
}
function updateTotal() {
    var cartProducts = document.querySelectorAll(".shopping-cart");
    var total = 0;

    cartProducts.forEach(function (product) {
        var priceElement = product.querySelector("div p:nth-child(2) strong"); // Ruta ajustada para obtener el precio
        var quantityInput = product.querySelector(".quantity");

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
        const response = await fetch('LeerProductosGeneral.php', {
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

    // Itera sobre la lista de productos y crea una tarjeta para cada uno
    productosVendedor.forEach(function (producto) {
        var cardElement = document.createElement("div");
        cardElement.className = "col-md-4";
        
        cardElement.innerHTML = `
            <div class="card">
                <img src="${producto.imagen}" class="card-img-top" style="height: 300px;" alt="${producto.nombre}">
                <div class="card-body">
                    <h5 class="card-title">${producto.nombre}</h5>
                    <p class="card-text">Descripción: ${producto.descripcion}</p>
                    <p class="card-text">Precio: $${producto.precio}</p>
                    <a href="${producto.enlace}" class="btn btn-primary">Ver Artículo</a>
                    <a onclick="addToCart('${producto.imagen}', '${producto.nombre}', ${producto.precio})" class="btn btn-primary">Agregar a Carrito</a>
                </div>
            </div>
        `;

        // Agrega la tarjeta al contenedor de productos
        listaProductosContainer.appendChild(cardElement);
    });
}

// Llama a la función para mostrar los productos en tarjetas
mostrarProductosEnTarjetas();
