-- Cria o banco de dados
CREATE DATABASE IF NOT EXISTS doce_encanto;

-- Seleciona o banco
USE doce_encanto;

-- Tabelas
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    celular VARCHAR(15),
    dataNascimento DATE,
    senha_hash VARCHAR(255) NOT NULL,
    isAdmin BOOLEAN DEFAULT FALSE,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10, 2) NOT NULL,
    categoria VARCHAR(50),
    imagem_url VARCHAR(255),
    estoque INT DEFAULT 0
);

CREATE TABLE IF NOT EXISTS enderecos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    cep VARCHAR(10) NOT NULL,
    rua VARCHAR(255) NOT NULL,
    numero VARCHAR(10) NOT NULL,
    bairro VARCHAR(100) NOT NULL,
    cidade VARCHAR(100) NOT NULL,
    estado VARCHAR(2) NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    data_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(50) DEFAULT 'Pendente',
    valor_total DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS itens_pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT,
    produto_id INT,
    quantidade INT NOT NULL,
    preco_unitario DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
    FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE RESTRICT
);

-- Usuário administrador
-- Senha: Doce2025@ (hash gerado com PASSWORD_DEFAULT)
INSERT INTO usuarios (nome, email, senha_hash, isAdmin) VALUES
('Admin Doce Encanto', 'admin@doceencanto.com', '$2y$10$9HgTp/YSAfS0SqqNraUideIBeICrYRAKTpMp5WMGyvHbQbsk/SKgm', TRUE); 

-- Exemplo
INSERT INTO produtos (nome, descricao, preco, categoria, estoque) VALUES
('Bolo de Chocolate', 'Delicioso bolo de chocolate com cobertura de brigadeiro.', 55.00, 'Bolos', 10),
('Brigadeiro Gourmet', 'Brigadeiro tradicional com granulado belga.', 3.50, 'Doces', 100),
('Trufa de Maracujá', 'Trufa recheada com mousse de maracujá.', 4.00, 'Doces', 50);
