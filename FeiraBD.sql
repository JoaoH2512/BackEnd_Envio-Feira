-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 15/09/2026 às 22:21
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `feirabd`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `aluno`
--

CREATE TABLE `aluno` (
  `id` int(10) UNSIGNED NOT NULL,
  `rm` varchar(50) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `senha_hash` varchar(255) NOT NULL,
  `curso` varchar(100) DEFAULT NULL,
  `turma` varchar(50) DEFAULT NULL,
  `tipo` enum('lider','integrante') NOT NULL DEFAULT 'integrante',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `aluno`
--

INSERT INTO `aluno` (`id`, `rm`, `nome`, `email`, `senha_hash`, `curso`, `turma`, `tipo`, `created_at`) VALUES
(1, '20260001', 'Lucas Almeida', 'lucas.almeida@email.com', 'hash_ficticio_001', 'Informática para Internet', '3F', '', '2026-09-15 17:56:52'),
(2, '20260002', 'Matheus Oliveira', 'matheus.oliveira@email.com', 'hash_ficticio_002', 'Informática para Internet', '3F', '', '2026-09-15 17:56:52'),
(3, '20260003', 'Gabriel Santos', 'gabriel.santos@email.com', 'hash_ficticio_003', 'Administração', '3A', '', '2026-09-15 17:56:52'),
(4, '20260004', 'Pedro Henrique', 'pedro.henrique@email.com', 'hash_ficticio_004', 'Informática para Internet', '3F', '', '2026-09-15 17:56:52'),
(5, '20260005', 'Rafael Costa', 'rafael.costa@email.com', 'hash_ficticio_005', 'Administração', '3A', '', '2026-09-15 17:56:52'),
(6, '20260006', 'João Vitor', 'joao.vitor@email.com', 'hash_ficticio_006', 'Informática para Internet', '3F', '', '2026-09-15 17:56:52'),
(7, '20260007', 'Bruno Martins', 'bruno.martins@email.com', 'hash_ficticio_007', 'Administração', '3B', '', '2026-09-15 17:56:52'),
(8, '20260008', 'Daniel Souza', 'daniel.souza@email.com', 'hash_ficticio_008', 'Informática para Internet', '3F', '', '2026-09-15 17:56:52'),
(9, '20260009', 'Felipe Rodrigues', 'felipe.rodrigues@email.com', 'hash_ficticio_009', 'Administração', '3B', '', '2026-09-15 17:56:52'),
(10, '20260010', 'Gustavo Ferreira', 'gustavo.ferreira@email.com', 'hash_ficticio_010', 'Informática para Internet', '3F', '', '2026-09-15 17:56:52');

-- --------------------------------------------------------

--
-- Estrutura para tabela `aluno_projeto`
--

CREATE TABLE `aluno_projeto` (
  `id_aluno` int(10) UNSIGNED NOT NULL,
  `id_projeto` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `avaliacao`
--

CREATE TABLE `avaliacao` (
  `id` int(10) UNSIGNED NOT NULL,
  `avaliador_id` int(10) UNSIGNED NOT NULL,
  `projeto_id` int(10) UNSIGNED NOT NULL,
  `criterio_id` int(10) UNSIGNED NOT NULL,
  `nota` decimal(5,2) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `observacao` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `avaliacao`
--

INSERT INTO `avaliacao` (`id`, `avaliador_id`, `projeto_id`, `criterio_id`, `nota`, `status`, `observacao`) VALUES
(60, 1, 18, 1, 7.00, 'Avaliado', ''),
(61, 1, 18, 2, 4.00, 'Avaliado', ''),
(62, 1, 18, 3, 7.00, 'Avaliado', ''),
(63, 1, 18, 4, 5.00, 'Avaliado', ''),
(64, 1, 18, 5, 8.00, 'Avaliado', ''),
(65, 1, 18, 6, 1.00, 'Avaliado', ''),
(66, 1, 17, 1, 10.00, 'Avaliado', 'Boa participação durante o desenvolvimento.'),
(67, 1, 17, 2, 3.00, 'Avaliado', 'Boa participação durante o desenvolvimento.'),
(68, 1, 17, 3, 4.00, 'Avaliado', 'Boa participação durante o desenvolvimento.'),
(69, 1, 17, 4, 8.00, 'Avaliado', 'Boa participação durante o desenvolvimento.'),
(70, 1, 17, 5, 9.00, 'Avaliado', 'Boa participação durante o desenvolvimento.'),
(71, 1, 17, 6, 0.00, 'Avaliado', 'Boa participação durante o desenvolvimento.');

-- --------------------------------------------------------

--
-- Estrutura para tabela `conversas`
--

CREATE TABLE `conversas` (
  `id` int(10) UNSIGNED NOT NULL,
  `professor_id` int(10) UNSIGNED NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `criterio`
--

CREATE TABLE `criterio` (
  `id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `criterio`
--

INSERT INTO `criterio` (`id`, `nome`) VALUES
(1, 'Oralidade'),
(2, 'Postura'),
(3, 'Organização'),
(4, 'Criatividade'),
(5, 'Capricho'),
(6, 'Domínio');

-- --------------------------------------------------------

--
-- Estrutura para tabela `mediador`
--

CREATE TABLE `mediador` (
  `id` int(10) UNSIGNED NOT NULL,
  `fk_professor` int(10) UNSIGNED NOT NULL,
  `fk_projeto` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `mensagens`
--

CREATE TABLE `mensagens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `conversa_id` int(10) UNSIGNED NOT NULL,
  `remetente_id` int(10) UNSIGNED NOT NULL,
  `remetente_tipo` enum('professor','admin') NOT NULL,
  `mensagem` text NOT NULL,
  `status` enum('enviada','recebida','lida') NOT NULL DEFAULT 'enviada',
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `professor`
--

CREATE TABLE `professor` (
  `id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `matricula` varchar(50) NOT NULL,
  `tipo` enum('orientador','avaliador','coordenador') NOT NULL DEFAULT 'avaliador',
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `professor`
--

INSERT INTO `professor` (`id`, `nome`, `email`, `senha`, `matricula`, `tipo`, `criado_em`) VALUES
(1, 'Amanda Chagas', 'amanda_chagas@gmail.com', '$2y$10$N4xgnjaWWFI8gwZ/Gx2qYuoqqqOk7mx35.1gw8bhB.r.TWd4lrOgy', '123456', 'avaliador', '2026-09-15 17:43:07');

-- --------------------------------------------------------

--
-- Estrutura para tabela `projeto`
--

CREATE TABLE `projeto` (
  `id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(200) NOT NULL,
  `descricao` text DEFAULT NULL,
  `periodo` varchar(255) DEFAULT NULL,
  `orientador_id` int(10) UNSIGNED NOT NULL,
  `status` varchar(255) DEFAULT NULL,
  `ods` varchar(255) DEFAULT NULL,
  `links` text DEFAULT NULL,
  `senha_acesso` varchar(255) DEFAULT NULL,
  `id_aluno` int(10) UNSIGNED NOT NULL,
  `nota` decimal(5,2) DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `projeto`
--

INSERT INTO `projeto` (`id`, `nome`, `descricao`, `periodo`, `orientador_id`, `status`, `ods`, `links`, `senha_acesso`, `id_aluno`, `nota`, `criado_em`) VALUES
(17, 'Projeto Teste', 'Projeto fictício para testar o cadastro.', '2026', 1, 'Em andamento', 'ODS 4 - Educação de Qualidade', 'https://github.com/projeto-teste', '123456', 1, 8.50, '2026-09-15 18:05:01'),
(18, 'Educação Financeira', 'Projeto fictício sobre educação financeira.', '2026', 1, 'Em andamento', 'ODS 4 - Educação de Qualidade', 'https://github.com/projeto', '123456', 1, 9.00, '2026-09-15 18:08:33');

-- --------------------------------------------------------

--
-- Estrutura para tabela `stands`
--

CREATE TABLE `stands` (
  `id_stand` int(10) UNSIGNED NOT NULL,
  `sala` varchar(100) DEFAULT NULL,
  `num_stand` varchar(50) DEFAULT NULL,
  `fk_projeto` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `aluno`
--
ALTER TABLE `aluno`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_aluno_rm` (`rm`),
  ADD UNIQUE KEY `uq_aluno_email` (`email`);

--
-- Índices de tabela `aluno_projeto`
--
ALTER TABLE `aluno_projeto`
  ADD PRIMARY KEY (`id_aluno`,`id_projeto`),
  ADD KEY `fk_alunoprojeto_projeto` (`id_projeto`);

--
-- Índices de tabela `avaliacao`
--
ALTER TABLE `avaliacao`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_avaliacao_composta` (`avaliador_id`,`projeto_id`,`criterio_id`),
  ADD KEY `idx_avaliacao_projeto` (`projeto_id`),
  ADD KEY `idx_avaliacao_criterio` (`criterio_id`);

--
-- Índices de tabela `conversas`
--
ALTER TABLE `conversas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_professor_conversa` (`professor_id`),
  ADD KEY `idx_conversas_professor` (`professor_id`);

--
-- Índices de tabela `criterio`
--
ALTER TABLE `criterio`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `mediador`
--
ALTER TABLE `mediador`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_mediador_professor_projeto` (`fk_professor`,`fk_projeto`),
  ADD KEY `idx_mediador_projeto` (`fk_projeto`);

--
-- Índices de tabela `mensagens`
--
ALTER TABLE `mensagens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_mensagens_conversa_data` (`conversa_id`,`criado_em`,`id`),
  ADD KEY `idx_mensagens_status` (`status`),
  ADD KEY `idx_mensagens_remetente` (`remetente_id`);

--
-- Índices de tabela `professor`
--
ALTER TABLE `professor`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_professor_email` (`email`),
  ADD UNIQUE KEY `uq_professor_matricula` (`matricula`);

--
-- Índices de tabela `projeto`
--
ALTER TABLE `projeto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_projeto_orientador` (`orientador_id`),
  ADD KEY `idx_projeto_aluno` (`id_aluno`);

--
-- Índices de tabela `stands`
--
ALTER TABLE `stands`
  ADD PRIMARY KEY (`id_stand`),
  ADD KEY `idx_stands_projeto` (`fk_projeto`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `aluno`
--
ALTER TABLE `aluno`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `avaliacao`
--
ALTER TABLE `avaliacao`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT de tabela `conversas`
--
ALTER TABLE `conversas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `criterio`
--
ALTER TABLE `criterio`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `mediador`
--
ALTER TABLE `mediador`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `mensagens`
--
ALTER TABLE `mensagens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `professor`
--
ALTER TABLE `professor`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `projeto`
--
ALTER TABLE `projeto`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de tabela `stands`
--
ALTER TABLE `stands`
  MODIFY `id_stand` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `aluno_projeto`
--
ALTER TABLE `aluno_projeto`
  ADD CONSTRAINT `fk_alunoprojeto_aluno` FOREIGN KEY (`id_aluno`) REFERENCES `aluno` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_alunoprojeto_projeto` FOREIGN KEY (`id_projeto`) REFERENCES `projeto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `avaliacao`
--
ALTER TABLE `avaliacao`
  ADD CONSTRAINT `fk_avaliacao_criterio` FOREIGN KEY (`criterio_id`) REFERENCES `criterio` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_avaliacao_professor` FOREIGN KEY (`avaliador_id`) REFERENCES `professor` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_avaliacao_projeto` FOREIGN KEY (`projeto_id`) REFERENCES `projeto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `conversas`
--
ALTER TABLE `conversas`
  ADD CONSTRAINT `fk_conversas_professor` FOREIGN KEY (`professor_id`) REFERENCES `professor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `mediador`
--
ALTER TABLE `mediador`
  ADD CONSTRAINT `fk_mediador_professor` FOREIGN KEY (`fk_professor`) REFERENCES `professor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_mediador_projeto` FOREIGN KEY (`fk_projeto`) REFERENCES `projeto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `mensagens`
--
ALTER TABLE `mensagens`
  ADD CONSTRAINT `fk_mensagens_conversa` FOREIGN KEY (`conversa_id`) REFERENCES `conversas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `projeto`
--
ALTER TABLE `projeto`
  ADD CONSTRAINT `fk_projeto_aluno` FOREIGN KEY (`id_aluno`) REFERENCES `aluno` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_projeto_orientador` FOREIGN KEY (`orientador_id`) REFERENCES `professor` (`id`) ON UPDATE CASCADE;

--
-- Restrições para tabelas `stands`
--
ALTER TABLE `stands`
  ADD CONSTRAINT `fk_stands_projeto` FOREIGN KEY (`fk_projeto`) REFERENCES `projeto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
