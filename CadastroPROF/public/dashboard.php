<?php
declare(strict_types=1);

require_once __DIR__ . '/../services/Session.php';
Session::start();

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Professor.php';
require_once __DIR__ . '/../services/Auth.php';

$auth = new Auth(new Professor((new Database())->connect()));

if (!$auth->estaAutenticado()) {
    header('Location: chat/');
    exit;
}

$professor = $auth->professor();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Painel do Professor | Sistema Escolar</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav class="navbar">
<a href="dashboard.php" class="navbar-brand">🎓 Sistema Escolar</a>
<div class="navbar-links">
<a href="dashboard.php" class="nav-button active">🏠 Início</a>
<a href="chat.php" class="nav-button">💬 Fale conosco</a>
<div class="user-badge">👤 <?= htmlspecialchars($professor['nome'], ENT_QUOTES, 'UTF-8') ?><span>PROFESSOR</span></div>
<a href="logout.php" class="nav-button nav-danger">🚪 Sair</a>
</div>
</nav>

<main class="page-container">
<section class="welcome-card">
<span class="eyebrow">ÁREA DO PROFESSOR</span>
<h1>Bem-vindo, <?= htmlspecialchars($professor['nome'], ENT_QUOTES, 'UTF-8') ?>!</h1>
<p>Aqui estão suas informações cadastradas. Pelo botão “Fale conosco”, você pode conversar diretamente com a administração.</p>

<div class="profile-grid">
<div class="profile-card"><span>Nome</span><strong><?= htmlspecialchars($professor['nome'], ENT_QUOTES, 'UTF-8') ?></strong></div>
<div class="profile-card"><span>RA</span><strong><?= htmlspecialchars($professor['ra'], ENT_QUOTES, 'UTF-8') ?></strong></div>
<div class="profile-card"><span>E-mail</span><strong><?= htmlspecialchars($professor['email'], ENT_QUOTES, 'UTF-8') ?></strong></div>
<div class="profile-card"><span>Tipo</span><strong><?= htmlspecialchars(ucfirst($professor['tipo']), ENT_QUOTES, 'UTF-8') ?></strong></div>
</div>

<a href="chat.php" class="button button-primary">💬 Fale conosco</a>
</section>
</main>
</body>
</html>
