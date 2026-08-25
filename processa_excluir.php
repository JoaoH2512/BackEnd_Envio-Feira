<?php
require 'conexao.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // garante que é número inteiro para segurança

    $sql = "DELETE FROM professores WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // Redireciona para a página de listagem com sucesso
        header('Location: listar.php?msg=apagado');
        exit;
    } else {
        echo "Erro ao apagar o produto.";
    }
} else {
    echo "ID do produto não informado.";
}
?>