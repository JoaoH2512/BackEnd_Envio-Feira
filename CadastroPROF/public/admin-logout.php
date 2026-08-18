<?php

declare(strict_types=1);

session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => isset($_SERVER['HTTPS']),
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();

unset($_SESSION['admin_autenticado']);

session_regenerate_id(true);

header('Location: admin.php');

exit;