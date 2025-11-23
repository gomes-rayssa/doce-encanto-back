Funcionalidades

O sistema Doce Encanto oferece um conjunto robusto de funcionalidades, divididas entre a área de e-commerce (loja) e o painel administrativo:

Categoria e Funcionalidades Principais
E-commerce (Loja):
Visualização de produtos (bolos e doces), carrinho de compras, cadastro e login de clientes, gestão de perfil do usuário, e finalização de pedidos.

Gestão de Produtos:
Cadastro, edição e listagem de produtos (com nome, descrição, preço, categoria, imagem e estoque).

Gestão de Pedidos:
Visualização detalhada de pedidos, atualização de status (Pendente, Em Preparação, Enviado, Entregue), e acompanhamento de entregadores.

Gestão de Clientes:
Listagem e visualização de detalhes dos clientes cadastrados.

Gestão de Equipe:
Cadastro e gerenciamento de funcionários e entregadores (com informações de contato, função e veículo).
A
dministração:
Painel de controle (admin.php), relatórios de vendas e dados (relatorios.php), e configurações gerais do sistema (configuracoes.php).


Tecnologias Utilizadas

Linguagem de Programação:
PHP - Linguagem principal para o back-end e lógica de aplicação.

Banco de Dados:
MySQL / MariaDB - Utilizado para persistência de dados (produtos, usuários, pedidos, etc.). A conexão é feita via extensão mysqli.

Front-end:
HTML5, CSS3, JavaScript - Utilizados para a estrutura, estilização e interatividade da interface do usuário.

Servidor Web:
Apache / Nginx - Necessário para interpretar o código PHP (geralmente em um ambiente como XAMPP, WAMP ou LAMP).


Instruções de Uso e Instalação
Para configurar e executar o projeto localmente, siga os passos abaixo:

1. Pré-requisitos

Você precisará de um ambiente de servidor web que suporte PHP e MySQL. Recomenda-se o uso de pacotes como XAMPP, WAMP ou MAMP.
•Servidor Web (Apache ou Nginx)

•PHP (versão compatível com mysqli)

•MySQL ou MariaDB

2. Configuração do Projeto

1.Clone o Repositório:

2.Configuração do Banco de Dados:
•Acesse o painel de administração do seu banco de dados (ex: phpMyAdmin).

•Crie um novo banco de dados chamado doce_encanto.

•Importe o arquivo doce_encanto_schema.sql para criar todas as tabelas e popular com dados iniciais (incluindo usuários administradores de teste).

3.Ajuste de Conexão:
•Edite o arquivo db_config.php e verifique se as credenciais de acesso ao banco de dados estão corretas para o seu ambiente local:


3. Execução
•Certifique-se de que os serviços Apache e MySQL estão em execução.

•Abra seu navegador e acesse o endereço do projeto no seu servidor local (ex: http://localhost/doce-encanto-back/ ).

4. Acesso Administrativo

Para acessar o painel de administração, utilize as credenciais de teste inseridas pelo script doce_encanto_schema.sql:

•URL de Login
http://localhost/doce-encanto-back/login.php

•E-mail
admin@doces.com

•Senha
password (hash padrão)

