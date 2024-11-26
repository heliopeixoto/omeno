<?php
include 'db.php';

$data = $_POST;
$login = $data['login'];
$senha = $data['senha'];
$empresa = $data['empresa'];
$categoria = $data['categoria'];

$sql = "INSERT INTO usuarios (login, senha, empresa, categoria) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $login, $senha, $empresa, $categoria);

if ($stmt->execute()) {
    echo "Usuário adicionado com sucesso.";
} else {
    echo "Erro: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
