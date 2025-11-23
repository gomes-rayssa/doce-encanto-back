USE doce_encanto;

ALTER TABLE pedidos 
ADD COLUMN IF NOT EXISTS metodo_pagamento VARCHAR(50) DEFAULT 'Não informado';

UPDATE pedidos 
SET metodo_pagamento = 'Não informado' 
WHERE metodo_pagamento IS NULL OR metodo_pagamento = '';
