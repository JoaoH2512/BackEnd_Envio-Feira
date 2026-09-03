<?php
declare(strict_types=1);

class Chat
{
    private string $conversaDataColumn;
    private string $mensagemDataColumn;
    private string $professorIdentifierColumn;

    public function __construct(private PDO $db)
    {
        $this->conversaDataColumn = $this->detectarColunaData('conversas');
        $this->mensagemDataColumn = $this->detectarColunaData('mensagens');
        $this->professorIdentifierColumn = $this->detectarColunaProfessor();
    }

    private function detectarColunaData(string $tabela): string
    {
        $stmt = $this->db->query("SHOW COLUMNS FROM {$tabela}");
        $colunas = $stmt->fetchAll(PDO::FETCH_COLUMN);
        return in_array('criado_em', $colunas, true) ? 'criado_em' : 'criada_em';
    }

    private function detectarColunaProfessor(): string
    {
        $stmt = $this->db->query('SHOW COLUMNS FROM professor');
        $colunas = $stmt->fetchAll(PDO::FETCH_COLUMN);
        return in_array('matricula', $colunas, true) ? 'matricula' : 'ra';
    }

    public function criarOuBuscarConversa(int $professorId): int
    {
        $stmt = $this->db->prepare(
            'SELECT id FROM conversas WHERE professor_id = ? LIMIT 1'
        );
        $stmt->execute([$professorId]);
        $id = $stmt->fetchColumn();

        if ($id !== false) {
            return (int) $id;
        }

        try {
            $stmt = $this->db->prepare('INSERT INTO conversas (professor_id) VALUES (?)');
            $stmt->execute([$professorId]);
            return (int) $this->db->lastInsertId();
        } catch (PDOException $e) {
            if ((int) ($e->errorInfo[1] ?? 0) !== 1062) throw $e;
            $stmt = $this->db->prepare('SELECT id FROM conversas WHERE professor_id = ? LIMIT 1');
            $stmt->execute([$professorId]);
            $id = $stmt->fetchColumn();
            if ($id === false) throw $e;
            return (int) $id;
        }
    }

    public function buscarConversaDoProfessor(int $professorId): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT c.id, c.professor_id, p.nome, p.{$this->professorIdentifierColumn} AS ra, p.email
             FROM conversas c
             INNER JOIN professor p ON p.id = c.professor_id
             WHERE c.professor_id = ?
             LIMIT 1"
        );
        $stmt->execute([$professorId]);

        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function conversaPertenceAoProfessor(int $conversaId, int $professorId): bool
    {
        $stmt = $this->db->prepare(
            'SELECT 1 FROM conversas WHERE id = ? AND professor_id = ? LIMIT 1'
        );
        $stmt->execute([$conversaId, $professorId]);

        return (bool) $stmt->fetchColumn();
    }

    public function conversaExiste(int $conversaId): bool
    {
        $stmt = $this->db->prepare(
            'SELECT 1 FROM conversas WHERE id = ? LIMIT 1'
        );
        $stmt->execute([$conversaId]);

        return (bool) $stmt->fetchColumn();
    }

    public function buscarTodasConversas(): array
    {
        $sql = "
            SELECT
                c.id,
                c.professor_id,
                p.nome,
                p.{$this->professorIdentifierColumn} AS ra,
                p.email,
                c.{$this->conversaDataColumn} AS criado_em,
                (
                    SELECT m.mensagem
                    FROM mensagens m
                    WHERE m.conversa_id = c.id
                    ORDER BY m.id DESC
                    LIMIT 1
                ) AS ultima_mensagem,
                (
                    SELECT COUNT(*)
                    FROM mensagens m2
                    WHERE m2.conversa_id = c.id
                      AND m2.remetente_tipo = 'professor'
                      AND m2.status <> 'lida'
                ) AS nao_lidas
            FROM conversas c
            INNER JOIN professor p ON p.id = c.professor_id
            ORDER BY
                (
                    SELECT MAX(m3.{$this->mensagemDataColumn})
                    FROM mensagens m3
                    WHERE m3.conversa_id = c.id
                ) DESC,
                c.{$this->conversaDataColumn} DESC
        ";

        return $this->db->query($sql)->fetchAll();
    }

    public function buscarMensagens(int $conversaId): array
    {
        $stmt = $this->db->prepare(
            "SELECT
                id,
                conversa_id,
                remetente_id,
                remetente_tipo,
                mensagem,
                status,
                {$this->mensagemDataColumn} AS criado_em
             FROM mensagens
             WHERE conversa_id = ?
             ORDER BY id ASC"
        );
        $stmt->execute([$conversaId]);

        return $stmt->fetchAll();
    }

    public function enviarMensagem(
        int $conversaId,
        string $tipo,
        int $remetenteId,
        string $mensagem
    ): int {
        $stmt = $this->db->prepare(
            'INSERT INTO mensagens
                (conversa_id, remetente_id, remetente_tipo, mensagem, status)
             VALUES (?, ?, ?, ?, \'enviada\')'
        );

        $stmt->execute([
            $conversaId,
            $remetenteId,
            $tipo,
            $mensagem,
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function marcarRecebidas(int $conversaId, string $destinatario): void
    {
        $remetente = $destinatario === 'admin' ? 'professor' : 'admin';

        $stmt = $this->db->prepare(
            'UPDATE mensagens
             SET status = \'recebida\'
             WHERE conversa_id = ?
               AND remetente_tipo = ?
               AND status = \'enviada\''
        );
        $stmt->execute([$conversaId, $remetente]);
    }

    public function marcarComoLidas(int $conversaId, string $destinatario): void
    {
        $remetente = $destinatario === 'admin' ? 'professor' : 'admin';

        $stmt = $this->db->prepare(
            'UPDATE mensagens
             SET status = \'lida\'
             WHERE conversa_id = ?
               AND remetente_tipo = ?
               AND status IN (\'enviada\', \'recebida\')'
        );
        $stmt->execute([$conversaId, $remetente]);
    }
}
