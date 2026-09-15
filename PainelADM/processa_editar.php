<?php
require_once "../conexao.php";
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $rm = $_POST['rm'];
    $email = $_POST['email'];

    $sql = "UPDATE professores SET nome = :nome, rm = :rm, email = :email WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':rm', $rm);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        echo json_encode(['sucesso' => true]);
    } else {
        echo json_encode(['sucesso' => false]);
    }
    exit;
}

echo json_encode(['sucesso' => false, 'erro' => 'Método inválido']);