<?php
require_once __DIR__ . '/lib/services/Session.php';
Session::start();
Session::destruir();
header('Location: admin.php');
exit;
