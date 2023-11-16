
function habilitarBotones() {
    var comboBox = document.getElementById('rol');
    var btnCrearCuenta = document.getElementById("btnCrearCuenta");
    var btnCrearCuentaGoogle = document.getElementById("btnCrearCuentaGoogle");

    // Verifica si el combo box tiene una opción seleccionada
    if (comboBox.value !== "") {
        // Habilita los botones
        btnCrearCuenta.disabled = false;
        btnCrearCuentaGoogle.disabled = false;
    } else {
        // Deshabilita los botones si no hay una opción seleccionada
        btnCrearCuenta.disabled = true;
        btnCrearCuentaGoogle.disabled = true;
    }
}
function alternarBoton() {
    var btnIniciarSesion = document.getElementById("btnIniciarSesion");
    var btnCrearCuenta = document.getElementById("btnCrearCuenta");
    var comboBox = document.getElementById("rol");

    btnIniciarSesion.classList.toggle("d-none");
    btnCrearCuenta.classList.toggle("d-none");
    comboBox.classList.toggle("d-none");

    var tituloVentana = document.getElementById("tituloVentana").innerText;

    document.getElementById("tituloVentana").innerText = (btnIniciarSesion.classList.contains("d-none")) ? "Crear Cuenta" : "Iniciar Sesión";

    document.getElementById("mensajeCuenta").innerHTML = (btnIniciarSesion.classList.contains("d-none")) ? "¿Tienes una cuenta? <a href='#' onclick='alternarBoton()'>Iniciar Sesión</a>" : "¿No tienes cuenta? <a href='#' onclick='alternarBoton()'>Crear cuenta</a>";
    habilitarBotones();
    alternarBotonGoogle();
}

function alternarBotonGoogle(accion) {
    // Obtiene el botón de "Iniciar Sesión con Google"
    var btnIniciarGoogle = document.getElementById("btnIniciarGoogle");

    // Obtiene el botón de "Crear cuenta con Google"
    var btnCrearCuentaGoogle = document.getElementById("btnCrearCuentaGoogle");

    // Obtiene el botón de "Iniciar Sesión"
    var btnIniciarSesion = document.getElementById("btnIniciarSesion");

    // Obtiene el botón de "Crear Cuenta"
    var btnCrearCuenta = document.getElementById("btnCrearCuenta");

    // Alterna la visibilidad de los botones
    btnIniciarGoogle.classList.toggle("d-none");
    btnCrearCuentaGoogle.classList.toggle("d-none");

    // Lógica adicional según la acción
    if (accion === 'iniciarSesionGoogle') {
        // Oculta el botón "Crear Cuenta" si está visible
        if (!btnCrearCuenta.classList.contains("d-none")) {
            btnCrearCuenta.classList.add("d-none");
        }
        // Muestra el botón "Iniciar Sesión"
        btnIniciarSesion.classList.remove("d-none");
        
        // Agrega aquí la lógica para iniciar sesión con Google
    } else if (accion === 'crearCuentaGoogle') {
        // Oculta el botón "Iniciar Sesión" si está visible
        if (!btnIniciarSesion.classList.contains("d-none")) {
            btnIniciarSesion.classList.add("d-none");
        }
        // Muestra el botón "Crear Cuenta"
        btnCrearCuenta.classList.remove("d-none");
        
        // Agrega aquí la lógica para crear cuenta con Google
        habilitarBotones();
    }
}  habilitarBotones();

function IniciarSesion(){
   
    var correo = document.getElementById('correo').value;
    var contrasena = document.getElementById('contrasena').value;

    if(correo === null || contrasena === null )
    {
        prompt("Vacio");
    }else{
        //Realiza una solicitud fetch para enviar los datos a Consultas.php
        fetch('Consultas.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'correo=' + correo + '&contrasena=' + contrasena
        })
        .then(response => response.json())
        .then(data => {
            console.log(data); // Maneja la respuesta del servidor
            if(data['mensaje'] === "Inicio de sesión exitoso"){
                var enlaceEspecifico = 'Interfaz_Central.html';

                        // Accede a la ventana principal desde la ventana secundaria
                var ventanaPrincipal = window.opener;

            // Cambia la ubicación de la ventana principal
            if (ventanaPrincipal) {
                ventanaPrincipal.location.href = enlaceEspecifico;
                // Cierra la ventana secundaria (ventana de inicio de sesión)
                window.close();
            } else {
                // Si window.opener es nulo, puedes abrir una nueva ventana si es necesario
                window.open(enlaceEspecifico, '_blank');
            }
            }
            // Aquí puedes realizar acciones dependiendo de la respuesta del servidor
        })
        .catch(error => console.error('Error al realizar la solicitud:', error));
    }
}

function iniciarSesionGoogle() {
    var enlaceEspecifico = '/Templates/Interfaz_Central.html';

    // Accede a la ventana principal desde la ventana secundaria
    var ventanaPrincipal = window.opener;

    // Cambia la ubicación de la ventana principal
    if (ventanaPrincipal) {
        ventanaPrincipal.location.href = enlaceEspecifico;
        // Cierra la ventana secundaria (ventana de inicio de sesión)
        window.close();
    } else {
        // Si window.opener es nulo, puedes abrir una nueva ventana si es necesario
        window.open(enlaceEspecifico, '_blank');
    }
}
function CrearCuentaGoogle(){

}
function CrearCuenta() {
    var comboBox = document.getElementById('rol');
    var correo = document.getElementById('correo').value;
    var contrasena = document.getElementById('contrasena').value;

    if (correo === null || contrasena === null) {
        // Puedes manejar el caso de campos nulos aquí si es necesario
    } else {
        // Obtén el valor seleccionado del elemento de la lista desplegable
        var rolSeleccionado = comboBox.options[comboBox.selectedIndex].value;

        // Realiza una solicitud fetch para enviar los datos a Consultas.php
        fetch('CrearCuenta.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            // Envía el valor seleccionado en lugar del elemento de la lista desplegable
            body: 'correo=' + correo + '&contrasena=' + contrasena + '&comboBox=' + rolSeleccionado
        })
        .then(response => response.json())
        .then(data => {
            console.log(data); // Maneja la respuesta del servidor
            if(data['mensaje'] === "Datos guardados en la base de datos"){
                var enlaceEspecifico = 'Interfaz_Central.html';

                                // Accede a la ventana principal desde la ventana secundaria
                        var ventanaPrincipal = window.opener;

                    // Cambia la ubicación de la ventana principal
                    if (ventanaPrincipal) {
                        ventanaPrincipal.location.href = enlaceEspecifico;
                        // Cierra la ventana secundaria (ventana de inicio de sesión)
                        window.close();
                    } else {
                        // Si window.opener es nulo, puedes abrir una nueva ventana si es necesario
                        window.open(enlaceEspecifico, '_blank');
                    }
            }
            // Aquí puedes realizar acciones dependiendo de la respuesta del servidor
        })
        .catch(error => console.error('Error al realizar la solicitud:', error));
    }
}
