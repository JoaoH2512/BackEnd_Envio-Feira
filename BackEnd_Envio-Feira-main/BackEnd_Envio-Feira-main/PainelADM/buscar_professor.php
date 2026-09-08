<?php
require_once "../conexao.php";
header('Content-Type: application/json');

$id = $_GET['id'] ?? null;

if (!$id) {
    echo json_encode(['erro' => 'ID não informado']);
    exit;
}

$sql = "SELECT id, nome, rm, email FROM professores WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $id);
$stmt->execute();

$professor = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode($professor);