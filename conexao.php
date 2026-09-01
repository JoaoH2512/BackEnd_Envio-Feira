<?php
$host = "localhost";
$db   = "FeiraBD";
$user = "root";
$pass = "";   

try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=FeiraBD",
        "root",
        ""
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}
?>