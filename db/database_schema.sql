CREATE DATABASE IF NOT EXISTS `search_db`;
USE `search_db`;

-- Tabela para Clientes
CREATE TABLE IF NOT EXISTS `clientes` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nome` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `senha` VARCHAR(255) NOT NULL,
    `telefone` VARCHAR(20),
    `endereco` VARCHAR(255),
    `criado_em` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela para Prestadores de Serviço
CREATE TABLE IF NOT EXISTS `prestadores` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nome` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `senha` VARCHAR(255) NOT NULL,
    `telefone` VARCHAR(20),
    `endereco` VARCHAR(255),
    `nicho` VARCHAR(255), -- Ex: Eletricista, Encanador, Pintor
    `descricao` TEXT,
    `foto` VARCHAR(255),
    `criado_em` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela para Serviços (opcional, se cada prestador oferecer múltiplos serviços específicos)
CREATE TABLE IF NOT EXISTS `servicos` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `prestador_id` INT NOT NULL,
    `nome_servico` VARCHAR(255) NOT NULL,
    `descricao_servico` TEXT,
    `preco_base` DECIMAL(10, 2),
    `criado_em` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`prestador_id`) REFERENCES `prestadores`(`id`) ON DELETE CASCADE
);

-- Tabela para Avaliações
CREATE TABLE IF NOT EXISTS `avaliacoes` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `prestador_id` INT NOT NULL,
    `cliente_id` INT NOT NULL,
    `nota` INT NOT NULL CHECK (nota >= 1 AND nota <= 5),
    `comentario` TEXT,
    `criado_em` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`prestador_id`) REFERENCES `prestadores`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`cliente_id`) REFERENCES `clientes`(`id`) ON DELETE CASCADE
);


