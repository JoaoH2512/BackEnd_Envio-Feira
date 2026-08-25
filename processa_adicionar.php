<?php
require_once "conexao.php";

    // Se o formulário for enviado
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nome = $_POST['nome'];
        $rm = $_POST['rm'];
        $email = $_POST['email'];

        $sql = "INSERT INTO professores (nome, rm, email)
            VALUES (:nome, :rm, :email)";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':rm', $rm);
        $stmt->bindParam(':email', $email);

        if ($stmt->execute()) {
            echo "<script>alert('Professor atualizado com sucesso!'); window.location='listar.php';</script>";
            exit;
        } else {
            echo "<script>alert('Erro ao atualizar professor!');</script>";
        }
    }

?>