<?php
declare(strict_types=1);

require_once __DIR__ . '/config/Database.php';

try {
    $db = (new Database())->connect();
    
    echo "🔍 Verificando estrutura da tabela professor...\n\n";
    
    // Verificar colunas existentes
    $stmt = $db->query("SHOW COLUMNS FROM professor");
    $existingColumns = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $existingColumns[] = $row['Field'];
        echo "  ✅ Coluna encontrada: {$row['Field']} ({$row['Type']})\n";
    }
    
    echo "\n📋 Colunas esperadas: nome, ra, email, senha, matricula, tipo, ativo, criado_em\n\n";
    
    // Verificar colunas faltantes
    $expectedColumns = ['nome', 'ra', 'email', 'senha', 'matricula', 'tipo', 'ativo', 'criado_em'];
    $missingColumns = array_diff($expectedColumns, $existingColumns);
    
    if (empty($missingColumns)) {
        echo "✅ Todas as colunas estão corretas!\n";
        exit;
    }
    
    echo "⚠️ Colunas faltantes: " . implode(', ', $missingColumns) . "\n\n";
    echo "🔄 Adicionando colunas faltantes...\n\n";
    
    // Adicionar colunas faltantes uma por uma
    foreach ($missingColumns as $column) {
        try {
            switch ($column) {
                case 'ra':
                    $db->exec("ALTER TABLE professor ADD COLUMN ra VARCHAR(50) NOT NULL UNIQUE AFTER nome");
                    echo "  ✅ Coluna 'ra' adicionada\n";
                    break;
                case 'ativo':
                    $db->exec("ALTER TABLE professor ADD COLUMN ativo TINYINT(1) NOT NULL DEFAULT 1 AFTER tipo");
                    echo "  ✅ Coluna 'ativo' adicionada\n";
                    break;
                case 'criado_em':
                    $db->exec("ALTER TABLE professor ADD COLUMN criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER ativo");
                    echo "  ✅ Coluna 'criado_em' adicionada\n";
                    break;
                case 'senha':
                    $db->exec("ALTER TABLE professor ADD COLUMN senha VARCHAR(255) NOT NULL AFTER email");
                    echo "  ✅ Coluna 'senha' adicionada\n";
                    break;
                case 'matricula':
                    $db->exec("ALTER TABLE professor ADD COLUMN matricula VARCHAR(50) NOT NULL UNIQUE AFTER senha");
                    echo "  ✅ Coluna 'matricula' adicionada\n";
                    break;
                case 'tipo':
                    $db->exec("ALTER TABLE professor ADD COLUMN tipo ENUM('orientador','avaliador','coordenador') NOT NULL DEFAULT 'avaliador' AFTER matricula");
                    echo "  ✅ Coluna 'tipo' adicionada\n";
                    break;
            }
        } catch (PDOException $e) {
            echo "  ❌ Erro ao adicionar coluna '$column': " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n✅ Correção concluída!\n";
    echo "🔄 Recarregue a página do admin agora.\n";
    
} catch (PDOException $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
    echo "💡 Verifique se o banco de dados 'feirabd' existe e está acessível.\n";
}