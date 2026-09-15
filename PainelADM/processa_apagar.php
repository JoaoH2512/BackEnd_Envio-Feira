<?php
require '../conexao.php';
header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $id = $_GET['id'];


    // DELETA O PROFESSOR DO BANCO
    $sql = "DELETE FROM professores WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        echo json_encode(['sucesso' => true]);
    } else {
        echo json_encode(['sucesso' => false, 'erro' => 'Erro ao remover professor']);
    }
    exit;
}

echo json_encode(['sucesso' => false, 'erro' => 'ID não informado']);