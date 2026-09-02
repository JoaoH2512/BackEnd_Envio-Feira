<?php
declare(strict_types=1);

require_once __DIR__ . '/../../services/Session.php';
Session::start();

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../models/Professor.php';
require_once __DIR__ . '/../../services/Auth.php';

if (isset($_SESSION['professor']['id'])) {
    header('Location: ../dashboard.php');
    exit;
}

$auth = new Auth(new Professor((new Database())->connect()));
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ra = trim($_POST['ra'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if ($ra === '' || $senha === '') {
        $erro = 'Preencha o RA e a senha.';
    } elseif (!$auth->loginPorRA($ra, $senha)) {
        $erro = 'RA ou senha inválidos.';
    } else {
        header('Location: ../dashboard.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login | Sistema Escolar</title>
<link rel="stylesheet" href="../css/style.css">
</head>
<body>
<main class="auth-page">
<section class="auth-card">
<div class="auth-icon">🎓</div>
<h1>Sistema Escolar</h1>
<p>Entre com seu RA e senha de professor.</p>

<?php if ($erro): ?>
<div class="alert alert-error"><?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?></div>
<?php endif; ?>

<form method="POST" class="auth-form">
<div class="form-group">
<label for="ra">RA</label>
<input type="text" id="ra" name="ra" maxlength="50" placeholder="Digite seu RA" autocomplete="username" required>
</div>

<div class="form-group">
<label for="senha">Senha</label>
<input type="password" id="senha" name="senha" placeholder="Digite sua senha" autocomplete="current-password" required>
</div>

<button type="submit" class="button button-primary button-full">Entrar</button>
</form>

<div class="auth-footer">
<a href="../admin.php">Acesso administrativo</a>
</div>
</section>
</main>
</body>
</html>