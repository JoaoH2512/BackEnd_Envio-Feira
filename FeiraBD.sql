-- =====================================================================
-- FeiraBD.sql
-- Banco de dados unificado do Sistema Escolar / Feira de Projetos
--
-- Este arquivo unifica:
--   1) A estrutura original de "FeiraBD.sql" (alunos, projetos, avaliações,
--      stands e, principalmente, a tabela `professor`, usada como base
--      para o cadastro de professores).
--   2) A parte de CHAT entre professor e administração vinda de
--      "database.sql" (tabelas `conversas` e `mensagens`), adaptada para
--      referenciar a tabela `professor` (em vez de `professores`).
--
-- Tabelas internas do phpMyAdmin (pma__*) e o banco `test` do dump
-- original foram removidas por não fazerem parte da aplicação.
-- =====================================================================

CREATE DATABASE IF NOT EXISTS feirabd
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE feirabd;

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS mensagens;
DROP TABLE IF EXISTS conversas;
DROP TABLE IF EXISTS aluno_projeto;
DROP TABLE IF EXISTS avaliacao;
DROP TABLE IF EXISTS mediador;
DROP TABLE IF EXISTS stands;
DROP TABLE IF EXISTS projeto;
DROP TABLE IF EXISTS criterio;
DROP TABLE IF EXISTS aluno;
DROP TABLE IF EXISTS professor;

SET FOREIGN_KEY_CHECKS = 1;


-- ---------------------------------------------------------------------
-- PROFESSOR
-- Estrutura de cadastro vinda de FeiraBD.sql (nome, email, senha,
-- matrícula e tipo). É esta tabela que o login/cadastro do professor
-- e o chat com a administração passam a usar.
-- ---------------------------------------------------------------------
CREATE TABLE professor (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    nome VARCHAR(150) NOT NULL,

    email VARCHAR(150) NOT NULL,

    senha VARCHAR(255) NOT NULL,

    matricula VARCHAR(50) NOT NULL,

    tipo ENUM(
        'orientador',
        'avaliador',
        'coordenador'
    ) NOT NULL DEFAULT 'avaliador',

    criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    UNIQUE KEY uq_professor_email (email),
    UNIQUE KEY uq_professor_matricula (matricula)
);


-- ---------------------------------------------------------------------
-- ALUNO
-- ---------------------------------------------------------------------
CREATE TABLE aluno (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    rm VARCHAR(50) NOT NULL,

    nome VARCHAR(150) NOT NULL,

    email VARCHAR(150) NOT NULL,

    senha_hash VARCHAR(255) NOT NULL,

    curso VARCHAR(100) DEFAULT NULL,

    turma VARCHAR(50) DEFAULT NULL,

    tipo ENUM(
        'lider',
        'integrante'
    ) NOT NULL DEFAULT 'integrante',

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    UNIQUE KEY uq_aluno_rm (rm),
    UNIQUE KEY uq_aluno_email (email)
);


-- ---------------------------------------------------------------------
-- CRITERIO
-- ---------------------------------------------------------------------
CREATE TABLE criterio (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    nome VARCHAR(150) NOT NULL
);


-- ---------------------------------------------------------------------
-- PROJETO
-- ---------------------------------------------------------------------
CREATE TABLE projeto (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    nome VARCHAR(200) NOT NULL,

    descricao TEXT DEFAULT NULL,

    periodo ENUM(
        'matutino',
        'vespertino',
        'noturno'
    ) NOT NULL,

    orientador_id INT UNSIGNED NOT NULL,

    status ENUM(
        'pendente',
        'em_andamento',
        'concluido',
        'cancelado'
    ) NOT NULL DEFAULT 'pendente',

    ods VARCHAR(255) DEFAULT NULL,

    links TEXT DEFAULT NULL,

    senha_acesso VARCHAR(255) DEFAULT NULL,

    id_aluno INT UNSIGNED NOT NULL,

    nota DECIMAL(5,2) DEFAULT NULL,

    criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_projeto_orientador
        FOREIGN KEY (orientador_id)
        REFERENCES professor(id)
        ON UPDATE CASCADE,

    CONSTRAINT fk_projeto_aluno
        FOREIGN KEY (id_aluno)
        REFERENCES aluno(id)
        ON UPDATE CASCADE,

    INDEX idx_projeto_orientador (orientador_id),
    INDEX idx_projeto_aluno (id_aluno)
);


