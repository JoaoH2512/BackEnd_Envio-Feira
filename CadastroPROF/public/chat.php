<?php

declare(strict_types=1);

require_once __DIR__ . '/../services/Session.php';

Session::start();

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Professor.php';
require_once __DIR__ . '/../models/Chat.php';
require_once __DIR__ . '/../services/Auth.php';

$database = new Database();

$db = $database->connect();

$professorModel = new Professor($db);

$auth = new Auth($professorModel);

if (
    !$auth->estaAutenticado()
) {

    header('Location: chat/');

    exit;
}

$professor = $auth->professor();

$chatModel = new Chat($db);

$conversa = $chatModel->buscarConversaDoProfessor(
    (int) $professor['id']
);

$conversaId = $conversa
    ? (int) $conversa['id']
    : null;

$csrfToken = Session::csrfToken();

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
        Suporte | Sistema Escolar
    </title>

    <link
        rel="stylesheet"
        href="css/style.css"
    >

</head>

<body>

<nav class="navbar">

    <a
        href="dashboard.php"
        class="navbar-brand"
    >
        🎓 Sistema Escolar
    </a>

    <div class="navbar-links">

        <a
            href="dashboard.php"
            class="nav-button"
        >
            🏠 Início
        </a>

        <a
            href="chat.php"
            class="nav-button active"
        >
            💬 Suporte
        </a>

        <div class="user-badge">

            👤

            <?= htmlspecialchars(
                $professor['nome'],
                ENT_QUOTES,
                'UTF-8'
            ) ?>

            <span>
                PROFESSOR
            </span>

        </div>

        <a
            href="logout.php"
            class="nav-button nav-danger"
        >
            🚪 Sair
        </a>

    </div>

</nav>

<main class="chat-page">

    <section
        class="chat-window"
        data-conversa-id="<?= $conversaId ?? '' ?>"
        data-csrf="<?= htmlspecialchars(
            $csrfToken,
            ENT_QUOTES,
            'UTF-8'
        ) ?>"
        data-tipo="professor"
    >

        <header class="chat-header">

            <div class="chat-avatar">
                ADM
            </div>

            <div>

                <h1>
                    Suporte administrativo
                </h1>

                <span>
                    Fale diretamente com a administração
                </span>

            </div>

        </header>

        <div
            id="chat-messages"
            class="chat-messages"
        >

            <div class="chat-empty">

                <div>
                    💬
                </div>

                <h2>
                    Como podemos ajudar?
                </h2>

                <p>
                    Envie uma mensagem para a administração.
                </p>

            </div>

        </div>

        <form
            id="chat-form"
            class="chat-form"
        >

            <input
                type="text"
                id="chat-input"
                name="mensagem"
                maxlength="2000"
                placeholder="Digite sua mensagem..."
                autocomplete="off"
                required
            >

            <button
                type="submit"
                class="chat-send"
            >
                ➤
            </button>

        </form>

    </section>

</main>

<script>

const chatWindow =
    document.querySelector('.chat-window');

const chatMessages =
    document.querySelector('#chat-messages');

const chatForm =
    document.querySelector('#chat-form');

const chatInput =
    document.querySelector('#chat-input');

let conversaId =
    chatWindow.dataset.conversaId || null;

const csrfToken =
    chatWindow.dataset.csrf;

async function carregarMensagens() {

    if (!conversaId) {
        return;
    }

    const response = await fetch(
        `api/chat-mensagens.php?conversa_id=${encodeURIComponent(conversaId)}`
    );

    if (!response.ok) {
        return;
    }

    const data = await response.json();

    if (!data.sucesso) {
        return;
    }

    renderizarMensagens(
        data.mensagens
    );
}

function renderizarMensagens(
    mensagens
) {

    chatMessages.innerHTML = '';

    if (!mensagens.length) {

        chatMessages.innerHTML = `
            <div class="chat-empty">
                <div>💬</div>
                <h2>Como podemos ajudar?</h2>
                <p>Envie uma mensagem para a administração.</p>
            </div>
        `;

        return;
    }

    mensagens.forEach(
        mensagem => {

            const propria =
                mensagem.remetente_tipo === 'professor';

            const item =
                document.createElement('div');

            item.className =
                propria
                    ? 'message message-own'
                    : 'message message-other';

            const status =
                propria
                    ? `
                        <span class="message-status">
                            ${mensagem.status === 'read'
                                ? '✓✓'
                                : mensagem.status === 'received'
                                    ? '✓✓'
                                    : '✓'}
                        </span>
                    `
                    : '';

            item.innerHTML = `
                <div class="message-bubble">
                    <div class="message-text">
                        ${escapeHtml(mensagem.mensagem)}
                    </div>

                    <div class="message-meta">
                        ${formatarHora(mensagem.criado_em)}
                        ${status}
                    </div>
                </div>
            `;

            chatMessages.appendChild(item);
        }
    );

    chatMessages.scrollTop =
        chatMessages.scrollHeight;
}

async function enviarMensagem(
    mensagem
) {

    const formData =
        new FormData();

    formData.append(
        'mensagem',
        mensagem
    );

    formData.append(
        'csrf_token',
        csrfToken
    );

    if (conversaId) {

        formData.append(
            'conversa_id',
            conversaId
        );
    }

    const response =
        await fetch(
            'api/chat-enviar.php',
            {
                method: 'POST',
                body: formData
            }
        );

    const data =
        await response.json();

    if (!data.sucesso) {

        alert(
            data.erro ||
            'Não foi possível enviar a mensagem.'
        );

        return;
    }

    conversaId =
        data.conversa_id;

    chatWindow.dataset.conversaId =
        conversaId;

    chatInput.value = '';

    await carregarMensagens();
}

chatForm.addEventListener(
    'submit',
    async event => {

        event.preventDefault();

        const mensagem =
            chatInput.value.trim();

        if (!mensagem) {
            return;
        }

        await enviarMensagem(
            mensagem
        );
    }
);

function escapeHtml(texto) {

    const div =
        document.createElement('div');

    div.textContent =
        texto;

    return div.innerHTML;
}

function formatarHora(data) {

    return new Date(
        data.replace(' ', 'T')
    ).toLocaleTimeString(
        'pt-BR',
        {
            hour: '2-digit',
            minute: '2-digit'
        }
    );
}

carregarMensagens();

setInterval(
    carregarMensagens,
    3000
);

</script>

</body>

</html>