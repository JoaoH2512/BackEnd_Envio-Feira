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

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Professor.php';
require_once __DIR__ . '/../services/Auth.php';

$database = new Database();
$db = $database->connect();

$professorModel = new Professor($db);
$auth = new Auth($professorModel);

$auth->logout();

header(
    'Location: index.php'
);

exit;