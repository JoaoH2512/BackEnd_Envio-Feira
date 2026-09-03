CREATE DATABASE IF NOT EXISTS sistema_professor
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE sistema_professor;

DROP TABLE IF EXISTS mensagens;
DROP TABLE IF EXISTS conversas;
DROP TABLE IF EXISTS professores;


CREATE TABLE professores (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    nome VARCHAR(150) NOT NULL,

    ra VARCHAR(30) NOT NULL UNIQUE,

    email VARCHAR(255) NOT NULL UNIQUE,

    senha VARCHAR(255) NOT NULL,

    ativo BOOLEAN NOT NULL DEFAULT TRUE,

    criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    atualizado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
);


CREATE TABLE conversas (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    professor_id INT UNSIGNED NOT NULL,

    criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_conversas_professor
        FOREIGN KEY (professor_id)
        REFERENCES professores(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    UNIQUE KEY unique_professor_conversa (professor_id),

    INDEX idx_conversas_professor (professor_id)
);


CREATE TABLE mensagens (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    conversa_id INT UNSIGNED NOT NULL,

    remetente_id INT UNSIGNED NOT NULL,

    remetente_tipo ENUM(
        'professor',
        'admin'
    ) NOT NULL,

    mensagem TEXT NOT NULL,

    status ENUM(
        'enviada',
        'recebida',
        'lida'
    ) NOT NULL DEFAULT 'enviada',

    criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_mensagens_conversa
        FOREIGN KEY (conversa_id)
        REFERENCES conversas(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    INDEX idx_mensagens_conversa (conversa_id),

    INDEX idx_mensagens_status (status),

    INDEX idx_mensagens_remetente (remetente_id)
);
