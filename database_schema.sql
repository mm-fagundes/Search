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

CREATE TABLE IF NOT EXISTS `prestadores` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nome` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `senha` VARCHAR(255) NOT NULL,
    `telefone` VARCHAR(20),
    `endereco` VARCHAR(255),
    `nicho` VARCHAR(255), -- Ex: Eletricista, Encanador, Pintor
    `categoria_id` INT DEFAULT NULL,
    `horario_inicio` TIME DEFAULT '08:00:00',
    `horario_fim` TIME DEFAULT '18:00:00',
    `descricao` TEXT,
    `foto` VARCHAR(255),
    `criado_em` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela para Categorias de Prestadores
CREATE TABLE IF NOT EXISTS `categorias` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nome` VARCHAR(255) NOT NULL UNIQUE,
    `descricao` TEXT,
    `ícone` VARCHAR(255),
    `criado_em` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Alterar tabela prestadores para adicionar horários (se não existir)
-- ALTER TABLE `prestadores` ADD COLUMN `horario_inicio` TIME DEFAULT '08:00:00';
-- ALTER TABLE `prestadores` ADD COLUMN `horario_fim` TIME DEFAULT '18:00:00';

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

-- Tabela para Favoritos
CREATE TABLE IF NOT EXISTS `favoritos` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `cliente_id` INT NOT NULL,
    `prestador_id` INT NOT NULL,
    `criado_em` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `unique_favorito` (`cliente_id`, `prestador_id`),
    FOREIGN KEY (`cliente_id`) REFERENCES `clientes`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`prestador_id`) REFERENCES `prestadores`(`id`) ON DELETE CASCADE
);

-- Tabela para Histórico de Visualizações
CREATE TABLE IF NOT EXISTS `historico` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `cliente_id` INT NOT NULL,
    `prestador_id` INT,
    `tipo_acao` VARCHAR(50), -- 'visualizacao' ou 'busca'
    `termo_busca` VARCHAR(255),
    `criado_em` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`cliente_id`) REFERENCES `clientes`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`prestador_id`) REFERENCES `prestadores`(`id`) ON DELETE CASCADE
);

-- Tabela para Agendamentos
CREATE TABLE IF NOT EXISTS `agendamentos` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `prestador_id` INT NOT NULL,
    `cliente_id` INT NOT NULL,
    `servico_id` INT NOT NULL,
    `data_agendamento` DATETIME NOT NULL,
    `status` ENUM('pendente', 'confirmado', 'cancelado', 'concluido') DEFAULT 'pendente',
    `observacoes` TEXT,
    `criado_em` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`prestador_id`) REFERENCES `prestadores`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`cliente_id`) REFERENCES `clientes`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`servico_id`) REFERENCES `servicos`(`id`) ON DELETE CASCADE
);

INSERT INTO `categorias` (`nome`, `descricao`, `ícone`) VALUES
('Eletricista', 'Profissionais especializados em instalações elétricas e reparos', 'eletricista_icon.png'),
('Encanador', 'Profissionais especializados em encanamento e sistemas hidráulicos', 'encanador_icon.png'),
('Pintor', 'Pintores profissionais para pintura de paredes e decoração', 'pintor_icon.png'),
('Jardinagem', 'Especialistas em jardinagem, poda e manutenção de espaços verdes', 'jardinagem_icon.png');

-- Inserir prestadores de serviço
INSERT INTO `prestadores` (`nome`, `email`, `senha`, `telefone`, `endereco`, `nicho`, `categoria_id`, `horario_inicio`, `horario_fim`, `descricao`, `foto`) VALUES
('Carlos Silva', 'carlos.silva@gmail.com', 'carlos123', '99988-7766', 'Rua das Flores, 123', 'Eletricista', 1, '08:00:00', '18:00:00', 'Eletricista especializado em instalações residenciais e comerciais', 'eletricista1.jpg'),
('Ana Souza', 'ana.souza@gmail.com', 'ana123', '98877-6655', 'Avenida Brasil, 456', 'Encanadora', 2, '08:00:00', '18:00:00', 'Especialista em conserto e instalação de sistemas hidráulicos', 'encanadora1.jpg'),
('Marcos Oliveira', 'marcos.oliveira@gmail.com', 'marcos123', '97766-5544', 'Rua São Paulo, 789', 'Pintor', 3, '09:00:00', '17:00:00', 'Pintor profissional para ambientes internos e externos', 'pintor1.jpg'),
('Juliana Costa', 'juliana.costa@gmail.com', 'juliana123', '96655-4433', 'Rua das Palmeiras, 101', 'Jardinagem', 4, '07:00:00', '16:00:00', 'Especialista em manutenção de jardins e paisagismo', 'jardinagem1.jpg');


-- (Se a tabela `categorias` ainda não existir, já existe uma definição acima.)

-- Caso o banco já exista e as colunas não tenham sido adicionadas, forneça instruções
-- para alterar a tabela prestadores e adicionar os campos de categoria e horário.
-- Essas instruções são seguras para executar apenas uma vez em migrações manuais.
-- Exemplo (remova os comentários ao executar):
-- ALTER TABLE `prestadores` ADD COLUMN `categoria_id` INT DEFAULT NULL;
-- ALTER TABLE `prestadores` ADD COLUMN `horario_inicio` TIME DEFAULT '08:00:00';
-- ALTER TABLE `prestadores` ADD COLUMN `horario_fim` TIME DEFAULT '18:00:00';

-- Caso deseje adicionar chave estrangeira para categorias (se desejar):
-- ALTER TABLE `prestadores` ADD CONSTRAINT `fk_prestadores_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categorias`(`id`) ON DELETE SET NULL;



