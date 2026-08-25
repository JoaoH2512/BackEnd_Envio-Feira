<?php
require_once "conexao.php"

    $id = $_GET['id'];

    // Se o formulário for enviado
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
            echo "<script>alert('Professor atualizado com sucesso!'); window.location='listar.php';</script>";
            exit;
        } else {
            echo "<script>alert('Erro ao atualizar professor!');</script>";
        }
    }

    // Buscar os dados do produto
    $sql = "SELECT * FROM professores WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);
?>