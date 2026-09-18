<?php
declare(strict_types=1);

require_once __DIR__ . '/lib/services/Session.php';
Session::start();

if (empty($_SESSION['admin_autenticado'])) {
    header('Location: admin.php');
    exit;
}

require_once __DIR__ . '/lib/config/Database.php';
require_once __DIR__ . '/lib/models/Chat.php';

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
<link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="barra-navegacao">
<a href="admin.php" class="marca-navegacao">🎓 Sistema Escolar</a>
<div class="links-navegacao">
<a href="admin.php" class="botao-navegacao">👨‍🏫 Professores</a>
<a href="admin-chat.php" class="botao-navegacao ativo">💬 Chat</a>
<a href="logout.php" class="botao-navegacao navegacao-perigo">🚪 Sair</a>
</div>
</nav>

<main class="pagina-chat-administrador">
<section class="chat-administrador">

<aside class="barra-lateral-conversas">
<header class="cabecalho-conversa">
<span class="rotulo-destaque">SUPORTE</span>
<h1>Conversas</h1>
</header>
<div id="conversation-list" class="lista-conversas">
<?php if (!$conversas): ?>
<div class="conversa-vazia">Nenhuma conversa iniciada.</div>
<?php else: ?>
<?php foreach ($conversas as $c): ?>
<button type="button" class="item-conversa" data-conversa-id="<?= (int)$c['id'] ?>">
<div class="avatar-conversa"><?= htmlspecialchars(strtoupper(substr($c['nome'],0,1)), ENT_QUOTES, 'UTF-8') ?></div>
<div class="informacoes-conversa">
<strong><?= htmlspecialchars($c['nome'], ENT_QUOTES, 'UTF-8') ?></strong>
<span><?= htmlspecialchars($c['ultima_mensagem'] ?? 'Nenhuma mensagem', ENT_QUOTES, 'UTF-8') ?></span>
</div>
<?php if ((int)$c['nao_lidas'] > 0): ?><span class="distintivo-nao-lido"><?= (int)$c['nao_lidas'] ?></span><?php endif; ?>
</button>
<?php endforeach; ?>
<?php endif; ?>
</div>
</aside>

<section id="admin-chat-window" class="janela-chat-administrador" data-csrf="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
<div class="espaco-chat-administrador"><div>💬</div><h2>Selecione uma conversa</h2><p>Escolha um professor para visualizar as mensagens.</p></div>
</section>
</section>
</main>

<script>
const csrfToken = document.querySelector('#admin-chat-window').dataset.csrf;
const chatWindow = document.querySelector('#admin-chat-window');
const items = document.querySelectorAll('.item-conversa');
let conversaAtual = null;

items.forEach(item => item.addEventListener('click', () => {
    conversaAtual = item.dataset.conversaId;
    abrirConversa(conversaAtual, item);
}));

async function abrirConversa(id, item) {
    items.forEach(i => i.classList.remove('selecionado'));
    item.classList.add('selecionado');

    chatWindow.innerHTML = `
    <header class="cabecalho-chat">
        <div class="avatar-chat">👤</div>
        <div><h1>${escapeHtml(item.querySelector('strong').textContent)}</h1><span>Conversa com o professor</span></div>
    </header>
    <div id="admin-messages" class="mensagens-chat"></div>
    <form id="admin-chat-form" class="formulario-chat">
        <input type="text" id="admin-chat-input" maxlength="2000" placeholder="Digite sua resposta..." autocomplete="off" required>
        <button type="submit" class="enviar-chat">➤</button>
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
            item.className = own ? 'mensagem mensagem-propria' : 'mensagem mensagem-outra';
            item.innerHTML = '<div class="balao-mensagem"><div class="texto-mensagem">' +
                escapeHtml(m.mensagem) + '</div><div class="meta-mensagem">' +
                formatarHora(m.criado_em) +
                (own ? '<span class="status-mensagem">' + (m.status === 'lida' ? '✓✓' : m.status === 'recebida' ? '✓✓' : '✓') + '</span>' : '') +
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
