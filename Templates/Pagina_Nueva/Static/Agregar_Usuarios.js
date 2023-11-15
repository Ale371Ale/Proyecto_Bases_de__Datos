function showForm(type) {
    var compradorForm = document.getElementById('compradorForm');
    var vendedorForm = document.getElementById('vendedorForm');
    
    // Verificar si el formulario ya está visible
    var isCompradorFormVisible = compradorForm.classList.contains('show');
    var isVendedorFormVisible = vendedorForm.classList.contains('show');

    // Oculta todos los formularios si es necesario
    if (isCompradorFormVisible) {
        compradorForm.classList.remove('show');
        compradorForm.classList.add('hidden');
    }

    if (isVendedorFormVisible) {
        vendedorForm.classList.remove('show');
        vendedorForm.classList.add('hidden');
    }

    // Muestra el formulario correspondiente si no estaba visible
    if (type === 'comprador' && !isCompradorFormVisible) {
        compradorForm.classList.remove('hidden');
        compradorForm.classList.add('show');
    } else if (type === 'vendedor' && !isVendedorFormVisible) {
        vendedorForm.classList.remove('hidden');
        vendedorForm.classList.add('show');
    }
}

document.getElementById('showPassword').addEventListener('change', function () {
    var passwordInput = document.getElementById('floatingPassword');
    passwordInput.type = this.checked ? 'text' : 'password';

    // Cambiar la imagen del ojo según el estado del checkbox
    var eyeImage = this.checked ? 'Static/src/ojo abierto.png' : 'Static/src/ojo cerrado.png';
    this.nextElementSibling.querySelector('img').src = eyeImage;
});