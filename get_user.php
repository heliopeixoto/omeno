<?php
include 'db.php';

$id = $_GET['id'];

$sql = "SELECT * FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

echo json_encode($user);

$stmt->close();
$conn->close();
?>
