document.getElementById('register-form').addEventListener('submit', function (e) {
    const password = document.getElementById('password').value;
    const passwordConfirmation = document.getElementById('password_confirmation').value;
    const errorMessage = document.getElementById('error-message');

    if (password !== passwordConfirmation) {
        e.preventDefault();
        errorMessage.classList.remove('hidden');
    } else {
        errorMessage.classList.add('hidden');
    }
});
