<link rel="stylesheet" href="style-adicionar.css">

<button type="button" id="btnAbrirModal">
    Cadastrar professor
</button>

<div id="modalProfessor" class="modal">
    <div class="modal-conteudo">

        <button type="button" id="btnFecharModal">X</button>

        <h2>Cadastrar professor</h2>

        <form action="listar.php" method="POST">

            <label for="prof_nome">Nome do professor:</label>
            <input
                type="text"
                id="prof_nome"
                name="prof_nome"
                placeholder="Digite..."
                required
            >

            <label for="prof_rm">RM do professor:</label>
            <input
                type="number"
                id="prof_rm"
                name="prof_rm"
                placeholder="Digite..."
                required
            >

            <label for="prof_email">E-mail do professor:</label>
            <input
                type="email"
                id="prof_email"
                name="prof_email"
                placeholder="Digite..."
                required
            >

            <button type="submit">
                Cadastrar
            </button>

        </form>

    </div>
</div>

<script>
    const btnAbrir = document.getElementById("btnAbrirModal");
    const btnFechar = document.getElementById("btnFecharModal");
    const modal = document.getElementById("modalProfessor");

    btnAbrir.addEventListener("click", () => {
        modal.classList.add("ativo");
    });

    btnFechar.addEventListener("click", () => {
        modal.classList.remove("ativo");
    });
</script>