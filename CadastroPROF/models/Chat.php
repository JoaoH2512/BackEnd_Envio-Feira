<?php
declare(strict_types=1);

class Chat
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
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

        $stmt = $this->db->prepare(
            'INSERT INTO conversas (professor_id) VALUES (?)'
        );
        $stmt->execute([$professorId]);

        return (int) $this->db->lastInsertId();
    }

    public function buscarConversaDoProfessor(int $professorId): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT c.id, c.professor_id, c.criado_em, p.nome, p.ra, p.email
             FROM conversas c
             INNER JOIN professor p ON p.id = c.professor_id
             WHERE c.professor_id = ?
             LIMIT 1'
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
        $sql = '
            SELECT
                c.id,
                c.professor_id,
                p.nome,
                p.ra,
                p.email,
                c.criado_em,
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
                      AND m2.remetente_tipo = "professor"
                      AND m2.status <> "lida"
                ) AS nao_lidas
            FROM conversas c
            INNER JOIN professor p ON p.id = c.professor_id
            ORDER BY
                (
                    SELECT MAX(m3.criado_em)
                    FROM mensagens m3
                    WHERE m3.conversa_id = c.id
                ) DESC,
                c.criado_em DESC
        ';

        return $this->db->query($sql)->fetchAll();
    }

    public function buscarMensagens(int $conversaId): array
    {
        $stmt = $this->db->prepare(
            'SELECT
                id,
                conversa_id,
                remetente_id,
                remetente_tipo,
                mensagem,
                status,
                criado_em
             FROM mensagens
             WHERE conversa_id = ?
             ORDER BY id ASC'
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
             VALUES (?, ?, ?, ?, "enviada")'
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
             SET status = "recebida"
             WHERE conversa_id = ?
               AND remetente_tipo = ?
               AND status = "enviada"'
        );
        $stmt->execute([$conversaId, $remetente]);
    }

    public function marcarComoLidas(int $conversaId, string $destinatario): void
    {
        $remetente = $destinatario === 'admin' ? 'professor' : 'admin';

        $stmt = $this->db->prepare(
            'UPDATE mensagens
             SET status = "lida"
             WHERE conversa_id = ?
               AND remetente_tipo = ?
               AND status IN ("enviada", "recebida")'
        );
        $stmt->execute([$conversaId, $remetente]);
    }
}
