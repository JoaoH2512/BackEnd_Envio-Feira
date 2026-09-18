<?php
declare(strict_types=1);

require_once __DIR__ . '/../lib/services/Session.php';
Session::start();

require_once __DIR__ . '/../lib/config/Database.php';
require_once __DIR__ . '/../lib/models/Professor.php';
require_once __DIR__ . '/../lib/services/Auth.php';

$auth = new Auth(new Professor((new Database())->connect()));

if (!$auth->estaAutenticado()) {
    header('Location: login.php');
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
<link rel="stylesheet" href="../style.css">
</head>
<body>

<nav class="barra-navegacao">
<a href="dashboard.php" class="marca-navegacao">🎓 Sistema Escolar</a>
<div class="links-navegacao">
<a href="dashboard.php" class="botao-navegacao ativo">🏠 Início</a>
<a href="chat.php" class="botao-navegacao">💬 Fale conosco</a>
<div class="distintivo-usuario">👤 <?= htmlspecialchars($professor['nome'], ENT_QUOTES, 'UTF-8') ?><span>PROFESSOR</span></div>
<a href="logout.php" class="botao-navegacao navegacao-perigo">🚪 Sair</a>
</div>
</nav>

<main class="container-pagina">
<section class="cartao-boas-vindas">
<span class="rotulo-destaque">ÁREA DO PROFESSOR</span>
<h1>Bem-vindo, <?= htmlspecialchars($professor['nome'], ENT_QUOTES, 'UTF-8') ?>!</h1>
<p>Aqui estão suas informações cadastradas. Pelo botão “Fale conosco”, você pode conversar diretamente com a administração.</p>

<div class="grade-perfil">
<div class="cartao-perfil"><span>Nome</span><strong><?= htmlspecialchars($professor['nome'], ENT_QUOTES, 'UTF-8') ?></strong></div>
<div class="cartao-perfil"><span>RA</span><strong><?= htmlspecialchars($professor['ra'], ENT_QUOTES, 'UTF-8') ?></strong></div>
<div class="cartao-perfil"><span>E-mail</span><strong><?= htmlspecialchars($professor['email'], ENT_QUOTES, 'UTF-8') ?></strong></div>
<div class="cartao-perfil"><span>Tipo</span><strong><?= htmlspecialchars(ucfirst($professor['tipo']), ENT_QUOTES, 'UTF-8') ?></strong></div>
</div>

<a href="chat.php" class="botao botao-principal">💬 Fale conosco</a>
</section>
</main>
</body>
</html>
