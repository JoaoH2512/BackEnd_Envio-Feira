<?php

declare(strict_types=1);

require_once __DIR__ . '/../services/Session.php';

Session::start();

if (
    empty($_SESSION['admin_autenticado'])
) {

    header('Location: admin.php');

    exit;
}

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Chat.php';

$database = new Database();

$db = $database->connect();

$chatModel = new Chat($db);

$conversas =
    $chatModel->buscarTodasConversas();

$csrfToken =
    Session::csrfToken();

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
        Chat | Administração
    </title>

    <link
        rel="stylesheet"
        href="css/style.css"
    >

</head>

<body>

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
            class="nav-button"
        >
            👨‍🏫 Professores
        </a>

        <a
            href="admin-chat.php"
            class="nav-button active"
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

<main class="admin-chat-page">

    <section class="admin-chat">

        <aside class="conversation-sidebar">

            <header class="conversation-header">

                <div>

                    <span class="eyebrow">
                        SUPORTE
                    </span>

                    <h1>
                        Conversas
                    </h1>

                </div>

            </header>

            <div
                id="conversation-list"
                class="conversation-list"
            >

                <?php if (
                    empty($conversas)
                ): ?>

                    <div class="conversation-empty">
                        Nenhuma conversa iniciada.
                    </div>

                <?php else: ?>

                    <?php foreach (
                        $conversas as $conversa
                    ): ?>

                        <button
                            type="button"
                            class="conversation-item"
                            data-conversa-id="<?= (int) $conversa['id'] ?>"
                        >

                            <div class="conversation-avatar">

                                <?= htmlspecialchars(
                                    strtoupper(
                                        substr(
                                            $conversa['nome'],
                                            0,
                                            1
                                        )
                                    ),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>

                            </div>

                            <div class="conversation-info">

                                <strong>

                                    <?= htmlspecialchars(
                                        $conversa['nome'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                </strong>

                                <span>

                                    <?= htmlspecialchars(
                                        $conversa['ultima_mensagem']
                                            ?? 'Nenhuma mensagem',
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                </span>

                            </div>

                            <?php if (
                                (int) $conversa['nao_lidas'] > 0
                            ): ?>

                                <span class="unread-badge">

                                    <?= (int) $conversa['nao_lidas'] ?>

                                </span>

                            <?php endif; ?>

                        </button>

                    <?php endforeach; ?>

                <?php endif; ?>

            </div>

        </aside>

        <section
            class="admin-chat-window"
            id="admin-chat-window"
            data-csrf="<?= htmlspecialchars(
                $csrfToken,
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
        >

            <div class="admin-chat-placeholder">

                <div>
                    💬
                </div>

                <h2>
                    Selecione uma conversa
                </h2>

                <p>
                    Escolha um professor para visualizar as mensagens.
                </p>

            </div>

        </section>

    </section>

</main>

<script>

const csrfToken =
    document.querySelector(
        '#admin-chat-window'
    ).dataset.csrf;

const conversationItems =
    document.querySelectorAll(
        '.conversation-item'
    );

const chatWindow =
    document.querySelector(
        '#admin-chat-window'
    );

let conversaAtual =
    null;

conversationItems.forEach(
    item => {

        item.addEventListener(
            'click',
            () => {

                conversaAtual =
                    item.dataset.conversaId;

                abrirConversa(
                    conversaAtual,
                    item
                );
            }
        );
    }
);

async function abrirConversa(
    conversaId,
    item
) {

    conversationItems.forEach(
        elemento => {
            elemento.classList.remove(
                'selected'
            );
        }
    );

    item.classList.add(
        'selected'
    );

    chatWindow.innerHTML = `
        <header class="chat-header">
            <div class="chat-avatar">👤</div>
            <div>
                <h1>Conversa</h1>
                <span>Carregando mensagens...</span>
            </div>
        </header>

        <div
            id="admin-messages"
            class="chat-messages"
        ></div>

        <form
            id="admin-chat-form"
            class="chat-form"
        >
            <input
                type="text"
                id="admin-chat-input"
                maxlength="2000"
                placeholder="Digite sua resposta..."
                required
            >

            <button
                type="submit"
                class="chat-send"
            >
                ➤
            </button>
        </form>
    `;

    await carregarMensagensAdmin(
        conversaId
    );

    await marcarLidas(
        conversaId
    );

    const form =
        document.querySelector(
            '#admin-chat-form'
        );

    form.addEventListener(
        'submit',
        async event => {

            event.preventDefault();

            const input =
                document.querySelector(
                    '#admin-chat-input'
                );

            const mensagem =
                input.value.trim();

            if (!mensagem) {
                return;
            }

            await enviarMensagemAdmin(
                conversaId,
                mensagem
            );

            input.value = '';

            await carregarMensagensAdmin(
                conversaId
            );
        }
    );
}

async function carregarMensagensAdmin(
    conversaId
) {

    const response =
        await fetch(
            `api/chat-mensagens.php?conversa_id=${encodeURIComponent(conversaId)}`
        );

    const data =
        await response.json();

    if (!data.sucesso) {
        return;
    }

    const container =
        document.querySelector(
            '#admin-messages'
        );

    if (!container) {
        return;
    }

    container.innerHTML = '';

    data.mensagens.forEach(
        mensagem => {

            const propria =
                mensagem.remetente_tipo === 'admin';

            const item =
                document.createElement('div');

            item.className =
                propria
                    ? 'message message-own'
                    : 'message message-other';

            item.innerHTML = `
                <div class="message-bubble">

                    <div class="message-text">
                        ${escapeHtml(mensagem.mensagem)}
                    </div>

                    <div class="message-meta">

                        ${formatarHora(
                            mensagem.criado_em
                        )}

                        ${
                            propria
                                ? `
                                    <span class="message-status">
                                        ${
                                            mensagem.status === 'read'
                                                ? '✓✓'
                                                : mensagem.status === 'received'
                                                    ? '✓✓'
                                                    : '✓'
                                        }
                                    </span>
                                `
                                : ''
                        }

                    </div>

                </div>
            `;

            container.appendChild(
                item
            );
        }
    );

    container.scrollTop =
        container.scrollHeight;
}

async function enviarMensagemAdmin(
    conversaId,
    mensagem
) {

    const formData =
        new FormData();

    formData.append(
        'conversa_id',
        conversaId
    );

    formData.append(
        'mensagem',
        mensagem
    );

    formData.append(
        'csrf_token',
        csrfToken
    );

    await fetch(
        'api/chat-enviar.php',
        {
            method: 'POST',
            body: formData
        }
    );
}

async function marcarLidas(
    conversaId
) {

    const formData =
        new FormData();

    formData.append(
        'conversa_id',
        conversaId
    );

    formData.append(
        'csrf_token',
        csrfToken
    );

    await fetch(
        'api/chat-ler.php',
        {
            method: 'POST',
            body: formData
        }
    );
}

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

</script>

</body>

</html>