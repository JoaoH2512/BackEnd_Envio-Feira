<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../services/Session.php';

Session::start();

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../models/Chat.php';

if (
    empty($_SESSION['admin_autenticado']) &&
    !isset($_SESSION['professor'])
) {

    http_response_code(401);

    echo json_encode([
        'sucesso' => false,
        'erro' => 'Não autenticado.'
    ]);

    exit;
}

$conversaId =
    filter_input(
        INPUT_GET,
        'conversa_id',
        FILTER_VALIDATE_INT
    );

if (
    !$conversaId ||
    $conversaId <= 0
) {

    echo json_encode([
        'sucesso' => false,
        'erro' => 'Conversa inválida.'
    ]);

    exit;
}

$database =
    new Database();

$db =
    $database->connect();

$chatModel =
    new Chat($db);

$mensagens =
    $chatModel->buscarMensagens(
        (int) $conversaId
    );

$destinatario =
    !empty($_SESSION['admin_autenticado'])
        ? 'admin'
        : 'professor';

$chatModel->marcarRecebidas(
    (int) $conversaId,
    $destinatario
);

echo json_encode([
    'sucesso' => true,
    'mensagens' => $mensagens
]);