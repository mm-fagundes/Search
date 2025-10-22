CREATE TABLE `clientes` (
  `id_cliente` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `cpf` varchar(14) NOT NULL,
  `telefone` varchar(15) NOT NULL,
  PRIMARY KEY (`id_cliente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `prestadores` (
  `id_prestador` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `cpf` varchar(14) NOT NULL,
  `telefone` varchar(15) NOT NULL,
  PRIMARY KEY (`id_prestador`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `servicos` (
  `id_servico` int(11) NOT NULL AUTO_INCREMENT,
  `id_prestador` int(11) NOT NULL,
  `nome_servico` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id_servico`),
  KEY `id_prestador` (`id_prestador`),
  CONSTRAINT `servicos_ibfk_1` FOREIGN KEY (`id_prestador`) REFERENCES `prestadores` (`id_prestador`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `agendamentos` (
  `id_agendamento` int(11) NOT NULL AUTO_INCREMENT,
  `id_cliente` int(11) NOT NULL,
  `id_servico` int(11) NOT NULL,
  `data_hora` datetime NOT NULL,
  `status` varchar(50) NOT NULL,
  PRIMARY KEY (`id_agendamento`),
  KEY `id_cliente` (`id_cliente`),
  KEY `id_servico` (`id_servico`),
  CONSTRAINT `agendamentos_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`),
  CONSTRAINT `agendamentos_ibfk_2` FOREIGN KEY (`id_servico`) REFERENCES `servicos` (`id_servico`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `avaliacoes` (
  `id_avaliacao` int(11) NOT NULL AUTO_INCREMENT,
  `id_agendamento` int(11) NOT NULL,
  `nota` int(11) NOT NULL,
  `comentario` text,
  PRIMARY KEY (`id_avaliacao`),
  KEY `id_agendamento` (`id_agendamento`),
  CONSTRAINT `avaliacoes_ibfk_1` FOREIGN KEY (`id_agendamento`) REFERENCES `agendamentos` (`id_agendamento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
