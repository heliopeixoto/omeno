<?php
include 'db.php';

$term = $_GET['term'];
$empresa = $_GET['empresa'];
$query = "SELECT id, nome, codigo, valor, img, qtd FROM estoque WHERE (nome LIKE '%".$term."%' OR codigo LIKE '%".$term."%') AND empresa = '".$empresa."' ";
$result = $conn->query($query);

$produtos = [];
while ($row = $result->fetch_assoc()) {
    $produtos[] = $row;
}

echo json_encode($produtos);
?>
