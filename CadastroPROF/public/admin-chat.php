<?php
declare(strict_types=1);

require_once __DIR__ . '/../services/Session.php';
Session::start();

if (empty($_SESSION['admin_autenticado'])) {
    header('Location: admin.php');
    exit;
}

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Chat.php';

$chat = new Chat((new Database())->connect());
$conversas = $chat->buscarTodasConversas();
$csrfToken = Session::csrfToken();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Chat | Administração</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav class="navbar">
<a href="admin.php" class="navbar-brand">🎓 Sistema Escolar</a>
<div class="navbar-links">
<a href="admin.php" class="nav-button">👨‍🏫 Professores</a>
<a href="admin-chat.php" class="nav-button active">💬 Chat</a>
<a href="admin-logout.php" class="nav-button nav-danger">🚪 Sair</a>
</div>
</nav>

<main class="admin-chat-page">
<section class="admin-chat">

<aside class="conversation-sidebar">
<header class="conversation-header">
<span class="eyebrow">SUPORTE</span>
<h1>Conversas</h1>
</header>
<div id="conversation-list" class="conversation-list">
<?php if (!$conversas): ?>
<div class="conversation-empty">Nenhuma conversa iniciada.</div>
<?php else: ?>
<?php foreach ($conversas as $c): ?>
<button type="button" class="conversation-item" data-conversa-id="<?= (int)$c['id'] ?>">
<div class="conversation-avatar"><?= htmlspecialchars(strtoupper(substr($c['nome'],0,1)), ENT_QUOTES, 'UTF-8') ?></div>
<div class="conversation-info">
<strong><?= htmlspecialchars($c['nome'], ENT_QUOTES, 'UTF-8') ?></strong>
<span><?= htmlspecialchars($c['ultima_mensagem'] ?? 'Nenhuma mensagem', ENT_QUOTES, 'UTF-8') ?></span>
</div>
<?php if ((int)$c['nao_lidas'] > 0): ?><span class="unread-badge"><?= (int)$c['nao_lidas'] ?></span><?php endif; ?>
</button>
<?php endforeach; ?>
<?php endif; ?>
</div>
</aside>

<section id="admin-chat-window" class="admin-chat-window" data-csrf="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
<div class="admin-chat-placeholder"><div>💬</div><h2>Selecione uma conversa</h2><p>Escolha um professor para visualizar as mensagens.</p></div>
</section>
</section>
</main>

<script>
const csrfToken = document.querySelector('#admin-chat-window').dataset.csrf;
const chatWindow = document.querySelector('#admin-chat-window');
const items = document.querySelectorAll('.conversation-item');
let conversaAtual = null;

items.forEach(item => item.addEventListener('click', () => {
    conversaAtual = item.dataset.conversaId;
    abrirConversa(conversaAtual, item);
}));

async function abrirConversa(id, item) {
    items.forEach(i => i.classList.remove('selected'));
    item.classList.add('selected');

    chatWindow.innerHTML = `
    <header class="chat-header">
        <div class="chat-avatar">👤</div>
        <div><h1>${escapeHtml(item.querySelector('strong').textContent)}</h1><span>Conversa com o professor</span></div>
    </header>
    <div id="admin-messages" class="chat-messages"></div>
    <form id="admin-chat-form" class="chat-form">
        <input type="text" id="admin-chat-input" maxlength="2000" placeholder="Digite sua resposta..." autocomplete="off" required>
        <button type="submit" class="chat-send">➤</button>
    </form>`;

    await carregarMensagensAdmin(id);
    await marcarLidas(id);

    document.querySelector('#admin-chat-form').addEventListener('submit', async e => {
        e.preventDefault();
        const input = document.querySelector('#admin-chat-input');
        const texto = input.value.trim();
        if (!texto) return;
        await enviarMensagemAdmin(id, texto);
        input.value = '';
        await carregarMensagensAdmin(id);
    });
}

async function carregarMensagensAdmin(id) {
    try {
        const r = await fetch('api/chat-mensagens.php?conversa_id=' + encodeURIComponent(id), {cache:'no-store'});
        if (!r.ok) return;
        const data = await r.json();
        if (!data.sucesso) return;

        const box = document.querySelector('#admin-messages');
        if (!box) return;
        box.innerHTML = '';

        data.mensagens.forEach(m => {
            const own = m.remetente_tipo === 'admin';
            const item = document.createElement('div');
            item.className = own ? 'message message-own' : 'message message-other';
            item.innerHTML = '<div class="message-bubble"><div class="message-text">' +
                escapeHtml(m.mensagem) + '</div><div class="message-meta">' +
                formatarHora(m.criado_em) +
                (own ? '<span class="message-status">' + (m.status === 'lida' ? '✓✓' : m.status === 'recebida' ? '✓✓' : '✓') + '</span>' : '') +
                '</div></div>';
            box.appendChild(item);
        });

        box.scrollTop = box.scrollHeight;
    } catch (e) {}
}

async function enviarMensagemAdmin(id, texto) {
    const fd = new FormData();
    fd.append('conversa_id', id);
    fd.append('mensagem', texto);
    fd.append('csrf_token', csrfToken);

    const r = await fetch('api/chat-enviar.php', {method:'POST', body:fd});
    const data = await r.json();
    if (!data.sucesso) alert(data.erro || 'Erro ao enviar mensagem.');
}

async function marcarLidas(id) {
    const fd = new FormData();
    fd.append('conversa_id', id);
    fd.append('csrf_token', csrfToken);
    await fetch('api/chat-ler.php', {method:'POST', body:fd});
}

function escapeHtml(texto) {
    const div = document.createElement('div');
    div.textContent = texto;
    return div.innerHTML;
}

function formatarHora(data) {
    const d = new Date(String(data).replace(' ', 'T'));
    return Number.isNaN(d.getTime()) ? '' : d.toLocaleTimeString('pt-BR', {hour:'2-digit', minute:'2-digit'});
}
</script>
</body>
</html>
