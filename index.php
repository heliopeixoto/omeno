<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Lista de Produtos</title>
	<link rel="stylesheet" href="../zmonitor/awesome4.7/css/font-awesome.css" type="text/css" />
	<link rel="stylesheet" href="boots.min.css" type="text/css" />
	<script src="jquery-3.6.0.min.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
	<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">	
	<?php
	session_start(); // Inicia a sessão
	$email = $_POST['login'];
	$senha = $_POST['senha'];
	include('db.php');

	$sql = "SELECT * FROM usuarios WHERE login = '$email' AND senha = '$senha'";
	$result = $conn->query($sql);


	if ($result->num_rows == 0) 
	{

		header("Location: ../log.html");
		exit();
	}

	$row       = $result->fetch_assoc();
	$empresa   = $row['empresa'];
	$categoria = $row['categoria'];	

	$_SESSION['empresa'] = $empresa;
	$_SESSION['categoria'] = $categoria;
	$_SESSION['login'] = $email;

	if ($categoria == 'operador') {
		header("Location: vendas_operador.php");
		exit();
	}


	?>
	<input type="hidden" id="empresa" value="<?php echo $empresa; ?>" >
	<style>
		#addProductButton {
			margin-bottom: 20px;
		}
		body {
			font-family: Arial, sans-serif;
			background-color: #f4f4f4;
			color: #333;
			margin: 0;
			padding: 0;
		}
		.sidebar {
			background-color: #25395C;
			color: white;
			width: 250px;
			height: 100vh;
			position: fixed; 
			border-radius: 10px 10px 10px 0px;           
		}
		.sidebar a {
			color: white;
			display: block;
			padding: 10px 20px;
			text-decoration: none;
		}
		.sidebar a:hover {
			background-color: #34495e;
		}
		.sidebar .nav-header {
			font-size: 18px;
			margin-bottom: 10px;
			padding-left: 20px;
		}
		.content {
			margin-left: 270px;
			padding: 20px;
		}
		.btn-success {
			background-color: #28a745;
			border: none;
			color: white;
			padding: 10px 20px;
			cursor: pointer;
		}
		.btn-success:hover {
			background-color: #218838;
		}
		.help-box {
			background: url('prints.png') #ecf0f1;
			padding: 10px;
			border-radius: 5px;
			margin: 20px;
			position: absolute;
			bottom: 0;
			color: #25395C;
		}


		#tela_black {
			position   : absolute;
			top        : 0;
			left       : 0;
			width      : 100%;
			height     : 100%;
			background : #000;
			opacity    : 0.6;
			filter     : alpha(opacity=60);
			z-index    : 5;
			display    : none;
			z-index    : 9997;
			overflow   : hidden; 
		}


		::-webkit-scrollbar {width: 5px; height: 5px;}
		::-webkit-scrollbar-track {background: transparent;}
		::-webkit-scrollbar-thumb {background: #6198FF;}

		.scrollbar{
			background-color: #d7e5e8;
			overflow-y:scroll;
		}

	</style>

	<script type="text/javascript">
		function inserir()
		{
			var empresa = $('#empresa').val();
			$('#carregador').load('insertw.php?empresa='+empresa);
			$('#carregador, #tela_black').show();
		}

		function edit(ids)
		{
			$('#carregador').load('editw.php?id='+ids);
			$('#carregador, #tela_black').show();
		}


		function delete_prod(id)
		{
			if (confirm('Tem certeza que deseja excluir este produto?')) 
			{

				$('#carregador').load('delete.php?id='+id);
				estoque();
			}
		}


		function estoque()
		{
			var empresa = $('#empresa').val();
			$('#corpo_geral').load('estoque.php?empresa='+empresa);
		}

		function relatorio()
		{
			var empresa = $('#empresa').val();
			$('#corpo_geral').load('relatorio.php?empresa='+empresa);
		}

		function config()
		{
			var empresa = $('#empresa').val();
			$('#corpo_geral').load('config.php?empresa='+empresa);
		}

		function venda()
		{
			var empresa = $('#empresa').val();
			$('#carregador').load('venda.php?empresa='+empresa);
			$('#carregador, #tela_black').show();
		}		

	</script>
</head>

</head>
<div id="tela_black"></div>
<div id="carregador" style="position: fixed; z-index: 999; margin-left: 20%; margin-top: 60px; z-index: 9998;"></div>
<body>
	<div class="sidebar">
		<div class="nav-header" style="background: #42597D; padding: 20px; border-radius: 0px 10px 10px 0px">
			<center>
				<img src="meno.png" style="height: 140px; border-radius: 50%; padding: 10px; background: #fff;" />
			</center>
		</div>
		<br>
		<a href="#" onclick="estoque();"><i class="fa fa-cube"></i> Estoque</a>
		<a href="#" onclick="venda();"><i class="fa fa-shopping-cart"></i> Iniciar Venda</a>
		<a href="#" onclick="relatorio();"><i class="fa fa-book"></i> Relatórios</a>
		<a href="#" onclick="config();"><i class="fa fa-gears"></i> Configurações</a>		
		<a href="../log.html"><i class="fa fa-sign-out-alt"></i> Sair</a>
		<div class="help-box">
			<p><i class="fa fa-question-circle"></i> Central de ajuda</p>
			<p>Havendo qualquer anormalidade, por favor contacte o suporte.</p>
			<button class="btn btn-info btn-block"><i class="fa fa-comments"></i> Contatar Suporte</button>
		</div>
	</div>
	<div class="content" id="corpo_geral">
		<h1>Bem-vindo ao <b style="color: #25395C;">Menó|Estoque</b></h1>
		<p>Selecione uma opção no menu para começar.</p>
		<center>
			<img src="3self.png"  style="width: 50%;" />
		</center>

	</div>
</body>
</html>



<body>

</body>
</html>

<script>
	$(document).ready(function() {
        // Função para carregar a página de relatórios

        
        // Função para atualizar os relatórios
        
        // Evento de submit do formulário


        // Evento de clique na paginação
        $(document).on('click', '.pagination a', function(event) {
            var page = $(this).data('page'); // Obtém o número da página a partir do atributo data-page
            atualizarRelatorios(page); // Atualiza a tabela com a página selecionada
        });

        // Carregar a página inicial com os dados padrão
        
    });
</script>
</body>
</html>
