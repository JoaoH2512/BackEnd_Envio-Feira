<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../services/Session.php';

Session::start();

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../models/Chat.php';

if (
    !Session::validarCsrf(
        $_POST['csrf_token'] ?? ''
    )
) {

    http_response_code(403);

    echo json_encode([
        'sucesso' => false,
        'erro' => 'Token de segurança inválido.'
    ]);

    exit;
}

$tipo = null;
$remetenteId = null;

if (
    !empty($_SESSION['admin_autenticado'])
) {

    $tipo = 'admin';

    $remetenteId = 0;

} elseif (
    isset($_SESSION['professor'])
) {

    $tipo = 'professor';

    $remetenteId =
        (int) $_SESSION['professor']['id'];

} else {

    http_response_code(401);

    echo json_encode([
        'sucesso' => false,
        'erro' => 'Usuário não autenticado.'
    ]);

    exit;
}

$mensagem =
    trim($_POST['mensagem'] ?? '');

if (
    $mensagem === ''
) {

    echo json_encode([
        'sucesso' => false,
        'erro' => 'Digite uma mensagem.'
    ]);

    exit;
}

if (
    mb_strlen($mensagem) > 2000
) {

    echo json_encode([
        'sucesso' => false,
        'erro' => 'A mensagem é muito grande.'
    ]);

    exit;
}

$database = new Database();

$db = $database->connect();

$chatModel = new Chat($db);

if ($tipo === 'professor') {

    $professorId =
        (int) $_SESSION['professor']['id'];

    $conversaId =
        $chatModel->criarOuBuscarConversa(
            $professorId
        );

} else {

    $conversaId =
        filter_var(
            $_POST['conversa_id'] ?? '',
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
}

$mensagemId =
    $chatModel->enviarMensagem(
        (int) $conversaId,
        $tipo,
        $remetenteId,
        $mensagem
    );

echo json_encode([
    'sucesso' => true,
    'mensagem_id' => $mensagemId,
    'conversa_id' => (int) $conversaId
]);