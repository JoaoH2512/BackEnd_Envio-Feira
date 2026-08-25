<?php

declare(strict_types=1);

class Professor
{
    private PDO $db;


    public function __construct(PDO $db)
    {
        $this->db = $db;
    }


    public function buscarPorEmail(
        string $email
    ): ?array {

        $sql = "
            SELECT
                id,
                nome,
                ra,
                email,
                senha,
                ativo
            FROM professores
            WHERE email = :email
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':email' => $email
        ]);

        $professor = $stmt->fetch();

        return $professor ?: null;
    }


    public function buscarPorId(
        int $id
    ): ?array {

        $sql = "
            SELECT
                id,
                nome,
                ra,
                email,
                ativo,
                criado_em
            FROM professores
            WHERE id = :id
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':id' => $id
        ]);

        $professor = $stmt->fetch();

        return $professor ?: null;
    }


    public function buscarTodos(): array
    {
        $sql = "
            SELECT
                id,
                nome,
                ra,
                email,
                ativo,
                criado_em
            FROM professores
            ORDER BY id DESC
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll();
    }


    public function criar(
        string $nome,
        string $ra,
        string $email,
        string $senha
    ): bool {

        $senhaHash =
            password_hash(
                $senha,
                PASSWORD_DEFAULT
            );

        $sql = "
            INSERT INTO professores
            (
                nome,
                ra,
                email,
                senha
            )
            VALUES
            (
                :nome,
                :ra,
                :email,
                :senha
            )
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':nome' => $nome,
            ':ra' => $ra,
            ':email' => $email,
            ':senha' => $senhaHash
        ]);
    }


    public function emailExiste(
        string $email
    ): bool {

        $sql = "
            SELECT id
            FROM professores
            WHERE email = :email
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':email' => $email
        ]);

        return $stmt->fetch() !== false;
    }


    public function raExiste(
        string $ra
    ): bool {

        $sql = "
            SELECT id
            FROM professores
            WHERE ra = :ra
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':ra' => $ra
        ]);

        return $stmt->fetch() !== false;
    }


    public function excluir(
        int $id
    ): bool {

        $sql = "
            DELETE FROM professores
            WHERE id = :id
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $id
        ]);
    }
}