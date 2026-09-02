<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../services/Session.php';
Session::start();

if (empty($_SESSION['admin_autenticado'])) {
    http_response_code(403);
    echo json_encode(['sucesso' => false, 'erro' => 'Acesso restrito ao administrador.']);
    exit;
}

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../models/Chat.php';

$chat = new Chat((new Database())->connect());

echo json_encode([
    'sucesso' => true,
    'conversas' => $chat->buscarTodasConversas(),
], JSON_UNESCAPED_UNICODE);
