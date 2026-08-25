<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../services/Session.php';

Session::start();

if (
    empty($_SESSION['admin_autenticado'])
) {

    http_response_code(403);

    echo json_encode([
        'sucesso' => false,
        'erro' => 'Acesso restrito ao administrador.'
    ]);

    exit;
}

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../models/Chat.php';

$database =
    new Database();

$db =
    $database->connect();

$chatModel =
    new Chat($db);

$conversas =
    $chatModel->buscarTodasConversas();

echo json_encode([
    'sucesso' => true,
    'conversas' => $conversas
]);