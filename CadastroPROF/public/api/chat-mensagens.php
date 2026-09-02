<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../services/Session.php';
Session::start();

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../models/Chat.php';

if (empty($_SESSION['admin_autenticado']) && !isset($_SESSION['professor']['id'])) {
    http_response_code(401);
    echo json_encode(['sucesso' => false, 'erro' => 'Não autenticado.']);
    exit;
}

$conversaId = filter_input(INPUT_GET, 'conversa_id', FILTER_VALIDATE_INT);

if (!$conversaId || $conversaId <= 0) {
    http_response_code(400);
    echo json_encode(['sucesso' => false, 'erro' => 'Conversa inválida.']);
    exit;
}

$db = (new Database())->connect();
$chat = new Chat($db);

if (empty($_SESSION['admin_autenticado'])) {
    $professorId = (int) $_SESSION['professor']['id'];

    if (!$chat->conversaPertenceAoProfessor($conversaId, $professorId)) {
        http_response_code(403);
        echo json_encode(['sucesso' => false, 'erro' => 'Acesso negado.']);
        exit;
    }
}

$mensagens = $chat->buscarMensagens((int) $conversaId);

$destinatario = !empty($_SESSION['admin_autenticado']) ? 'admin' : 'professor';
$chat->marcarRecebidas((int) $conversaId, $destinatario);

echo json_encode([
    'sucesso' => true,
    'mensagens' => $mensagens,
], JSON_UNESCAPED_UNICODE);
