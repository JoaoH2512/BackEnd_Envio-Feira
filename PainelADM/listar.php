<link rel="stylesheet" href="style-listar.css">
<link rel="stylesheet" href="style-adicionar.css">

<?php

    require_once '../conexao.php';

    $sql = "SELECT id, nome, rm, email 
            FROM professores 
            ORDER BY nome ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $professores = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<h2>Professores cadastrados</h2>

<table>

    <thead>
        <tr>
            <th>Nome</th>
            <th>RM</th>
            <th>E-mail</th>
            <th>Ações</th>
        </tr>
    </thead>

    <tbody>

        <?php foreach ($professores as $professor): ?>

            <tr>

                <td><?= htmlspecialchars($professor['nome']) ?></td>
                <td><?= htmlspecialchars($professor['rm']) ?></td>
                <td><?= htmlspecialchars($professor['email']) ?></td>

                <td>
                    <button type="button" class="btnEditar" data-id="<?= $professor['id'] ?>">
                        Editar
                    </button>

                    <button type="button" class="btnApagar" data-id="<?= $professor['id'] ?>">
                        Apagar
                    </button>
                </td>

            </tr>

        <?php endforeach; ?>

    </tbody>

</table>

<!-- ---------------------------MODAL EDITAR PROFESSOR -->
<div id="modalProfessor" class="modal">
    <div class="modal-conteudo">

        <button type="button" id="btnFecharModal">X</button>

        <h2>Editar professor</h2>

        <form id="formEditar">

            <input type="hidden" id="edit_id" name="id">

            <label for="edit_nome">Nome do professor:</label>
            <input type="text" id="edit_nome" name="nome" required>

            <label for="edit_rm">RM do professor:</label>
            <input type="number" id="edit_rm" name="rm" required>

            <label for="edit_email">E-mail do professor:</label>
            <input type="email" id="edit_email" name="email" required>

            <button type="submit">
                Salvar alterações
            </button>

        </form>

    </div>
</div>

<script>
    // PREPARA O MODAL EDITAR
    const modal = document.getElementById("modalProfessor");
    const btnFechar = document.getElementById("btnFecharModal");
    const formEditar = document.getElementById("formEditar");

    // Abrir modal e carregar dados do professor
    document.querySelectorAll(".btnEditar").forEach(botao => {
        botao.addEventListener("click", () => {
            const id = botao.dataset.id;

            // BUSCAR PROFESSOR NO BUSCAR_PROFESSOR.PHP E ARMAZENA A RESPOSTA EM UMA VARIAVEL PROFESSOR
            fetch(`buscar_professor.php?id=${id}`)
                .then(res => res.json())
                .then(professor => {
                    document.getElementById("edit_id").value = professor.id;
                    document.getElementById("edit_nome").value = professor.nome;
                    document.getElementById("edit_rm").value = professor.rm;
                    document.getElementById("edit_email").value = professor.email;

                    modal.classList.add("ativo");
                })
                .catch(() => alert("Erro ao carregar dados do professor."));
        });
    });

    // Fechar modal
    btnFechar.addEventListener("click", () => {
        modal.classList.remove("ativo");
    });

    // Enviar formulário de edição via AJAX
    formEditar.addEventListener("submit", (e) => {
        e.preventDefault();

        const dados = new FormData(formEditar);

        fetch("processa_editar.php", {
            method: "POST",
            body: dados
        })
        .then(res => res.json())
        .then(resposta => {
            if (resposta.sucesso) {
                alert("Professor atualizado com sucesso!");
                location.reload();
            } else {
                alert("Erro ao atualizar professor.");
            }
        })
        .catch(() => alert("Erro na comunicação com o servidor."));
    });




// -----------------------------APAGAR PROFESSOR
    document.querySelectorAll(".btnApagar").forEach(botao => {
    botao.addEventListener("click", () => {
        const id = botao.dataset.id;

        const confirmar = confirm("Tem certeza que deseja excluir esse professor?");
        if (!confirmar) return;

        // FAZ A REQUISIÇÃO PARA O PROCESSA_APAGAR.PHP APAGAR O PROFESSOR, ARMAZENA A RESPOSTA E EXIBE UMA MENSAGEM
        fetch(`processa_apagar.php?id=${id}`)
            .then(res => res.json())
            .then(resposta => {
                if (resposta.sucesso) {
                    alert("Professor removido com sucesso!");
                    location.reload();
                } else {
                    alert(resposta.erro || "Erro ao remover professor");
                }
            })
            .catch(() => alert("Erro na comunicação com o servidor."));
    });
});
</script>