-- ---------------------------------------------------------------------
-- ALUNO_PROJETO
-- ---------------------------------------------------------------------
CREATE TABLE aluno_projeto (
    id_aluno INT UNSIGNED NOT NULL,

    id_projeto INT UNSIGNED NOT NULL,

    PRIMARY KEY (id_aluno, id_projeto),

    CONSTRAINT fk_alunoprojeto_aluno
        FOREIGN KEY (id_aluno)
        REFERENCES aluno(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_alunoprojeto_projeto
        FOREIGN KEY (id_projeto)
        REFERENCES projeto(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);


-- ---------------------------------------------------------------------
-- AVALIACAO
-- ---------------------------------------------------------------------
CREATE TABLE avaliacao (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    avaliador_id INT UNSIGNED NOT NULL,

    projeto_id INT UNSIGNED NOT NULL,

    criterio_id INT UNSIGNED NOT NULL,

    nota DECIMAL(5,2) DEFAULT NULL,

    status ENUM(
        'pendente',
        'concluida'
    ) NOT NULL DEFAULT 'pendente',

    observacao TEXT DEFAULT NULL,

    UNIQUE KEY uq_avaliacao_composta (
        avaliador_id,
        projeto_id,
        criterio_id
    ),

    CONSTRAINT fk_avaliacao_professor
        FOREIGN KEY (avaliador_id)
        REFERENCES professor(id)
        ON UPDATE CASCADE,

    CONSTRAINT fk_avaliacao_projeto
        FOREIGN KEY (projeto_id)
        REFERENCES projeto(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_avaliacao_criterio
        FOREIGN KEY (criterio_id)
        REFERENCES criterio(id)
        ON UPDATE CASCADE,

    INDEX idx_avaliacao_projeto (projeto_id),
    INDEX idx_avaliacao_criterio (criterio_id)
);


-- ---------------------------------------------------------------------
-- MEDIADOR
-- ---------------------------------------------------------------------
CREATE TABLE mediador (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    fk_professor INT UNSIGNED NOT NULL,

    fk_projeto INT UNSIGNED NOT NULL,

    UNIQUE KEY uq_mediador_professor_projeto (
        fk_professor,
        fk_projeto
    ),

    CONSTRAINT fk_mediador_professor
        FOREIGN KEY (fk_professor)
        REFERENCES professor(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_mediador_projeto
        FOREIGN KEY (fk_projeto)
        REFERENCES projeto(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    INDEX idx_mediador_projeto (fk_projeto)
);


-- ---------------------------------------------------------------------
-- STANDS
-- ---------------------------------------------------------------------
CREATE TABLE stands (
    id_stand INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    sala VARCHAR(100) DEFAULT NULL,

    num_stand VARCHAR(50) DEFAULT NULL,

    fk_projeto INT UNSIGNED NOT NULL,

    CONSTRAINT fk_stands_projeto
        FOREIGN KEY (fk_projeto)
        REFERENCES projeto(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    INDEX idx_stands_projeto (fk_projeto)
);


-- =====================================================================
-- CHAT PROFESSOR <-> ADMINISTRAÇÃO
-- Vindo de database.sql, adaptado para referenciar `professor` (singular,
-- com a estrutura da FeiraBD) em vez de `professores`.
-- =====================================================================

CREATE TABLE conversas (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    professor_id INT UNSIGNED NOT NULL,

    criada_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    UNIQUE KEY unique_professor_conversa (professor_id),

    CONSTRAINT fk_conversas_professor
        FOREIGN KEY (professor_id)
        REFERENCES professor(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

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

    criada_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_mensagens_conversa
        FOREIGN KEY (conversa_id)
        REFERENCES conversas(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    INDEX idx_mensagens_conversa (conversa_id),
    INDEX idx_mensagens_status (status),
    INDEX idx_mensagens_remetente (remetente_id)
);