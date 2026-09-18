# PainelADM — Sistema unificado (cadastro + chat de professores)

Este diretório concentra **todo** o sistema de cadastro de professores, login do
professor e chat entre professor e administração. A pasta `CadastroPROF` não é
mais necessária para o funcionamento do sistema (veja `MIGRATION_REPORT.md` na
raiz do projeto para os detalhes e evidências da migração).

## Estrutura

```
PainelADM/
├── index.php              # redireciona para admin.php
├── admin.php              # login administrativo + CRUD de professores
├── admin-chat.php         # chat administrativo (lista e responde conversas)
├── logout.php             # logout do administrador
├── professor/
│   ├── login.php           # login do professor (RA/matrícula + senha)
│   ├── dashboard.php        # área do professor
│   ├── chat.php             # chat do professor com a administração
│   └── logout.php           # logout do professor
├── api/
│   ├── chat-enviar.php
│   ├── chat-ler.php
│   ├── chat-listar.php
│   └── chat-mensagens.php
├── lib/
│   ├── config/
│   │   ├── Database.php
│   │   └── AdminConfig.php
│   ├── models/
│   │   ├── Professor.php
│   │   └── Chat.php
│   └── services/
│       ├── Auth.php
│       └── Session.php
└── style.css
```

## Acesso

- Painel administrativo: `/PainelADM/` (ou `/PainelADM/index.php`)
- Código administrativo padrão: `ADM-2026-SEGURANCA` (altere em
  `lib/config/AdminConfig.php` antes de ir para produção)
- Login do professor: `/PainelADM/professor/login.php`

## Banco de dados

Execute o arquivo `FeiraBD.sql` (raiz do projeto) no MySQL/MariaDB. O sistema
usa exclusivamente o banco `feirabd` e as tabelas:

- `professor` (colunas: `id`, `nome`, `email`, `senha`, `matricula`, `tipo`,
  `criado_em`)
- `conversas` (`id`, `professor_id`, `criado_em`)
- `mensagens` (`id`, `conversa_id`, `remetente_id`, `remetente_tipo`,
  `mensagem`, `status`, `criado_em`)

A interface chama o campo de "RA", mas internamente todas as consultas usam
`matricula`; o alias `AS ra` é aplicado somente nas consultas de leitura para
a interface.

Ajuste usuário/senha de conexão em `PainelADM/lib/config/Database.php`.

## Fluxo

1. O administrador acessa `PainelADM/`, entra com o código secreto e pode
   cadastrar, editar e excluir professores.
2. O professor acessa `PainelADM/professor/login.php`, entra com RA/matrícula
   e senha (hash `password_hash`/`password_verify`), acessa seu dashboard e o
   chat com a administração.
3. Uma conversa é criada automaticamente no primeiro envio de mensagem do
   professor.
4. O administrador acessa `admin-chat.php` para ver todas as conversas,
   quantidade de mensagens não lidas e responder.
5. Sessão, CSRF e verificação de posse de conversa são validados em todas as
   operações de escrita e leitura sensíveis.

## Segurança implementada

- Sessão única (`sistema_professor_session`), `HttpOnly`, `SameSite=Lax`,
  regenerada a cada login (admin ou professor).
- Token CSRF validado em todas as ações POST (cadastro, edição, exclusão,
  login do professor, envio/leitura de mensagens).
- Senhas com `password_hash` (bcrypt) — nunca expostas em respostas
  HTML/JSON.
- Consultas 100% via PDO com *prepared statements*.
- Saída sempre escapada com `htmlspecialchars`.
- Professor só acessa a própria conversa (`conversaPertenceAoProfessor`).
- `admin_autenticado` e sessão `professor` são chaves de sessão
  independentes — um professor não consegue forjar acesso administrativo, e
  o painel admin permanece bloqueado por trás do código secreto.
