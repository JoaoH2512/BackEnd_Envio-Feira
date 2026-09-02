<?php
declare(strict_types=1);

require_once __DIR__ . '/../services/Session.php';
Session::start();

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Professor.php';
require_once __DIR__ . '/../models/Chat.php';
require_once __DIR__ . '/../services/Auth.php';

$auth = new Auth(new Professor((new Database())->connect()));

if (!$auth->estaAutenticado()) {
    header('Location: chat/');
    exit;
}

$professor = $auth->professor();
$chat = new Chat((new Database())->connect());
$conversa = $chat->buscarConversaDoProfessor((int) $professor['id']);
$conversaId = $conversa ? (int) $conversa['id'] : null;
$csrfToken = Session::csrfToken();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Fale conosco | Sistema Escolar</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav class="navbar">
<a href="dashboard.php" class="navbar-brand">🎓 Sistema Escolar</a>
<div class="navbar-links">
<a href="dashboard.php" class="nav-button">🏠 Início</a>
<a href="chat.php" class="nav-button active">💬 Fale conosco</a>
<div class="user-badge">👤 <?= htmlspecialchars($professor['nome'], ENT_QUOTES, 'UTF-8') ?><span>PROFESSOR</span></div>
<a href="logout.php" class="nav-button nav-danger">🚪 Sair</a>
</div>
</nav>

<main class="chat-page">
<section class="chat-window" data-conversa-id="<?= $conversaId ?? '' ?>" data-csrf="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">

<header class="chat-header">
<div class="chat-avatar">ADM</div>
<div><h1>Fale conosco</h1><span>Converse diretamente com a administração</span></div>
</header>

<div id="chat-messages" class="chat-messages">
<div class="chat-empty"><div>💬</div><h2>Como podemos ajudar?</h2><p>Envie uma mensagem para a administração.</p></div>
</div>

<form id="chat-form" class="chat-form">
<input type="text" id="chat-input" maxlength="2000" placeholder="Digite sua mensagem..." autocomplete="off" required>
<button type="submit" class="chat-send">➤</button>
</form>
</section>
</main>

<script>
const win = document.querySelector('.chat-window');
const messages = document.querySelector('#chat-messages');
const form = document.querySelector('#chat-form');
const input = document.querySelector('#chat-input');
const csrfToken = win.dataset.csrf;
let conversaId = win.dataset.conversaId || null;

function escapeHtml(texto) {
    const div = document.createElement('div');
    div.textContent = texto;
    return div.innerHTML;
}

function formatarHora(data) {
    const d = new Date(String(data).replace(' ', 'T'));
    return Number.isNaN(d.getTime()) ? '' : d.toLocaleTimeString('pt-BR', {hour:'2-digit', minute:'2-digit'});
}

async function carregarMensagens() {
    if (!conversaId) return;

    try {
        const response = await fetch('api/chat-mensagens.php?conversa_id=' + encodeURIComponent(conversaId), {cache:'no-store'});
        if (!response.ok) return;
        const data = await response.json();
        if (!data.sucesso) return;

        messages.innerHTML = '';

        if (!data.mensagens.length) {
            messages.innerHTML = '<div class="chat-empty"><div>💬</div><h2>Como podemos ajudar?</h2><p>Envie uma mensagem para a administração.</p></div>';
            return;
        }

        data.mensagens.forEach(m => {
            const own = m.remetente_tipo === 'professor';
            const item = document.createElement('div');
            item.className = own ? 'message message-own' : 'message message-other';

            const status = own ? '<span class="message-status">' +
                (m.status === 'lida' ? '✓✓' : m.status === 'recebida' ? '✓✓' : '✓') +
                '</span>' : '';

            item.innerHTML = '<div class="message-bubble"><div class="message-text">' +
                escapeHtml(m.mensagem) +
                '</div><div class="message-meta">' +
                formatarHora(m.criado_em) + status +
                '</div></div>';

            messages.appendChild(item);
        });

        messages.scrollTop = messages.scrollHeight;
    } catch (e) {}
}

async function enviarMensagem(texto) {
    const fd = new FormData();
    fd.append('mensagem', texto);
    fd.append('csrf_token', csrfToken);
    if (conversaId) fd.append('conversa_id', conversaId);

    const response = await fetch('api/chat-enviar.php', {method:'POST', body:fd});
    const data = await response.json();

    if (!data.sucesso) {
        alert(data.erro || 'Não foi possível enviar a mensagem.');
        return;
    }

    conversaId = String(data.conversa_id);
    win.dataset.conversaId = conversaId;
    input.value = '';
    await carregarMensagens();
}

form.addEventListener('submit', async e => {
    e.preventDefault();
    const texto = input.value.trim();
    if (texto) await enviarMensagem(texto);
});

carregarMensagens();
setInterval(carregarMensagens, 3000);
</script>
</body>
</html>
