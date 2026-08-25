<?php

declare(strict_types=1);

require_once __DIR__ . '/../../services/Session.php';

Session::start();

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../models/Professor.php';
require_once __DIR__ . '/../../services/Auth.php';

if (
    isset($_SESSION['professor'])
) {

    header('Location: ../dashboard.php');

    exit;
}

$database = new Database();

$db = $database->connect();

$professorModel = new Professor($db);

$auth = new Auth($professorModel);

$erro = '';

if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
) {

    $email = trim(
        $_POST['email'] ?? ''
    );

    $senha = $_POST['senha'] ?? '';

    if (
        $email === '' ||
        $senha === ''
    ) {

        $erro =
            'Preencha o e-mail e a senha.';

    } elseif (
        !filter_var(
            $email,
            FILTER_VALIDATE_EMAIL
        )
    ) {

        $erro =
            'Digite um e-mail válido.';

    } elseif (
        !$auth->login(
            $email,
            $senha
        )
    ) {

        $erro =
            'E-mail ou senha inválidos.';
    }

    if (
        $erro === '' &&
        $auth->estaAutenticado()
    ) {

        header(
            'Location: ../dashboard.php'
        );

        exit;
    }
}

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
        Login | Sistema Escolar
    </title>

    <link
        rel="stylesheet"
        href="../css/style.css"
    >

</head>

<body>

    <main class="auth-page">

        <section class="auth-card">

            <div class="auth-icon">
                🎓
            </div>

            <h1>
                Sistema Escolar
            </h1>

            <p>
                Entre com sua conta de professor.
            </p>

            <?php if ($erro): ?>

                <div class="alert alert-error">

                    <?= htmlspecialchars(
                        $erro,
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>

                </div>

            <?php endif; ?>

            <form
                method="POST"
                class="auth-form"
            >

                <div class="form-group">

                    <label for="email">
                        E-mail
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="professor@email.com"
                        autocomplete="email"
                        required
                    >

                </div>

                <div class="form-group">

                    <label for="senha">
                        Senha
                    </label>

                    <input
                        type="password"
                        id="senha"
                        name="senha"
                        placeholder="Digite sua senha"
                        autocomplete="current-password"
                        required
                    >

                </div>

                <button
                    type="submit"
                    class="button button-primary button-full"
                >
                    Entrar
                </button>

            </form>

            <div class="auth-footer">

                <a href="../admin.php">
                    Acesso administrativo
                </a>

            </div>

        </section>

    </main>

</body>

</html>