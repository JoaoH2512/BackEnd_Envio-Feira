<?php
declare(strict_types=1);

require_once __DIR__ . '/../lib/services/Session.php';
Session::start();
Session::destruir();

header('Location: login.php');
exit;
