function showLoginForm(type) {
    // Hide all forms
    document.getElementById('telefonoForm').style.display = 'none';
    document.getElementById('correoForm').style.display = 'none';

    // Show the selected form
    document.getElementById(type + 'Form').style.display = 'block';
   
}
var form1 = document.getElementById("telefonoForm");
var form2 = document.getElementById("correoForm");

// Variables para rastrear el estado de los formularios
var activeForm = null;

function buttonClick(formId) {
var formContainer = document.getElementById(formId);

// Si ya hay un formulario activo y es diferente al clicado, lo oculta
if (activeForm !== null && activeForm !== formContainer) {
activeForm.classList.remove('show');
}

// Si el formulario clicado está activo, lo oculta
if (formContainer.classList.contains('show')) {
formContainer.classList.remove('show');
activeForm = null;
} else {
// Si no, lo muestra y actualiza el formulario activo
formContainer.classList.add('show');
activeForm = formContainer;
}
}


function buttonHover(buttonId) {
    var button = document.getElementById(buttonId);
    button.style.transform = 'scale(1.1)';
}

function buttonHoverOut(buttonId) {
    var button = document.getElementById(buttonId);
    button.style.transform = 'scale(1)';
}