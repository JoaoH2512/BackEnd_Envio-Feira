<?php
declare(strict_types=1);

class Professor
{
    public function __construct(private PDO $db) {}

    public function emailExiste(string $email, ?int $ignorarId = null): bool
    {
        $sql = 'SELECT 1 FROM professor WHERE email = ?' . ($ignorarId ? ' AND id <> ?' : '') . ' LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($ignorarId ? [$email, $ignorarId] : [$email]);
        return (bool) $stmt->fetchColumn();
    }

    public function raExiste(string $ra, ?int $ignorarId = null): bool
    {
        $sql = 'SELECT 1 FROM professor WHERE matricula = ?' . ($ignorarId ? ' AND id <> ?' : '') . ' LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($ignorarId ? [$ra, $ignorarId] : [$ra]);
        return (bool) $stmt->fetchColumn();
    }

    public function criar(string $nome, string $ra, string $email, string $senha, string $tipo): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO professor (nome, email, senha, matricula, tipo) VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->execute([$nome, $email, password_hash($senha, PASSWORD_DEFAULT), $ra, $tipo]);
        return (int) $this->db->lastInsertId();
    }

    public function atualizar(int $id, string $nome, string $ra, string $email, string $tipo, ?string $senha = null): void
    {
        if ($senha !== null && $senha !== '') {
            $stmt = $this->db->prepare(
                'UPDATE professor SET nome=?, matricula=?, email=?, tipo=?, senha=? WHERE id=?'
            );
            $stmt->execute([$nome, $ra, $email, $tipo, password_hash($senha, PASSWORD_DEFAULT), $id]);
            return;
        }

        $stmt = $this->db->prepare('UPDATE professor SET nome=?, matricula=?, email=?, tipo=? WHERE id=?');
        $stmt->execute([$nome, $ra, $email, $tipo, $id]);
    }

    public function buscarTodos(): array
    {
        return $this->db->query(
            'SELECT id, nome, matricula AS ra, email, tipo, criado_em FROM professor ORDER BY nome ASC'
        )->fetchAll();
    }

    /**
     * Retorna o professor com a senha (uso interno de autenticação apenas).
     */
    public function buscarPorRA(string $ra): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT id, nome, matricula AS ra, email, senha, tipo FROM professor WHERE matricula = ? LIMIT 1'
        );
        $stmt->execute([$ra]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /**
     * Retorna o professor com a senha (uso interno de autenticação apenas).
     */
    public function buscarPorEmail(string $email): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT id, nome, matricula AS ra, email, senha, tipo FROM professor WHERE email = ? LIMIT 1'
        );
        $stmt->execute([$email]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function buscarPorId(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT id, nome, matricula AS ra, email, tipo, criado_em FROM professor WHERE id = ? LIMIT 1'
        );
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function excluir(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM professor WHERE id = ?');
        $stmt->execute([$id]);
    }
}
