<?php

session_start();

require_once '../conexao.php';

$email = $_POST['email'] ?? '';
$matricula = $_POST['matricula'] ?? '';
$senha = $_POST['senha'] ?? '';

$sql = "SELECT *
        FROM professor
        WHERE email = :email";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':email', $email);
$stmt->execute();

$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if ($usuario) {

    if (
        $usuario['matricula'] == $matricula &&
        $usuario['senha'] == $senha
    ) {

        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['usuario_email'] = $usuario['email'];
        $_SESSION['usuario_matricula'] = $usuario['matricula'];
        $_SESSION['tipo'] = $usuario['tipo'];

        header('Location: ../PainelADM/listar.php');
        exit;

    } else {

        echo "Matrícula ou senha incorretas.";
        exit;
    }

} else {

    echo "E-mail, matrícula ou senha incorretos.";
    exit;
}

?>
