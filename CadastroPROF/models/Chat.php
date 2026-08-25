<?php

declare(strict_types=1);

class Chat
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }


    /*
    |--------------------------------------------------------------------------
    | BUSCAR CONVERSA DO PROFESSOR
    |--------------------------------------------------------------------------
    */

    public function buscarConversaDoProfessor(
        int $professorId
    ): ?array {

        $sql = "
            SELECT
                id,
                professor_id,
                criada_em
            FROM conversas
            WHERE professor_id = :professor_id
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':professor_id' => $professorId
        ]);

        $resultado = $stmt->fetch();

        return $resultado ?: null;
    }


    /*
    |--------------------------------------------------------------------------
    | CRIAR CONVERSA
    |--------------------------------------------------------------------------
    */

    public function criarConversa(
        int $professorId
    ): int {

        $sql = "
            INSERT INTO conversas
            (
                professor_id
            )
            VALUES
            (
                :professor_id
            )
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':professor_id' => $professorId
        ]);

        return (int) $this->db->lastInsertId();
    }


    /*
    |--------------------------------------------------------------------------
    | BUSCAR OU CRIAR CONVERSA
    |--------------------------------------------------------------------------
    */

    public function buscarOuCriarConversa(
        int $professorId
    ): array {

        $conversa =
            $this->buscarConversaDoProfessor(
                $professorId
            );

        if ($conversa !== null) {
            return $conversa;
        }

        $id = $this->criarConversa(
            $professorId
        );

        return [
            'id' => $id,
            'professor_id' => $professorId,
            'criada_em' => date('Y-m-d H:i:s')
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | ENVIAR MENSAGEM
    |--------------------------------------------------------------------------
    */

    public function enviarMensagem(
        int $conversaId,
        int $remetenteId,
        string $remetenteTipo,
        string $mensagem
    ): bool {

        $mensagem = trim($mensagem);

        if ($mensagem === '') {
            return false;
        }

        if (
            !in_array(
                $remetenteTipo,
                ['professor', 'admin'],
                true
            )
        ) {
            return false;
        }

        $sql = "
            INSERT INTO mensagens
            (
                conversa_id,
                remetente_id,
                remetente_tipo,
                mensagem,
                status
            )
            VALUES
            (
                :conversa_id,
                :remetente_id,
                :remetente_tipo,
                :mensagem,
                'enviada'
            )
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':conversa_id' => $conversaId,
            ':remetente_id' => $remetenteId,
            ':remetente_tipo' => $remetenteTipo,
            ':mensagem' => $mensagem
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | BUSCAR MENSAGENS
    |--------------------------------------------------------------------------
    */

    public function buscarMensagens(
        int $conversaId
    ): array {

        $sql = "
            SELECT
                id,
                conversa_id,
                remetente_id,
                remetente_tipo,
                mensagem,
                status,
                criada_em
            FROM mensagens
            WHERE conversa_id = :conversa_id
            ORDER BY id ASC
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':conversa_id' => $conversaId
        ]);

        return $stmt->fetchAll();
    }


    /*
    |--------------------------------------------------------------------------
    | BUSCAR TODAS AS CONVERSAS DO ADMIN
    |--------------------------------------------------------------------------
    */

    public function buscarTodasConversas(): array
    {
        $sql = "
            SELECT
                c.id,
                c.professor_id,
                c.criada_em,

                p.nome AS professor_nome,
                p.email AS professor_email,

                (
                    SELECT m.mensagem
                    FROM mensagens m
                    WHERE m.conversa_id = c.id
                    ORDER BY m.id DESC
                    LIMIT 1
                ) AS ultima_mensagem,

                (
                    SELECT m.criada_em
                    FROM mensagens m
                    WHERE m.conversa_id = c.id
                    ORDER BY m.id DESC
                    LIMIT 1
                ) AS ultima_mensagem_em,

                (
                    SELECT COUNT(*)
                    FROM mensagens m
                    WHERE
                        m.conversa_id = c.id
                        AND m.remetente_tipo = 'professor'
                        AND m.status <> 'lida'
                ) AS mensagens_nao_lidas

            FROM conversas c

            INNER JOIN professores p
                ON p.id = c.professor_id

            ORDER BY
                ultima_mensagem_em DESC,
                c.id DESC
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll();
    }


    /*
    |--------------------------------------------------------------------------
    | BUSCAR UMA CONVERSA
    |--------------------------------------------------------------------------
    */

    public function buscarConversa(
        int $conversaId
    ): ?array {

        $sql = "
            SELECT
                c.id,
                c.professor_id,
                c.criada_em,

                p.nome AS professor_nome,
                p.email AS professor_email

            FROM conversas c

            INNER JOIN professores p
                ON p.id = c.professor_id

            WHERE c.id = :id

            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':id' => $conversaId
        ]);

        $resultado = $stmt->fetch();

        return $resultado ?: null;
    }


    /*
    |--------------------------------------------------------------------------
    | MARCAR COMO RECEBIDA
    |--------------------------------------------------------------------------
    */

    public function marcarComoRecebidas(
        int $conversaId,
        string $destinatarioTipo
    ): bool {

        $sql = "
            UPDATE mensagens

            SET status = 'recebida'

            WHERE
                conversa_id = :conversa_id

                AND remetente_tipo <> :destinatario_tipo

                AND status = 'enviada'
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':conversa_id' => $conversaId,
            ':destinatario_tipo' => $destinatarioTipo
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | MARCAR COMO LIDA
    |--------------------------------------------------------------------------
    */

    public function marcarComoLidas(
        int $conversaId,
        string $destinatarioTipo
    ): bool {

        $sql = "
            UPDATE mensagens

            SET status = 'lida'

            WHERE
                conversa_id = :conversa_id

                AND remetente_tipo <> :destinatario_tipo

                AND status IN (
                    'enviada',
                    'recebida'
                )
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':conversa_id' => $conversaId,
            ':destinatario_tipo' => $destinatarioTipo
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | CONTAR NÃO LIDAS
    |--------------------------------------------------------------------------
    */

    public function contarNaoLidas(
        int $conversaId,
        string $destinatarioTipo
    ): int {

        $sql = "
            SELECT COUNT(*) AS total

            FROM mensagens

            WHERE
                conversa_id = :conversa_id

                AND remetente_tipo <> :destinatario_tipo

                AND status <> 'lida'
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':conversa_id' => $conversaId,
            ':destinatario_tipo' => $destinatarioTipo
        ]);

        $resultado = $stmt->fetch();

        return (int) (
            $resultado['total'] ?? 0
        );
    }
}