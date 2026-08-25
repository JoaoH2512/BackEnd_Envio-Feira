<?php

declare(strict_types=1);

require_once __DIR__ . '/../services/Session.php';

Session::start();

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../config/AdminConfig.php';
require_once __DIR__ . '/../models/Professor.php';

$database = new Database();
$db = $database->connect();

$professorModel = new Professor($db);

$csrfToken = Session::csrfToken();

$erro = '';
$sucesso = '';

if (!isset($_SESSION['admin_autenticado'])) {
    $_SESSION['admin_autenticado'] = false;
}

/*
|--------------------------------------------------------------------------
| LOGIN DO ADMINISTRADOR
|--------------------------------------------------------------------------
*/

if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    ($_POST['acao'] ?? '') === 'entrar_admin'
) {

    if (
        !Session::validarCsrf(
            $_POST['csrf_token'] ?? ''
        )
    ) {

        $erro = 'Não foi possível realizar a operação.';

    } else {

        $codigo = $_POST['codigo_secreto'] ?? '';

        if (
            hash_equals(
                AdminConfig::getSecretCode(),
                $codigo
            )
        ) {

            Session::regenerar();

            $_SESSION['admin_autenticado'] = true;

            header('Location: admin.php');
            exit;

        } else {

            $erro = 'Código administrativo inválido.';
        }
    }
}

/*
|--------------------------------------------------------------------------
| CADASTRO DO PROFESSOR
|--------------------------------------------------------------------------
*/

