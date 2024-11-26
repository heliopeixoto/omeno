<?php
include 'db.php';

$empresa = $_GET['empresa'];

$sql = "SELECT nome, qtd FROM estoque WHERE empresa = ? ORDER BY qtd ASC LIMIT 5";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $empresa);
$stmt->execute();
$result = $stmt->get_result();

$lowStockItems = [];
while ($row = $result->fetch_assoc()) {
    $lowStockItems[] = $row;
}

echo json_encode($lowStockItems);

$stmt->close();
$conn->close();
?>
