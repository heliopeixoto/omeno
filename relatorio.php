<?php
$empresa = $_GET['empresa'];
include('db.php');

echo '<div style="float: left; width: 100%;"><b><i class="fa fa-book"></i> Relatório</b>|<b style="color: #808080;">Menó</b></div>
 <br><br>';
// Recebe as datas do formulário ou define datas padrão
$dataInicio = isset($_GET['data_inicio']) ? $_GET['data_inicio'] : date('Y-m-d');
$dataFim = isset($_GET['data_fim']) ? $_GET['data_fim'] : date('Y-m-d');

// Adiciona a hora final para buscar o final do dia
$dataFim = date('Y-m-d 23:59:59', strtotime($dataFim));

// Contar o total de registros
$sql_count = "SELECT COUNT(*) as total FROM financeiro WHERE empresa = ? AND data_hora BETWEEN ? AND ?";
$stmt_count = $conn->prepare($sql_count);
$stmt_count->bind_param("sss", $empresa, $dataInicio, $dataFim);
$stmt_count->execute();
$result_count = $stmt_count->get_result();
$row_count = $result_count->fetch_assoc();
$totalRecords = $row_count['total'];

// Consultar o total de valor do dia
$sql_total_valor = "SELECT SUM(valor) as total_valor FROM financeiro WHERE empresa = ? AND data_hora BETWEEN ? AND ?";
$stmt_total_valor = $conn->prepare($sql_total_valor);
$stmt_total_valor->bind_param("sss", $empresa, $dataInicio, $dataFim);
$stmt_total_valor->execute();
$result_total_valor = $stmt_total_valor->get_result();
$row_total_valor = $result_total_valor->fetch_assoc();
$totalValorDia = $row_total_valor['total_valor'];

// Consultar todos os itens vendidos no dia para PHP processar o item mais vendido
$sql_itens_vendidos = "
    SELECT itens FROM financeiro WHERE empresa = ? AND data_hora BETWEEN ? AND ?
";
$stmt_itens_vendidos = $conn->prepare($sql_itens_vendidos);
$stmt_itens_vendidos->bind_param("sss", $empresa, $dataInicio, $dataFim);
$stmt_itens_vendidos->execute();
$result_itens_vendidos = $stmt_itens_vendidos->get_result();

// Processar os itens vendidos para encontrar o item mais vendido
$itensVendidos = [];
while ($row = $result_itens_vendidos->fetch_assoc()) {
    $itens = json_decode($row['itens'], true);
    foreach ($itens as $item) {
        $nome = $item['nome'];
        $quantidade = $item['quantidade'];
        if (!isset($itensVendidos[$nome])) {
            $itensVendidos[$nome] = 0;
        }
        $itensVendidos[$nome] += $quantidade;
    }
}

$itemMaisVendido = array_search(max($itensVendidos), $itensVendidos) ?: 'Nenhum item vendido';

// Consultar total por método de pagamento
$sql_total_metodos = "
    SELECT metodo_pagamento, SUM(valor) as total
    FROM financeiro
    WHERE empresa = ? AND data_hora BETWEEN ? AND ?
    GROUP BY metodo_pagamento
";
$stmt_total_metodos = $conn->prepare($sql_total_metodos);
$stmt_total_metodos->bind_param("sss", $empresa, $dataInicio, $dataFim);
$stmt_total_metodos->execute();
$result_total_metodos = $stmt_total_metodos->get_result();

$totaisMetodosPagamento = [
    'debito' => 0,
    'credito' => 0,
    'dinheiro' => 0,
    'pix' => 0
];

while ($row = $result_total_metodos->fetch_assoc()) {
    $metodo = strtolower($row['metodo_pagamento']);
    if (isset($totaisMetodosPagamento[$metodo])) {
        $totaisMetodosPagamento[$metodo] = $row['total'];
    }
}

// Paginação
$limit = 10; // Número de registros por página
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;
$totalPages = ceil($totalRecords / $limit);

// Consulta os dados da tabela financeiro com base nas datas fornecidas
$sql = "SELECT * FROM financeiro WHERE empresa = ? AND data_hora BETWEEN ? AND ? ORDER BY data_hora DESC LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssii", $empresa, $dataInicio, $dataFim, $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();
?>

<!-- Formulário de Busca por Data -->
<div style="margin-bottom: 20px; width: 700px;">
    <table>
        <td>
            <label for="data_inicio">Data Início:&nbsp;</label>
        </td>
        <td>
            <input type="date" id="data_inicio" class="form-control" name="data_inicio" value="<?php echo $dataInicio; ?>">
        </td>
        <td>
            <label for="data_fim">&nbsp;Data Fim: &nbsp;</label>
        </td>
        <td>
            <input type="date" id="data_fim" class="form-control" name="data_fim" value="<?php echo date('Y-m-d', strtotime($dataFim)); ?>">
        </td>
        <td>
            &nbsp;&nbsp;<label class="btn btn-info" onclick="buscar();" >Buscar <i class="fa fa-search"></i></label>
        </td>
    </table>    