if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    ($_POST['acao'] ?? '') === 'cadastrar_professor'
) {

    if (
        empty($_SESSION['admin_autenticado'])
    ) {

        header('Location: admin.php');
        exit;
    }

    if (
        !Session::validarCsrf(
            $_POST['csrf_token'] ?? ''
        )
    ) {

        $erro = 'Não foi possível realizar a operação.';

    } else {

        $nome = trim($_POST['nome'] ?? '');
        $ra = trim($_POST['ra'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';

        if (
            $nome === '' ||
            $ra === '' ||
            $email === '' ||
            $senha === ''
        ) {

            $erro = 'Preencha todos os campos.';

        } elseif (
            !filter_var(
                $email,
                FILTER_VALIDATE_EMAIL
            )
        ) {

            $erro = 'Digite um e-mail válido.';

        } elseif (
            strlen($nome) > 150
        ) {

            $erro = 'O nome é muito grande.';

        } elseif (
            strlen($ra) > 30
        ) {

            $erro = 'O RA é muito grande.';

        } elseif (
            strlen($senha) < 8
        ) {

            $erro =
                'A senha precisa ter pelo menos 8 caracteres.';

        } elseif (
            $professorModel->emailExiste($email)
        ) {

            $erro =
                'Este e-mail já está cadastrado.';

        } elseif (
            $professorModel->raExiste($ra)
        ) {

            $erro =
                'Este RA já está cadastrado.';

        } else {

            try {

                $professorModel->criar(
                    $nome,
                    $ra,
                    $email,
                    $senha
                );

                $sucesso =
                    'Professor cadastrado com sucesso!';

            } catch (PDOException $e) {

                $erro =
                    'Não foi possível cadastrar o professor.';
            }
        }
    }
}

/*
|--------------------------------------------------------------------------
| EXCLUSÃO
|--------------------------------------------------------------------------
*/

if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    ($_POST['acao'] ?? '') === 'excluir_professor'
) {

    if (
        empty($_SESSION['admin_autenticado'])
    ) {

        header('Location: admin.php');
        exit;
    }

    if (
        !Session::validarCsrf(
            $_POST['csrf_token'] ?? ''
        )
    ) {

        $erro = 'Não foi possível realizar a operação.';

    } else {

        $id = filter_var(
            $_POST['id'] ?? '',
            FILTER_VALIDATE_INT
        );

        if (
            $id === false ||
            $id <= 0
        ) {

            $erro = 'Professor inválido.';

        } else {

            try {

                $professorModel->excluir($id);

                $sucesso =
                    'Professor removido com sucesso.';

            } catch (PDOException $e) {

                $erro =
                    'Não foi possível remover o professor.';
            }
        }
    }
}

$professores = [];

if (
    !empty($_SESSION['admin_autenticado'])
) {

    $professores =
        $professorModel->buscarTodos();
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
        Administração | Professores
    </title>

    <link
        rel="stylesheet"
        href="css/style.css"
    >

</head>

<body>

<?php if (
    empty($_SESSION['admin_autenticado'])
): ?>

    <main class="auth-page">

        <section class="auth-card">

            <div class="auth-icon">
                🔐
            </div>

            <h1>
                Área administrativa
            </h1>

            <p>
                Digite o código secreto para continuar.
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

            <form method="POST">

                <input
                    type="hidden"
                    name="acao"
                    value="entrar_admin"
                >

                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?= htmlspecialchars(
                        $csrfToken,
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                >

                <div class="form-group">

                    <label for="codigo_secreto">
                        Código secreto
                    </label>

                    <input
                        type="password"
                        id="codigo_secreto"
                        name="codigo_secreto"
                        placeholder="Digite o código"
                        autocomplete="off"
                        required
                    >

                </div>

                <button
                    type="submit"
                    class="button button-primary button-full"
                >
                    Acessar painel
                </button>

            </form>

        </section>

    </main>

<?php else: ?>

    <nav class="navbar">

        <a
            href="admin.php"
            class="navbar-brand"
        >
            🎓 Sistema Escolar
        </a>

        <div class="navbar-links">

            <a
                href="admin.php"
                class="nav-button active"
            >
                👨‍🏫 Professores
            </a>

            <a
                href="admin-chat.php"
                class="nav-button"
            >
                💬 Chat
            </a>

            <a
                href="admin-logout.php"
                class="nav-button nav-danger"
            >
                🚪 Sair
            </a>

        </div>

    </nav>

    <main class="page-container">

        <header class="page-header">

            <div>

                <span class="eyebrow">
                    PAINEL ADMINISTRATIVO
                </span>

                <h1>
                    Gerenciamento de professores
                </h1>

                <p>
                    Cadastre e gerencie as contas dos professores.
                </p>

            </div>

            <div class="admin-status">
                🔐 ADM conectado
            </div>

        </header>

        <?php if ($erro): ?>

            <div class="alert alert-error">

                <?= htmlspecialchars(
                    $erro,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>

            </div>

        <?php endif; ?>

        <?php if ($sucesso): ?>

            <div class="alert alert-success">

                <?= htmlspecialchars(
                    $sucesso,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>

            </div>

        <?php endif; ?>

        <section class="content-grid">

            <div class="content-card">

                <div class="card-header">

                    <h2>
                        Cadastrar professor
                    </h2>

                    <p>
                        Crie uma nova conta para acesso ao sistema.
                    </p>

                </div>

                <form method="POST">

                    <input
                        type="hidden"
                        name="acao"
                        value="cadastrar_professor"
                    >

                    <input
                        type="hidden"
                        name="csrf_token"
                        value="<?= htmlspecialchars(
                            $csrfToken,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>"
                    >

                    <div class="form-group">

                        <label for="nome">
                            Nome completo
                        </label>

                        <input
                            type="text"
                            id="nome"
                            name="nome"
                            maxlength="150"
                            placeholder="Nome do professor"
                            required
                        >

                    </div>

                    <div class="form-group">

                        <label for="ra">
                            RA
                        </label>

                        <input
                            type="text"
                            id="ra"
                            name="ra"
                            maxlength="30"
                            placeholder="Ex: 123456"
                            required
                        >

                    </div>

                    <div class="form-group">

                        <label for="email">
                            E-mail
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            maxlength="255"
                            placeholder="professor@email.com"
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
                            minlength="8"
                            placeholder="Mínimo de 8 caracteres"
                            autocomplete="new-password"
                            required
                        >

                    </div>

                    <button
                        type="submit"
                        class="button button-primary button-full"
                    >
                        Cadastrar professor
                    </button>

                </form>

            </div>

            <div class="content-card">

                <div class="card-header">

                    <h2>
                        Professores cadastrados
                    </h2>

                    <p>
                        <?= count($professores) ?>
                        professor(es) cadastrado(s).
                    </p>

                </div>

                <?php if (
                    empty($professores)
                ): ?>

                    <div class="empty-state">

                        <div class="empty-icon">
                            👨‍🏫
                        </div>

                        <p>
                            Nenhum professor cadastrado ainda.
                        </p>

                    </div>

                <?php else: ?>

                    <div class="professors-list">

                        <?php foreach (
                            $professores as $professor
                        ): ?>

                            <article class="professor-item">

                                <div class="professor-info">

                                    <div class="avatar">

                                        <?= htmlspecialchars(
                                            strtoupper(
                                                substr(
                                                    $professor['nome'],
                                                    0,
                                                    1
                                                )
                                            ),
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>

                                    </div>

                                    <div>

                                        <h3>

                                            <?= htmlspecialchars(
                                                $professor['nome'],
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>

                                        </h3>

                                        <p>
                                            RA:
                                            <?= htmlspecialchars(
                                                $professor['ra'],
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        </p>

                                        <p>
                                            <?= htmlspecialchars(
                                                $professor['email'],
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        </p>

                                    </div>

                                </div>

                                <form
                                    method="POST"
                                    onsubmit="
                                        return confirm(
                                            'Deseja realmente excluir este professor?'
                                        );
                                    "
                                >

                                    <input
                                        type="hidden"
                                        name="acao"
                                        value="excluir_professor"
                                    >

                                    <input
                                        type="hidden"
                                        name="id"
                                        value="<?= (int) $professor['id'] ?>"
                                    >

                                    <input
                                        type="hidden"
                                        name="csrf_token"
                                        value="<?= htmlspecialchars(
                                            $csrfToken,
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>"
                                    >

                                    <button
                                        type="submit"
                                        class="button button-danger"
                                    >
                                        Excluir
                                    </button>

                                </form>

                            </article>

                        <?php endforeach; ?>

                    </div>

                <?php endif; ?>

            </div>

        </section>

    </main>

<?php endif; ?>

</body>

</html>