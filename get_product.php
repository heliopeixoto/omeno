<?php
include 'db.php';

$id = $_GET['id'];
$sql = "SELECT * FROM estoque WHERE id=$id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
    echo json_encode($product);
} else {
    echo json_encode([]);
}

$conn->close();
?>
