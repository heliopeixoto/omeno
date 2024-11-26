<?php
$empresa = $_GET['empresa'];

?>

<div style="float: left; width: 100%;"><b><i class="fa fa-gears"></i> Configurações</b>|<b style="color: #808080;">Menó</b></div>
<br><br>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<meta charset="UTF-8">
	<title>Gerenciamento de Usuários</title>

</head>
<body>
	<div class="container mt-5">
		<h2>Gerenciamento de Usuários</h2>
		<form id="form-usuario">
			<input type="hidden" id="user_id" name="id">
			<div class="form-group">
				<label for="login">Login:</label>
				<input type="text" class="form-control" id="login" name="login" required>
			</div>
			<div class="form-group">
				<label for="senha">Senha:</label>
				<input type="password" class="form-control" id="senha" name="senha" required>
			</div>
			<div class="form-group" style="display:none;">
				<label for="empresa">Empresa:</label>
				<input type="text" value="<?php echo $empresa; ?>" class="form-control" id="empresa" name="empresa" required>
			</div>
			<div class="form-group">
				<label for="categoria">Categoria:</label>
				<select class="form-control" id="categoria" name="categoria" required>
					<option value="operador">Operador</option>
					<option value="supervisor">Supervisor</option>
				</select>
			</div>
			<button type="submit" class="btn btn-primary" id="btn-save">Adicionar</button>
		</form>

		<hr>
		<h3>Usuários Cadastrados</h3>
		<table class="table table-bordered" id="tabela-usuarios" style="background: #fff;">
			<thead>
				<tr>
					<th>Login</th>
					<th>Senha</th>
					<th>Empresa</th>
					<th>Categoria</th>
					<th style="text-align: center;">Ações</th>
				</tr>
			</thead>
			<tbody>
				<!-- Os dados serão preenchidos via jQuery AJAX -->
			</tbody>
		</table>
	</div>

	<script>
    function delete_user(id)
    {
    	if (confirm('Tem certeza que deseja excluir este usuário?')) 
    	{

    		$('#carregador').load('delete_user.php?id='+id);
    		config();
    	}
    }

    function edita_user(id)
    {
    	$('#carregador').load('box_edit.php?id='+id+'&empresa=<?php echo $empresa; ?>');
    	$('#carregador').show();
    }

		$(document).ready(function() {
    loadUsers(); // Carrega a lista de usuários ao iniciar

    // Adicionar ou Editar usuário
    $('#form-usuario').on('submit', function(e) {
    	e.preventDefault();
    	var formData = $(this).serialize();

    	var userId = $('#user_id').val();
    	var url = userId ? 'update_user.php' : 'add_user.php';
    	var method = userId ? 'PUT' : 'POST';

    	$.ajax({
    		url: url,
    		type: method,
    		data: formData,
    		success: function(response) {
    			alert(response);
    			$('#form-usuario')[0].reset();
    			$('#user_id').val('');
    			$('#btn-save').text('Adicionar');
    			loadUsers();
    		},
    		error: function(xhr, status, error) {
    			console.error(xhr.responseText);
    			alert('Erro ao salvar usuário.');
    		}
    	});
    });

    // Editar usuário
    $(document).on('click', '.btn-edit', function() {
    	var userId = $(this).data('id');
    	$.ajax({
    		url: 'get_user.php',
    		type: 'GET',
    		data: { id: userId },
    		success: function(response) {
    			var user = JSON.parse(response);
    			$('#user_id').val(user.id);
    			$('#login').val(user.login);
    			$('#senha').val(user.senha);
    			$('#empresa').val(user.empresa);
    			$('#categoria').val(user.categoria);
    			$('#btn-save').text('Atualizar');
    		},
    		error: function(xhr, status, error) {
    			console.error(xhr.responseText);
    			alert('Erro ao carregar os dados do usuário.');
    		}
    	});
    });

    // Deletar usuário

    
    // Função para carregar a lista de usuários
    function loadUsers() {
    	$.ajax({
    		url: 'list_users.php?empresa=<?php echo $empresa; ?>',
    		type: 'GET',
    		success: function(response) {
    			var users = JSON.parse(response);
    			var rows = '';
    			users.forEach(function(user) {
    				rows += '<tr>';
    				rows += '<td>' + user.login + '</td>';
    				rows += '<td>' + user.senha + '</td>';
    				rows += '<td>' + user.empresa + '</td>';
    				rows += '<td>' + user.categoria + '</td>';
    				rows += '<td style="text-align: center;"><button class="btn btn-warning" data-id="' + user.id + '" onclick="edita_user(\''+user.id+'\')">Editar</button> <button onclick="delete_user(\''+user.id+'\')" class="btn btn-danger btn-delete" data-id="' + user.id + '">Deletar</button></td>';
    				rows += '</tr>';
    			});
    			$('#tabela-usuarios tbody').html(rows);
    		},
    		error: function(xhr, status, error) {
    			console.error(xhr.responseText);
    			alert('Erro ao carregar a lista de usuários.');
    		}
    	});
    }
});
</script>
</body>
</html>
