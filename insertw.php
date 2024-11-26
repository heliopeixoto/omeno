<?php
$empresa = $_GET['empresa'];

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Inserir Produto</title>
    <script>
$(document).ready(function() {
        $('#insereForm').on('submit', function(event) {
                event.preventDefault();
                let formData = new FormData(this);
                
                $.ajax({
                    url: 'insert.php?empresa=<?php echo $empresa; ?>',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        alert(response);

                        estoque();
                    },
                    error: function(response) {
                        alert('Erro ao atualizar produto');
                    }
                });
            });        
        });
        function formatMoney(input) {
            let value = input.value.replace(/\D/g, '');
            value = (value / 100).toFixed(2) + '';
            value = value.replace(".", ",");
            value = value.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
            input.value = value;
        }
    </script>
</head>
<body>
    <div style="background: #fff; padding: 15px; border-radius: 7px; box-shadow: 0px 0px 10px rgba(0,0,0,0.9);">
        <b>Add novo produto</b> <i onclick="$('#carregador, #tela_black').hide();" class="fa fa-times" style="float: right; font-size: 25px;"></i>
        <br><br>    
    <form id="insereForm" action="insert.php" method="post" enctype="multipart/form-data">
              <table class="table">
        <tr>
            <td>
                <label for="img">Imagem:</label>
            </td>
            <td>
                <input class="form-control" type="file" name="img" id="img">
            </td>
        </tr>
        <tr>
            <td>                
                <label for="nome">Nome:</label>
            </td>
            <td>
                <input class="form-control" type="text" name="nome" id="nome" required>
            </td>
        <tr>
            <td>
                <label for="codigo">Código:</label>
            </td>
            <td>
                <input class="form-control" type="text" name="codigo" id="codigo" required>
            </td>
        </tr>
        <tr>
            <td>
                <label for="valor">Valor:</label>
            </td>
            <td>
                <input class="form-control" type="text" name="valor" id="valor" required onkeyup="formatMoney(this)">
            </td>
        </tr>
        <tr>
            <td>
                <label for="desc">Descrição:</label>
            </td>
            <td>
                <input class="form-control" type="text" name="desc" id="desc" required>
            </td>
        </tr>
        <tr>
            <td>    
                <label for="qtd">Quantidade:</label>
            </td>
            <td>
                <input class="form-control" type="number" name="qtd" id="qtd" required>
            </td>
        </tr>
        <tr>
            <td>                
                <label for="categoria">Categoria:</label>
            </td>
            <td>
                <input class="form-control" type="text" name="categoria" id="categoria"><br>
            </td>
        </tr>
    </table>        
        <button class="btn btn-info btn-block" type="submit">Add novo produto <i class="fa fa-check"></i></button>
    </form>
    </div>
</body>
</html>
