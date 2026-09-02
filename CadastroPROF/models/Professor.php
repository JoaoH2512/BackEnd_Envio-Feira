<?php
declare(strict_types=1);

class Professor
{
    private PDO $db;
    private array $columns = [];

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->carregarColunas();
    }

    private function carregarColunas(): void
    {
        try {
            $stmt = $this->db->query("SHOW COLUMNS FROM professor");
            $this->columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            $this->columns = [];
        }
    }

    private function colunaExiste(string $coluna): bool
    {
        return in_array($coluna, $this->columns);
    }

    private function getCamposSelect(): string
    {
        $campos = ['id', 'nome'];
        
        if ($this->colunaExiste('ra')) {
            $campos[] = 'ra';
        }
        
        $campos[] = 'email';
        
        if ($this->colunaExiste('senha')) {
            $campos[] = 'senha';
        } elseif ($this->colunaExiste('senha_hash')) {
            $campos[] = 'senha_hash as senha';
        }
        
        if ($this->colunaExiste('tipo')) {
            $campos[] = 'tipo';
        }
        
        if ($this->colunaExiste('ativo')) {
            $campos[] = 'ativo';
        } else {
            $campos[] = '1 as ativo';
        }
        
        if ($this->colunaExiste('criado_em')) {
            $campos[] = 'criado_em';
        }
        
        return implode(', ', $campos);
    }

    public function emailExiste(string $email): bool
    {
        $stmt = $this->db->prepare('SELECT 1 FROM professor WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        return (bool) $stmt->fetchColumn();
    }

    public function raExiste(string $ra): bool
    {
        if (!$this->colunaExiste('ra')) {
            return false;
        }
        $stmt = $this->db->prepare('SELECT 1 FROM professor WHERE ra = ? LIMIT 1');
        $stmt->execute([$ra]);
        return (bool) $stmt->fetchColumn();
    }

    public function matriculaExiste(string $matricula): bool
    {
        if (!$this->colunaExiste('matricula')) {
            return false;
        }
        $stmt = $this->db->prepare('SELECT 1 FROM professor WHERE matricula = ? LIMIT 1');
        $stmt->execute([$matricula]);
        return (bool) $stmt->fetchColumn();
    }

    public function criar(
        string $nome,
        string $ra,
        string $email,
        string $senha,
        string $tipo
    ): int {
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
        
        // Verificar quais colunas existem
        $colunas = ['nome', 'email'];
        $placeholders = ['?', '?'];
        $params = [$nome, $email];
        
        if ($this->colunaExiste('ra')) {
            $colunas[] = 'ra';
            $placeholders[] = '?';
            $params[] = $ra;
        }
        
        if ($this->colunaExiste('senha')) {
            $colunas[] = 'senha';
            $placeholders[] = '?';
            $params[] = $senhaHash;
        }
        
        if ($this->colunaExiste('matricula')) {
            $colunas[] = 'matricula';
            $placeholders[] = '?';
            $params[] = $ra; // Usando RA como matrícula
        }
        
        if ($this->colunaExiste('tipo')) {
            $colunas[] = 'tipo';
            $placeholders[] = '?';
            $params[] = $tipo;
        }
        
        $sql = 'INSERT INTO professor (' . implode(', ', $colunas) . ') VALUES (' . implode(', ', $placeholders) . ')';
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $this->db->lastInsertId();
    }

    public function buscarPorRA(string $ra): ?array
    {
        if (!$this->colunaExiste('ra')) {
            return null;
        }

        $sql = "SELECT " . $this->getCamposSelect() . " FROM professor WHERE ra = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$ra]);
        $professor = $stmt->fetch();
        return $professor ?: null;
    }

    public function buscarPorEmail(string $email): ?array
    {
        $sql = "SELECT " . $this->getCamposSelect() . " FROM professor WHERE email = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        $professor = $stmt->fetch();
        return $professor ?: null;
    }

    public function buscarPorId(int $id): ?array
    {
        $sql = "SELECT " . $this->getCamposSelect() . " FROM professor WHERE id = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $professor = $stmt->fetch();
        return $professor ?: null;
    }

    public function buscarTodos(): array
    {
        $sql = "SELECT " . $this->getCamposSelect() . " FROM professor ORDER BY nome ASC";
        return $this->db->query($sql)->fetchAll();
    }

    public function excluir(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM professor WHERE id = ?');
        $stmt->execute([$id]);
    }
}