<?php
$empresa = $_GET['empresa'];
$id = $_GET['id'];
?>

<div class="container mt-5" style="background: #fff; box-shadow: 0px 0px 10px rgba(0,0,0,0.9); padding: 15px; width: 400px; border-radius: 4px;">
	<i class="fa fa-times" style="float: right; cursor: pointer;" onclick="$('#carregador').hide();"></i>
    <h2>Editar Usuário</h2>
    <form id="form-editar-usuario">
        <input type="hidden" id="user_id" name="id">
        <div class="form-group">
            <label for="login">Login:</label>
            <input type="text" class="form-control" id="login2" name="login2" required>
        </div>
        <div class="form-group">
            <label for="senha">Senha:</label>
            <input type="text" class="form-control" id="senha2" name="senha2">
        </div>
        <div class="form-group">
            <label for="empresa">Empresa:</label>
            <input type="text" class="form-control" id="empresa2" name="empresa2" required>
        </div>
        <div class="form-group">
            <label for="categoria">Categoria:</label>
            <select class="form-control" id="categoria2" name="categoria2" required>
            	<option value="operador">Operador</option>
            	<option value="supervisor">Supervisor</option>
            </select>

        </div>
        <button type="submit" class="btn btn-primary">Salvar</button>
    </form>
</div>

<script type="text/javascript">
	    $('#form-editar-usuario').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: 'update_user.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                alert(response);                
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert('Erro ao salvar usuário.');
            }
        });
    	});
	function loadUserDetails(userId) {
        $.ajax({
            url: 'get_user.php',
            type: 'GET',
            data: { id: userId },
            success: function(response) {
                var user = JSON.parse(response);
                $('#user_id').val(user.id);
                $('#login2').val(user.login);
                $('#senha2').val(user.senha);
                $('#empresa2').val(user.empresa);
                $('#categoria2').val(user.categoria);
                // Não preenche o campo de senha para segurança
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert('Erro ao carregar os dados do usuário.');
            }
        });
    }
    loadUserDetails(<?php echo $id; ?>);
</script>
