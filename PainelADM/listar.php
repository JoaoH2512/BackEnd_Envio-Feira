<link rel="stylesheet" href="style-listar.css">

<?php

    $sql = "SELECT nome, RM, EMAIL 
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

                <td>
                    <?= htmlspecialchars($professor['nome']) ?>
                </td>

                <td>
                    <?= htmlspecialchars($professor['RM']) ?>
                </td>

                <td>
                    <?= htmlspecialchars($professor['EMAIL']) ?>
                </td>

                <td>
                    <button type="button">
                        Editar
                    </button>
                </td>

            </tr>

        <?php endforeach; ?>

    </tbody>

</table>