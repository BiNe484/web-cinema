flatpickr('.dateofbirthtextbox', {
    
    dateFormat: 'd/m/Y',
    maxDate: 'today',
    locale: 'vi',

});

function togglePasswordVisibility(fieldId, iconClass) {
    var passwordInput = document.getElementById(fieldId);
    var icon = document.querySelector('.' + iconClass);
    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        icon.classList.add('fa-eye-slash');
        icon.classList.remove('fa-eye');
    } else {
        passwordInput.type = "password";
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}