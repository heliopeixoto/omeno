<?php
include 'db.php';
$empresa = $_GET['empresa'];

$sql = "SELECT * FROM usuarios WHERE `empresa` = '".$empresa."' ";
$result = $conn->query($sql);

$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

echo json_encode($users);

$conn->close();
?>
