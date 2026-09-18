document.addEventListener('DOMContentLoaded', function () {
  const password = document.getElementById('password');
  const togglePassword = document.getElementById('toggle-password');
  const loginReturn = document.getElementById('login-return');

  if (togglePassword && password) {
    togglePassword.addEventListener('click', function () {
      const visible = password.type === 'text';
      password.type = visible ? 'password' : 'text';
      togglePassword.setAttribute('aria-label', visible ? 'Mostrar senha' : 'Ocultar senha');
    });
  }

  if (loginReturn) {
    loginReturn.addEventListener('click', function () {
      if (window.history.length > 1) {
        window.history.back();
      } else {
        window.location.href = 'login.php';
      }
    });
  }
});