</div>

 <script>
        document.getElementById('imprimir').onclick = function() {
            var conteudo = document.getElementById('casca').innerHTML,
                tela_impressao = window.open('about:blank');

            tela_impressao.document.write(conteudo);
            tela_impressao.window.print();
            tela_impressao.window.close();
        };
        
</script>

<!-- Cabeçalho com Total do Dia e Item Mais Vendido -->
<img src="impirmir.png" style="cursor: pointer; height: 40px; float: right; margin: 10px;" id="imprimir" />
<div id="casca" style="width: 100%; background: #fff; padding: 10px; border: 1px solid #ddd; border-bottom: 0px;">
    <?php
        echo '<b>Data/Inicio: '. $dataInicio.' &nbsp;&nbsp;Data/Final: '.$dataFim.'</b><br>';    
    ?>
<div style="margin-bottom: 20px;">
    <b>Valor Total do Dia:</b> R$ <?php echo number_format($totalValorDia, 2, ',', '.'); ?><br>
    <b>Item Mais Vendido:</b> <?php echo htmlspecialchars($itemMaisVendido); ?>
</div>

<!-- Totais por Método de Pagamento -->

<div id="totais_pagamento" style="">
    <b>Total Débito:</b> R$ <?php echo number_format($totaisMetodosPagamento['debito'], 2, ',', '.'); ?><br>
    <b>Total Crédito:</b> R$ <?php echo number_format($totaisMetodosPagamento['credito'], 2, ',', '.'); ?><br>
    <b>Total Dinheiro:</b> R$ <?php echo number_format($totaisMetodosPagamento['dinheiro'], 2, ',', '.'); ?><br>
    <b>Total Pix:</b> R$ <?php echo number_format($totaisMetodosPagamento['pix'], 2, ',', '.'); ?>
</div>
</div>
<!-- Tabela de Relatórios -->

<table id="tabela-relatorio" border="1" class="table table-bordered table-striped" style="background: #fff;">
    <thead>
        <tr>
            <th>Data e Hora</th>
            <th>Operador</th>
            <th>Itens vendidos</th>
            <th>Valor</th>
            <th>Método de Pagamento</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $itens = json_decode($row['itens'], true);
                $itensFormatados = array_map(function($item) {
                    return $item['nome'] . ": R$ " . number_format($item['valor'], 2, ',', '.') . " | Quantidade: " . $item['quantidade'] . " | Total: R$ " . number_format($item['valorTotal'], 2, ',', '.');
                }, $itens);
                $itensFormatados = implode('<br>', $itensFormatados); // Usa <br> para separar os itens

                echo "<tr>";                
                echo "<td>" . date('d/m/Y H:i:s', strtotime($row['data_hora'])) . "</td>";
                echo "<td>" . $row['vendedor'] . "</td>";
                echo "<td>" . $itensFormatados . "</td>";
                echo "<td>R$ " . number_format($row['valor'], 2, ',', '.') . "</td>";
                echo "<td>";
                echo htmlspecialchars($row['metodo_pagamento']);
                if($row['troco'] != '') {
                    echo "&nbsp;(Pagou: ".$row['pagoux']."&nbsp;&nbsp;,Troco: ".$row['troco'].")";
                }
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>Nenhum registro encontrado.</td></tr>";
        }
        ?>
    </tbody>
</table>

<!-- Paginação -->
<center>
<div class="pagination">
    <?php
    for ($i = 1; $i <= $totalPages; $i++) {
        $active = ($i == $page) ? 'class="active"' : '';
        echo "<a href='#' data-page='$i' $active>$i</a> ";
    }
    ?>
</div>
</center>

<?php
$stmt->close();
$stmt_count->close();
$stmt_total_valor->close();
$stmt_itens_vendidos->close();
$stmt_total_metodos->close();
$conn->close();
?>

<style>
.pagination a {
    padding: 5px 10px;
    margin: 0 2px;
    border: 1px solid #ddd;
    text-decoration: none;
    color: #333;
}
.pagination a.active {
    background: #007bff;
    color: #fff;
}
</style>
<script type="text/javascript">
    function atualizarRelatorios(page) {
        var dataInicio = $("#data_inicio").val();
        var dataFim = $("#data_fim").val();
                       
        $('#corpo_geral').load('relatorio.php?empresa=<?php echo $empresa; ?>&data_inicio='+dataInicio+'&data_fim='+dataFim+'&page='+page);
    }

    function buscar() {
        atualizarRelatorios(1); // Atualiza a tabela com a página selecionada
    }   

    // Atualiza a tabela quando a página é carregada

    // Evento de clique na paginação
    /*$(document).on('click', '.pagination a', function(event) {
        event.preventDefault();
        var page = $(this).data('page'); // Obtém o número da página a partir do atributo data-page
        atualizarRelatorios(page); // Atualiza a tabela com a página selecionada
    });*/
</script>
