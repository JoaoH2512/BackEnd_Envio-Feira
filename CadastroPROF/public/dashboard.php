<?php

declare(strict_types=1);

session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => isset($_SERVER['HTTPS']),
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Professor.php';
require_once __DIR__ . '/../services/Auth.php';

$database = new Database();

$db = $database->connect();

$professorModel = new Professor($db);

$auth = new Auth($professorModel);

if (!$auth->estaAutenticado()) {

    header('Location: index.php');

    exit;
}

$professor = $auth->professor();

?>

<!DOCTYPE html>

<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Bem-vindo | Área do Professor
    </title>

    <link
        rel="stylesheet"
        href="css/style.css"
    >

</head>

<body>

    <main class="login-container">

        <section class="login-card">

            <div class="login-header">

                <div class="logo">
                    ✓
                </div>

                <h1>
                    Bem-vindo!
                </h1>

                <p>
                    É bom ter você por aqui,
                    <?= htmlspecialchars(
                        $professor['nome'],
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>.
                </p>

            </div>

            <div class="form-group">

                <strong>
                    Nome:
                </strong>

                <br>

                <?= htmlspecialchars(
                    $professor['nome'],
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>

            </div>

            <div class="form-group">

                <strong>
                    RA:
                </strong>

                <br>

                <?= htmlspecialchars(
                    $professor['ra'],
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>

            </div>

            <div class="form-group">

                <strong>
                    E-mail:
                </strong>

                <br>

                <?= htmlspecialchars(
                    $professor['email'],
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>

            </div>

            <a
                href="logout.php"
                class="login-button"
            >
                Sair da conta
            </a>

        </section>

    </main>

</body>

</html>