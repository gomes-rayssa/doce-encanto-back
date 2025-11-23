-- Script para adicionar coluna de método de pagamento na tabela pedidos
USE doce_encanto;

-- Adicionar coluna metodo_pagamento se não existir
ALTER TABLE pedidos 
ADD COLUMN IF NOT EXISTS metodo_pagamento VARCHAR(50) DEFAULT 'Não informado';

-- Atualizar pedidos existentes
UPDATE pedidos 
SET metodo_pagamento = 'Não informado' 
WHERE metodo_pagamento IS NULL OR metodo_pagamento = '';
