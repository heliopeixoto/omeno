# omeno
Menó Estoque - Um sistema de gerenciamento de estoque na nuvem, desenvolvido para simplificar o controle de estoque de empresas, com recursos como alertas de estoque baixo, gerenciamento de usuários e acompanhamento de vendas.
1. Introdução
1.1. Visão Geral
O Menó Estoque é um sistema de gerenciamento de estoque na nuvem, projetado para oferecer:

Controle eficiente de produtos,
Visualização em tempo real,
Uma solução ideal para pequenos e médios negócios.
1.2. Objetivos
🚀 Automatizar o controle de estoque.
🔔 Notificar produtos com estoque baixo.
✅ Simplificar adição, edição e exclusão de itens.
📊 Gerar relatórios de inventário detalhados.
💻 Oferecer uma interface amigável e intuitiva.
2. Funcionalidades do Sistema
2.1. Controle de Estoque
Listagem detalhada de produtos com:
📸 Imagem,
🔤 Nome,
🆔 Código,
💰 Valor,
✍️ Descrição,
📦 Quantidade,
🗂️ Categoria.
2.2. Monitoramento de Estoque Baixo
Exibição dos 5 produtos com menor quantidade diretamente na tela inicial.
2.3. Operações com Produtos
Adicionar Produto: Cadastro rápido de novos itens.
Editar Produto: Alteração de informações existentes.
Excluir Produto: Remoção de itens desnecessários.
2.4. Relatórios
Geração de relatórios personalizados baseados nas movimentações de estoque.
2.5. Suporte ao Usuário
Acesso a uma central de ajuda integrada para resolução de dúvidas.
3. Estrutura do Sistema
3.1. Tecnologias Utilizadas
Front-End:
HTML,
CSS,
JavaScript (design responsivo).
Back-End:
PHP 5,
MySQL (banco de dados).
Hospedagem:
Sistema na nuvem para acessibilidade remota.
4. Design da Interface
4.1. Barra Lateral (Menu)
📂 Acesso rápido às funcionalidades:
Estoque,
Iniciar Venda,
Relatórios,
Configurações,
Sair.
🛠️ Botão de suporte para contato direto com a central de ajuda.
4.2. Painel Principal
Exibição dos produtos cadastrados em formato de tabela com colunas para:
📸 Imagem,
🔤 Nome,
🆔 Código,
💰 Valor,
✍️ Descrição,
📦 Quantidade,
🗂️ Categoria,
⚙️ Ações.
5. Requisitos do Sistema
5.1. Requisitos Funcionais
Cadastro, edição e exclusão de produtos.
Notificações visuais para produtos com estoque baixo.
Relatórios de inventário.
5.2. Requisitos Não Funcionais
Interface amigável e de fácil navegação.
Acessibilidade remota via nuvem.
Segurança dos dados armazenados.
6. Estrutura do Banco de Dados
6.1. Tabela: Produtos
Campo	Tipo	Descrição
id	INT	Identificador único do produto.
imagem	VARCHAR(255)	Caminho para a imagem do produto.
nome	VARCHAR(100)	Nome do produto.
codigo	VARCHAR(50)	Código do produto.
valor	DECIMAL(10,2)	Preço do produto.
descricao	TEXT	Descrição detalhada do produto.
quantidade	INT	Quantidade disponível no estoque.
categoria	VARCHAR(100)	Categoria do produto.
7. Guia do Usuário
7.1. Adicionar Produto
Clique no botão "Adicionar Novo Produto".
Preencha os campos obrigatórios.
Clique em "Salvar".
7.2. Editar Produto
Clique no botão "Editar" ao lado do produto desejado.
Altere os dados necessários.
Clique em "Salvar".
7.3. Apagar Produto
Clique no botão "Apagar" ao lado do produto desejado.
Confirme a exclusão na janela de diálogo.
8. Manutenção e Atualizações
Backup Automático: Configuração de backups periódicos do banco de dados.
Monitoramento de Erros: Logs de erros para depuração.
Atualizações: Novos recursos implementados com base no feedback dos usuários.
9. Conclusão
O Menó Estoque é uma solução eficiente e escalável para o controle de estoque. Ele oferece recursos modernos e acessibilidade na nuvem, sendo ideal para empresas que desejam organização, praticidade e agilidade.
