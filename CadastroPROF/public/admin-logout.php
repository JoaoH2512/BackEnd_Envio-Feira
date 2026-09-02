<?php
declare(strict_types=1);

require_once __DIR__ . '/../services/Session.php';
Session::start();

unset($_SESSION['admin_autenticado']);

Session::regenerar();

header('Location: admin.php');
exit;
