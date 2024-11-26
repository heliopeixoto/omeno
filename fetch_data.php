<?php
include 'db.php';
$empresa = $_GET['empresa'];


$search = isset($_GET['search']) ? $_GET['search'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$records_per_page = 10;
$offset = ($page - 1) * $records_per_page;

$sql_count = "SELECT COUNT(*) as total FROM estoque WHERE (nome LIKE '%$search%' OR codigo LIKE '%$search%') AND `empresa` = '".$empresa."' ";
$result_count = $conn->query($sql_count);
$total_records = $result_count->fetch_assoc()['total'];
$total_pages = ceil($total_records / $records_per_page);

$sql = "SELECT * FROM estoque WHERE (nome LIKE '%$search%' OR codigo LIKE '%$search%') AND `empresa` = '".$empresa."' LIMIT $offset, $records_per_page";

$result = $conn->query($sql);

$table = '';
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

        $quant = $row['qtd'];
        if($row['qtd'] == 0)
            $quant = '<b style=\'color: #d9534f; font-weight: bold; font-size: 16px;\'>Esgotado</b>';

        $table .= "<tr>            
            <td style='width: 60px;'><img src='{$row['img']}' alt='' width='50'></td>
            <td>{$row['nome']}</td>
            <td>{$row['codigo']}</td>
            <td>{$row['valor']}</td>
            <td>{$row['desc']}</td> 
            <td>{$quant}</td>
            <td>{$row['categoria']}</td>
            <td style='text-align: center;'>
                <a class='btn btn-info' onclick=\"edit('".$row['id']."')\">Editar <i class='fa fa-edit'></i></a> 
                <a class='btn btn-danger' onclick=\"delete_prod('".$row['id']."')\">Apagar <i class='fa fa-trash'></i></a>
            </td>            
        </tr>";
    }
} else {
    $table .= "<tr><td colspan='9'>Nenhum registro encontrado</td></tr>";
}

$pagination = "<center>";
for ($i = 1; $i <= $total_pages; $i++) {
    if($_GET['page'] == $i)
        $pagination .= "<a style='padding: 4px; border: 1px solid #ddd; background: #6198FF; color: #fff;' class='page-link' href='javascript:void(0);' data-page='$i'>$i</a>";
    else
        $pagination .= "<a style='padding: 4px; border: 1px solid #ddd; color: #808080;' class='page-link' href='javascript:void(0);' data-page='$i'>$i</a>";
}
$pagination .= "</center>";
$response = ['table' => $table, 'pagination' => $pagination];
echo json_encode($response);

$conn->close();
?>
