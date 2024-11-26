<?php
include 'db.php';

$empresa = $_GET['empresa'];
$nome = $_POST['nome'];
$codigo = $_POST['codigo'];
$valor = str_replace(['.', ','], ['', '.'], $_POST['valor']);
$desc = $_POST['desc'];
$qtd = $_POST['qtd'];
$categoria = ''; //$_POST['categoria'] ?? '';

// Tratamento do upload da imagem
if ($_FILES['img']['name']) {
    $img = 'uploads/' . basename(uniqid().'_'.$_FILES['img']['name']);
    move_uploaded_file($_FILES['img']['tmp_name'], $img);
} else {
    $img = '';
}

// Verifica se já existe um produto com o mesmo código e empresa
$sql_check = "SELECT * FROM estoque WHERE codigo = ? AND empresa = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("ss", $codigo, $empresa);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    echo "Erro: Já existe um produto com o código $codigo";
} else {
    // Insere o novo produto
    $sql_insert = "INSERT INTO estoque (`img`, `nome`, `codigo`, `valor`, `desc`, `qtd`, `categoria`, `empresa`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("ssssisss", $img, $nome, $codigo, $valor, $desc, $qtd, $categoria, $empresa);

    if ($stmt_insert->execute()) {
        echo "Novo registro criado com sucesso";
    } else {
        echo "Erro: " . $stmt_insert->error;
    }

    $stmt_insert->close();
}

$stmt_check->close();
$conn->close();
?>
