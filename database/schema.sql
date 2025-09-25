-- Criação do banco de dados
CREATE DATABASE IF NOT EXISTS procuradoria CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE procuradoria;

-- Tabela de usuários
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    nivel_acesso ENUM('admin', 'advogado') NOT NULL DEFAULT 'advogado',
    ativo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabela de pareceres
CREATE TABLE IF NOT EXISTS pareceres (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero_processo VARCHAR(50) NOT NULL,
    assunto VARCHAR(255) NOT NULL,
    interessado VARCHAR(100) NOT NULL,
    relator_id INT NOT NULL,
    data_entrada DATE NOT NULL,
    prazo_dias INT NOT NULL,
    data_limite DATE NOT NULL,
    status ENUM('pendente', 'em_analise', 'concluido') NOT NULL DEFAULT 'pendente',
    tipo ENUM('licitacao', 'administrativo') NOT NULL DEFAULT 'administrativo',
    parecer_texto TEXT,
    data_conclusao DATE,
    dias_atraso INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (relator_id) REFERENCES usuarios(id)
);

-- Inserir usuário admin padrão
INSERT INTO usuarios (nome, email, senha, nivel_acesso) VALUES
('Administrador', 'admin@procuradoria.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
-- Senha padrão: password