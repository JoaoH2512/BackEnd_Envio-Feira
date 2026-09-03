# Correções do chat do `CadastroPROF`

## Principais correções

1. **Compatibilidade de datas:** o modelo detecta automaticamente `criado_em` ou `criada_em` nas tabelas `conversas` e `mensagens`, eliminando o erro `Unknown column c.criado_em` em bancos antigos.
2. **Compatibilidade de identificador:** `matricula` e `ra` são detectados automaticamente e expostos como `ra` para a interface.
3. **Conversa única resistente a concorrência:** `Chat.php` reaproveita a conversa existente quando duas requisições simultâneas disputam a chave única.
4. **Consultas consistentes:** datas retornam com o alias esperado pelo JavaScript e há índice adequado para ordenar mensagens.
5. **Validação do remetente:** o modelo rejeita tipos de remetente diferentes de `professor` e `admin`.
6. **Script legado do módulo:** `CadastroPROF/database/database.sql` também foi alinhado nos nomes de timestamp.

## Instalação

O erro foi informado em `C:\xampp\htdocs\BackEnd\CadastroPROF\models\Chat.php`. Extraia o ZIP substituindo a pasta exatamente em `C:\xampp\htdocs\BackEnd`; não substitua apenas uma pasta com nome diferente. Reinicie o Apache após a cópia.

Não é necessário apagar o banco existente: o código identifica automaticamente os nomes antigos das colunas. Para uma instalação nova, execute `FeiraBD.sql` no MySQL/MariaDB e configure as credenciais em `CadastroPROF/config/Database.php`.

O fluxo esperado é:

- professor acessa `public/chat/`, entra com a matrícula/RA e senha;
- a conversa é criada somente no primeiro envio;
- administração entra por `public/admin.php` e acessa `public/admin-chat.php`;
- mensagens são protegidas por sessão, CSRF e verificação de pertencimento da conversa.

## Validação realizada

Foi feita inspeção estática dos endpoints, referências de coluna e do esquema SQL. O pacote contém o projeto completo e foi verificado com teste de integridade do ZIP.
