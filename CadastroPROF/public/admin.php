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
require_once __DIR__ . '/../config/AdminConfig.php';
require_once __DIR__ . '/../models/Professor.php';

$database = new Database();
$db = $database->connect();

$professorModel = new Professor($db);

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(
        random_bytes(32)
    );
}

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
    isset($_POST['acao']) &&
    $_POST['acao'] === 'entrar_admin'
) {

    $csrfToken = $_POST['csrf_token'] ?? '';

    if (
        !hash_equals(
            $_SESSION['csrf_token'],
            $csrfToken
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

            session_regenerate_id(true);

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
    isset($_POST['acao']) &&
    $_POST['acao'] === 'cadastrar_professor'
) {

    if (!$_SESSION['admin_autenticado']) {

        header('Location: admin.php');

        exit;
    }

    $csrfToken = $_POST['csrf_token'] ?? '';

    if (
        !hash_equals(
            $_SESSION['csrf_token'],
            $csrfToken
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

        } elseif (strlen($nome) > 150) {

            $erro = 'O nome é muito grande.';

        } elseif (strlen($ra) > 30) {

            $erro = 'O RA é muito grande.';

        } elseif (strlen($senha) < 8) {

            $erro = 'A senha precisa ter pelo menos 8 caracteres.';

        } elseif ($professorModel->emailExiste($email)) {

            $erro = 'Este e-mail já está cadastrado.';

        } elseif ($professorModel->raExiste($ra)) {

            $erro = 'Este RA já está cadastrado.';

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
    isset($_POST['acao']) &&
    $_POST['acao'] === 'excluir_professor'
) {

    if (!$_SESSION['admin_autenticado']) {

        header('Location: admin.php');

        exit;
    }

    $csrfToken = $_POST['csrf_token'] ?? '';

    if (
        !hash_equals(
            $_SESSION['csrf_token'],
            $csrfToken
        )
    ) {

        $erro = 'Não foi possível realizar a operação.';

    } else {

        $id = filter_var(
            $_POST['id'] ?? '',
            FILTER_VALIDATE_INT
        );

        if ($id === false || $id <= 0) {

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

/*
|--------------------------------------------------------------------------
| BUSCAR PROFESSORES
|--------------------------------------------------------------------------
*/

$professores = [];

if ($_SESSION['admin_autenticado']) {

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
        href="css/admin.css"
    >

</head>

<body>

<?php if (!$_SESSION['admin_autenticado']): ?>

    <main class="admin-login">

        <section class="admin-login-card">

            <div class="admin-icon">
                🔐
            </div>

            <h1>
                Área administrativa
            </h1>

            <p>
                Digite o código secreto para continuar.
            </p>

            <?php if ($erro): ?>

                <div class="alert error">
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
                        $_SESSION['csrf_token'],
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                >

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

                <button type="submit">
                    Acessar painel
                </button>

            </form>

        </section>

    </main>

<?php else: ?>

    <main class="admin-container">

        <header class="admin-header">

            <div>

                <span class="admin-label">
                    PAINEL ADMINISTRATIVO
                </span>

                <h1>
                    Gerenciamento de professores
                </h1>

                <p>
                    Cadastre e gerencie as contas dos professores.
                </p>

            </div>

            <a
                href="admin-logout.php"
                class="logout"
            >
                Sair
            </a>

        </header>

        <?php if ($erro): ?>

            <div class="alert error">
                <?= htmlspecialchars(
                    $erro,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </div>

        <?php endif; ?>

        <?php if ($sucesso): ?>

            <div class="alert success">
                <?= htmlspecialchars(
                    $sucesso,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </div>

        <?php endif; ?>

        <section class="content-grid">

            <div class="card">

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
                            $_SESSION['csrf_token'],
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
                        class="primary-button"
                    >
                        Cadastrar professor
                    </button>

                </form>

            </div>

            <div class="card">

                <div class="card-header">

                    <h2>
                        Professores cadastrados
                    </h2>

                    <p>
                        <?= count($professores) ?>
                        professor(es) cadastrado(s).
                    </p>

                </div>

                <?php if (empty($professores)): ?>

                    <div class="empty">

                        <span>
                            👨‍🏫
                        </span>

                        <p>
                            Nenhum professor cadastrado ainda.
                        </p>

                    </div>

                <?php else: ?>

                    <div class="professors-list">

                        <?php foreach ($professores as $professor): ?>

                            <article class="professor">

                                <div class="professor-info">

                                    <div class="avatar">
                                        <?= strtoupper(
                                            substr(
                                                $professor['nome'],
                                                0,
                                                1
                                            )
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
                                            $_SESSION['csrf_token'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>"
                                    >

                                    <button
                                        type="submit"
                                        class="delete-button"
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