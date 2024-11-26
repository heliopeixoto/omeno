<?php
$empresa = $_GET['empresa'];

?>

<div style="float: left; width: 100%;"><b><i class="fa fa-cube"></i> Estoque</b>|<b style="color: #808080;">Menó</b></div>
<br>



<button id="addProductButton" style="float: right;" class="btn btn-info" onclick="inserir()">Adicionar Novo Produto <i class="fa fa-plus"></i></button>

<input type="text" id="search" class="form-control" style="margin-bottom: 12px; width: 500px; border-radius: 10px;" placeholder="Buscar produto...">

<div style="background: #fff; width: 100%; border: 1px solid #ddd; border-bottom: none;">    
    <div style="display: flex; background: #fff;">
        <div style=" width: 200px;">
            <img src="esgotado.png" style="height: 200px;" >
        </div>        
        <div id="low-stock-items" style="flex: 1">
        <!-- Os itens com estoque baixo serão carregados aqui -->
        </div>
    </div>
</div>
<table id="productTable" class="table table-bordered table-striped" style="background: #fff;">
    <thead>
        <tr>

            <th>Imagem</th>
            <th>Nome</th>
            <th>Código</th>
            <th>Valor</th>
            <th>Descrição</th>
            <th>Quantidade</th>
            <th>Categoria</th>
            <th style="text-align: center;">Ações</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>
<div id="pagination"></div>
<script>
    $(document).ready(function() {
        function loadData(page, search) {
            $.ajax({
                url: "fetch_data.php?empresa=<?php echo $empresa; ?>",
                type: "GET",
                data: { page: page, search: search },
                success: function(data) {
                    var response = JSON.parse(data);
                    $('#productTable tbody').html(response.table);
                    $('#pagination').html(response.pagination);
                }
            });
        }

        loadData(1, '');

        $(document).on('click', '.page-link', function() {
            var page = $(this).data('page');
            var search = $('#search').val();
            loadData(page, search);
        });

        $('#search').on('keyup', function() {
            var search = $(this).val();
            loadData(1, search);
        });
    });

loadLowStockItems();

    function loadLowStockItems() {
        $.ajax({
            url: 'get_low_stock.php',
            type: 'GET',
            data: { empresa: '<?php echo $empresa; ?>' },
            success: function(response) {
                var items = JSON.parse(response);
                var html = '<div style="background: #fff; padding: 15px;"><b>*Estoque Baixo* (5 produtos com estoque mais baixo)</b>';
                if (items.length > 0) {
                    items.forEach(function(item) {
                        var status = item.qtd == 0 ? '<b style="color: #d9534f">esgotado</b>' : item.qtd;
                        html += '<div style="padding: 5px;">' + item.nome + ': ' + status + '</div>';
                    });
                } else {
                    html += '<div class="list-group-item">Nenhum item com estoque baixo.</div>';
                }
                html += '</div>';
                $('#low-stock-items').html(html);
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert('Erro ao carregar os itens com estoque baixo.');
            }
        });
    }

</script>
