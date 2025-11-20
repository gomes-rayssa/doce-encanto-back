-- 1. Cria e Seleciona o Banco de Dados
CREATE DATABASE IF NOT EXISTS doce_encanto;
USE doce_encanto;

---------------------------------------------------
-- 2. TABELAS BASE EXISTENTES (doce_encanto_schema.sql)
---------------------------------------------------

-- Tabela de Usuários (Clientes e Administradores)
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

-- Tabela de Produtos
CREATE TABLE IF NOT EXISTS produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10, 2) NOT NULL,
    categoria VARCHAR(50),
    imagem_url VARCHAR(255),
    estoque INT DEFAULT 0
);

-- Tabela de Endereços
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

-- Tabela de Pedidos
CREATE TABLE IF NOT EXISTS pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    data_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(50) DEFAULT 'Pendente',
    valor_total DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Tabela de Itens do Pedido
CREATE TABLE IF NOT EXISTS itens_pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT,
    produto_id INT,
    quantidade INT NOT NULL,
    preco_unitario DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
    FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE RESTRICT
);

---------------------------------------------------
-- 3. TABELAS E MODIFICAÇÕES PARA O PAINEL ADMIN
---------------------------------------------------

-- Tabela para Funcionários (Entregadores e Equipe Interna) (Baseado em funcionarios.php)
CREATE TABLE IF NOT EXISTS equipe (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    telefone VARCHAR(15),
    endereco TEXT,
    tipo ENUM('funcionario', 'entregador') NOT NULL, -- 'funcionario' (cozinha, caixa) ou 'entregador'
    funcao VARCHAR(50), -- Para funcionários (e.g., Cozinha, Atendente)
    veiculo_tipo VARCHAR(50), -- Para entregadores (e.g., Moto, Carro, Bicicleta)
    placa VARCHAR(10), -- Para veículos motorizados
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de Configurações Gerais (Chave-Valor) (Baseado em configuracoes.php)
CREATE TABLE IF NOT EXISTS configuracoes (
    chave VARCHAR(100) PRIMARY KEY,
    valor TEXT NOT NULL
);

-- Tabela para Gerenciamento de Promoções (Baseado em configuracoes.php)
CREATE TABLE IF NOT EXISTS promocoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    desconto DECIMAL(5, 2) NOT NULL, -- Desconto em porcentagem
    data_inicio DATE NOT NULL,
    data_termino DATE NOT NULL,
    status ENUM('Ativa', 'Inativa') DEFAULT 'Ativa'
);

-- Modificação na tabela 'pedidos' para vincular um entregador (Baseado em pedido-detalhe.php)
ALTER TABLE pedidos
ADD COLUMN entregador_id INT NULL,
ADD FOREIGN KEY (entregador_id) REFERENCES equipe(id) ON DELETE SET NULL;


---------------------------------------------------
-- 4. DADOS INICIAIS DE DEMONSTRAÇÃO
---------------------------------------------------

-- Senha padrão para todos os admins: Doce2025@ (hash de exemplo)
SET @admin_senha_hash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'; 

-- Usuários (Admin e Cliente)
INSERT INTO usuarios (nome, email, senha_hash, isAdmin, data_cadastro) VALUES
('Admin Principal', 'admin@doces.com', @admin_senha_hash, TRUE, '2024-01-01 10:00:00'), -- Admin Principal
('Gerente Secundário', 'gerente@doces.com', @admin_senha_hash, TRUE, '2024-02-15 10:00:00'), -- Segundo Admin
('Maria Silva', 'maria@email.com', @admin_senha_hash, FALSE, '2024-01-15 14:00:00'), -- Cliente 1
('João Santos', 'joao@email.com', @admin_senha_hash, FALSE, '2024-02-20 16:00:00'); -- Cliente 2

-- Produtos (Baseado em produtos.php e doce_encanto_schema.sql)
INSERT INTO produtos (nome, descricao, preco, categoria, estoque) VALUES
('Bolo de Chocolate', 'Delicioso bolo de chocolate com cobertura de brigadeiro.', 55.00, 'Bolos', 10),
('Brigadeiro Gourmet', 'Brigadeiro tradicional com granulado belga.', 3.50, 'Doces', 150),
('Beijinho', 'Doce de coco tradicional.', 3.00, 'Doces', 200),
('Trufas Sortidas', 'Caixa com trufas de diversos sabores.', 45.00, 'Chocolates', 0),
('Cajuzinho', 'Doce de amendoim tradicional.', 3.50, 'Doces', 143),
('Brigadeiro de Colher', 'Brigadeiro cremoso para comer de colher.', 14.00, 'Doces', 129);

-- Endereço de exemplo para Maria Silva
INSERT INTO enderecos (usuario_id, cep, rua, numero, bairro, cidade, estado)
SELECT id, '12345-678', 'Rua das Flores', '123', 'Centro', 'São Paulo', 'SP'
FROM usuarios WHERE email = 'maria@email.com';

-- Pedidos de exemplo para Maria Silva (para aparecer em admin.php e cliente-detalhe.php)
-- ID #1234
INSERT INTO pedidos (id, usuario_id, data_pedido, status, valor_total) 
SELECT 1234, id, '2024-11-20 14:30:00', 'Novo', 156.90 FROM usuarios WHERE email = 'maria@email.com';
INSERT INTO itens_pedido (pedido_id, produto_id, quantidade, preco_unitario) VALUES
(1234, (SELECT id FROM produtos WHERE nome = 'Brigadeiro Gourmet'), 20, 3.50),
(1234, (SELECT id FROM produtos WHERE nome = 'Beijinho'), 15, 3.00),
(1234, (SELECT id FROM produtos WHERE nome = 'Cajuzinho'), 12, 3.49); -- Valor ajustado para totalizar R$ 156.90 (3.50*20 + 3.00*15 + 3.49*12)

-- Equipe (Funcionários e Entregadores) (Baseado em funcionarios.php)
INSERT INTO equipe (nome, email, telefone, tipo, funcao, veiculo_tipo, placa, endereco) VALUES
('Carlos Silva', 'carlos@email.com', '(11) 91234-5678', 'entregador', NULL, 'Moto', 'ABC-1234', 'Rua 1'),
('José Alves', 'jose@email.com', '(11) 91234-8765', 'entregador', NULL, 'Carro', 'XYZ-5678', 'Rua 2'),
('Ana Paula', 'ana.p@email.com', '(11) 91234-4321', 'entregador', NULL, 'Bicicleta', NULL, 'Rua 3'),
('Mariana Santos', 'mariana@email.com', '(11) 92345-6789', 'funcionario', 'Atendente', NULL, NULL, 'Rua 4'),
('Roberto Costa', 'roberto@email.com', '(11) 92345-9876', 'funcionario', 'Cozinha', NULL, NULL, 'Rua 5'),
('Paula Lima', 'paula@email.com', '(11) 92345-1234', 'funcionario', 'Caixa', NULL, NULL, 'Rua 6');

-- Configurações do Site (Baseado em configuracoes.php)
INSERT INTO configuracoes (chave, valor) VALUES
('store_name', 'Doces Artesanais'),
('contact_email', 'contato@doces.com'),
('phone', '(11) 3456-7890'),
('delivery_fee', '10.00'),
('store_address', 'Rua das Flores, 123 - Centro - São Paulo/SP - CEP: 12345-678');

-- Promoções (Baseado em configuracoes.php)
-- Ajuste as datas para o período desejado.
INSERT INTO promocoes (nome, desconto, data_inicio, data_termino, status) VALUES
('Black Friday', 30.00, '2024-11-01', '2024-11-30', 'Ativa');