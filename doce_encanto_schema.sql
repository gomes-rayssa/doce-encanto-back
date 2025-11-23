CREATE DATABASE IF NOT EXISTS doce_encanto;
USE doce_encanto;


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

CREATE TABLE IF NOT EXISTS equipe (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    telefone VARCHAR(15),
    endereco TEXT,
    tipo ENUM('funcionario', 'entregador') NOT NULL, 
    funcao VARCHAR(50), 
    veiculo_tipo VARCHAR(50), 
    placa VARCHAR(10), 
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS configuracoes (
    chave VARCHAR(100) PRIMARY KEY,
    valor TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS promocoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    desconto DECIMAL(5, 2) NOT NULL, 
    data_inicio DATE NOT NULL,
    data_termino DATE NOT NULL,
    status ENUM('Ativa', 'Inativa') DEFAULT 'Ativa'
);

ALTER TABLE pedidos
ADD COLUMN entregador_id INT NULL,
ADD FOREIGN KEY (entregador_id) REFERENCES equipe(id) ON DELETE SET NULL;


SET @admin_senha_hash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'; 

INSERT INTO usuarios (nome, email, senha_hash, isAdmin, data_cadastro) VALUES
('Admin Principal', 'admin@doces.com', @admin_senha_hash, TRUE, '2024-01-01 10:00:00'), 
('Gerente Secundário', 'gerente@doces.com', @admin_senha_hash, TRUE, '2024-02-15 10:00:00'); 

INSERT INTO produtos (nome, descricao, preco, categoria, estoque) VALUES;

INSERT INTO enderecos (usuario_id, cep, rua, numero, bairro, cidade, estado);


INSERT INTO pedidos (id, usuario_id, data_pedido, status, valor_total) 
FROM usuarios WHERE email ;
INSERT INTO itens_pedido (pedido_id, produto_id, quantidade, preco_unitario) VALUES; 

INSERT INTO equipe (nome, email, telefone, tipo, funcao, veiculo_tipo, placa, endereco) VALUES;

INSERT INTO configuracoes (chave, valor) VALUES
('store_name', 'Doces Encanto'),
('contact_email', 'contato@doceencanto.com'),
('phone', '(21) 99999-9999'),
('delivery_fee', '10.00'),
('store_address', 'Rio de Janeiro, RJ');

INSERT INTO promocoes (nome, desconto, data_inicio, data_termino, status) VALUES
('Black Friday', 30.00, '2024-11-01', '2024-11-30', 'Ativa');