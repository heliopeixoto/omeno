<?php
include 'db.php';

$data = $_POST;
$id = $data['id'];
$login = $data['login2'];
$senha = $data['senha2'];
$empresa = $data['empresa2'];
$categoria = $data['categoria2'];

//print_r($_POST);

$sql = "UPDATE usuarios SET login = ?, senha = ?, empresa = ?, categoria = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssi", $login, $senha, $empresa, $categoria, $id);

if ($stmt->execute()) {
    echo "Usuário atualizado com sucesso.";
} else {
    echo "Erro: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
