-- Script de atualização do banco de dados para o sistema de administração
USE doce_encanto;

-- Modificar tabela de produtos para adicionar campo esgotado
ALTER TABLE produtos 
ADD COLUMN esgotado BOOLEAN DEFAULT FALSE AFTER estoque;

-- Modificar tabela de pedidos para adicionar mais campos
ALTER TABLE pedidos 
MODIFY COLUMN status ENUM('novo', 'em_preparacao', 'enviado', 'entregue', 'cancelado') DEFAULT 'novo',
ADD COLUMN metodo_pagamento ENUM('cartao_debito', 'cartao_credito', 'pagar_entrega') DEFAULT 'pagar_entrega',
ADD COLUMN parcelas INT DEFAULT 1,
ADD COLUMN status_pagamento ENUM('aprovado', 'pendente', 'falhou') DEFAULT 'pendente',
ADD COLUMN endereco_entrega_id INT,
ADD COLUMN entregador_id INT,
ADD COLUMN nota_fiscal_enviada BOOLEAN DEFAULT FALSE,
ADD FOREIGN KEY (endereco_entrega_id) REFERENCES enderecos(id) ON DELETE SET NULL;

-- Tabela de histórico de status de pedidos
CREATE TABLE IF NOT EXISTS historico_status_pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT NOT NULL,
    status_anterior VARCHAR(50),
    status_novo VARCHAR(50) NOT NULL,
    alterado_por INT NOT NULL,
    data_alteracao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
    FOREIGN KEY (alterado_por) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Tabela de funcionários
CREATE TABLE IF NOT EXISTS funcionarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    celular VARCHAR(15) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    cep VARCHAR(10),
    rua VARCHAR(255),
    numero VARCHAR(10),
    bairro VARCHAR(100),
    cidade VARCHAR(100),
    estado VARCHAR(2),
    funcao ENUM('atendente', 'cozinha', 'caixa') NOT NULL,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ativo BOOLEAN DEFAULT TRUE
);

-- Tabela de entregadores
CREATE TABLE IF NOT EXISTS entregadores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    celular VARCHAR(15) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    cep VARCHAR(10),
    rua VARCHAR(255),
    numero VARCHAR(10),
    bairro VARCHAR(100),
    cidade VARCHAR(100),
    estado VARCHAR(2),
    veiculo ENUM('bicicleta', 'moto', 'carro') NOT NULL,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ativo BOOLEAN DEFAULT TRUE
);

-- Adicionar chave estrangeira para entregador em pedidos
ALTER TABLE pedidos 
ADD FOREIGN KEY (entregador_id) REFERENCES entregadores(id) ON DELETE SET NULL;

-- Tabela de log de ações administrativas
CREATE TABLE IF NOT EXISTS log_admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT NOT NULL,
    acao VARCHAR(255) NOT NULL,
    tabela VARCHAR(50),
    registro_id INT,
    detalhes TEXT,
    data_acao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Índices para melhorar performance
CREATE INDEX idx_pedidos_status ON pedidos(status);
CREATE INDEX idx_pedidos_data ON pedidos(data_pedido);
CREATE INDEX idx_produtos_categoria ON produtos(categoria);
CREATE INDEX idx_produtos_estoque ON produtos(estoque);
