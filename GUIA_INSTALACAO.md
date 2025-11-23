# Guia Rápido de Instalação - Doce Encanto Atualizado

## Passo 1: Substituir Arquivos

Extraia o arquivo `doce-encanto-atualizado.zip` e substitua todos os arquivos do projeto anterior.

## Passo 2: Atualizar Banco de Dados

Execute o seguinte comando SQL no seu banco de dados:

```sql
USE doce_encanto;

ALTER TABLE pedidos 
ADD COLUMN IF NOT EXISTS metodo_pagamento VARCHAR(50) DEFAULT 'Não informado';

UPDATE pedidos 
SET metodo_pagamento = 'Não informado' 
WHERE metodo_pagamento IS NULL OR metodo_pagamento = '';
```

**Alternativa**: Execute o arquivo `add_metodo_pagamento.sql` que está incluído no projeto:

```bash
mysql -u seu_usuario -p doce_encanto < add_metodo_pagamento.sql
```

## Passo 3: Configurar Permissões

Certifique-se de que o diretório de upload possui permissões adequadas:

```bash
chmod 755 assets/produtos
chown www-data:www-data assets/produtos
```

Ou, se necessário (menos seguro):

```bash
chmod 777 assets/produtos
```

## Passo 4: Testar o Sistema

### Teste de Imagens
1. Acesse a área administrativa (admin.php)
2. Vá em "Produtos" → "Novo Produto"
3. Preencha os dados e faça upload de uma imagem
4. Salve e verifique se a imagem aparece na lista
5. Acesse a página de bolos/doces como cliente e verifique se a imagem aparece

### Teste de Acessibilidade
1. Pressione a tecla Tab repetidamente
2. Verifique se todos os elementos interativos recebem foco visível (borda azul)
3. Pressione Enter/Space nos botões para ativá-los
4. Aumente o zoom do navegador para 200% e verifique se tudo continua legível

### Teste de Método de Pagamento
1. Faça um pedido como cliente
2. Acesse a área administrativa
3. Vá em "Pedidos" e verifique se a coluna "Método de Pagamento" aparece
4. Clique em "Ver detalhes" e verifique se o método aparece na página de detalhes

### Teste de Navegação Bidirecional
1. Acesse a área administrativa como admin
2. Clique no botão "Página Inicial" (ícone de casa) no header
3. Verifique se foi redirecionado para a página do usuário
4. Verifique se o botão com ícone de escudo aparece no header
5. Clique nele para voltar à área administrativa

## Passo 5: Pronto!

Seu sistema está atualizado com:
- ✅ Sistema de imagens funcionando corretamente
- ✅ Acessibilidade completa (WCAG 2.1 AA)
- ✅ Método de pagamento nos pedidos
- ✅ Navegação bidirecional entre admin e usuário

## Problemas Comuns

### Imagens não aparecem
- Verifique as permissões do diretório `assets/produtos`
- Verifique se o PHP tem permissão para mover arquivos
- Verifique os logs de erro do PHP

### Coluna metodo_pagamento não existe
- Execute o script SQL de atualização do banco de dados
- Verifique se está conectado ao banco correto

### Botão de admin não aparece
- Verifique se está logado como administrador
- Verifique se a sessão `$_SESSION['is_admin']` está definida como `true`

## Suporte

Para mais informações, consulte o arquivo `ALTERACOES_REALIZADAS.md` que contém a documentação completa de todas as alterações.
