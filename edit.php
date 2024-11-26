<?php
include 'db.php';

$id = $_POST['id'];
$nome = $_POST['nome'];
$codigo = $_POST['codigo'];
$valor = str_replace(['.', ','], ['', '.'], $_POST['valor']);
$desc = $_POST['desc'];
$qtd = $_POST['qtd'];
$categoria = ''; //$_POST['categoria'] ?? '';

$sql = "SELECT `img` FROM estoque WHERE `id`=$id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$img = $row['img'];

// Tratamento do upload da imagem
if ($_FILES['img']['name']) {
    $img = 'uploads/' . basename($_FILES['img']['name']);
    move_uploaded_file($_FILES['img']['tmp_name'], $img);
}

$sql = "UPDATE estoque SET `img`='$img', `nome`='$nome', `codigo`='$codigo', `valor`='$valor', `desc`='$desc', `qtd`='$qtd', `categoria`='$categoria' WHERE `id`=$id";

if ($conn->query($sql) === TRUE) {
    echo "Registro atualizado com sucesso";
} else {
    echo "Erro ao atualizar registro: " . $conn->error;
}

$conn->close();
?>
