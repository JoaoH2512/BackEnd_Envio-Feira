<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../services/Session.php';
Session::start();

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../models/Chat.php';

if (!Session::validarCsrf($_POST['csrf_token'] ?? '')) {
    http_response_code(403);
    echo json_encode(['sucesso' => false, 'erro' => 'Token de segurança inválido.']);
    exit;
}

if (empty($_SESSION['admin_autenticado']) && !isset($_SESSION['professor']['id'])) {
    http_response_code(401);
    echo json_encode(['sucesso' => false, 'erro' => 'Não autenticado.']);
    exit;
}

$conversaId = filter_var($_POST['conversa_id'] ?? '', FILTER_VALIDATE_INT);

if (!$conversaId || $conversaId <= 0) {
    echo json_encode(['sucesso' => false, 'erro' => 'Conversa inválida.']);
    exit;
}

$db = (new Database())->connect();
$chat = new Chat($db);

if (!empty($_SESSION['admin_autenticado'])) {
    $destinatario = 'admin';

    if (!$chat->conversaExiste((int) $conversaId)) {
        http_response_code(403);
        echo json_encode(['sucesso' => false, 'erro' => 'Conversa inexistente.']);
        exit;
    }
} else {
    $destinatario = 'professor';
    $professorId = (int) $_SESSION['professor']['id'];

    if (!$chat->conversaPertenceAoProfessor((int) $conversaId, $professorId)) {
        http_response_code(403);
        echo json_encode(['sucesso' => false, 'erro' => 'Acesso negado.']);
        exit;
    }
}

$chat->marcarComoLidas((int) $conversaId, $destinatario);

echo json_encode(['sucesso' => true]);
