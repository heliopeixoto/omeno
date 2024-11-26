<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('db.php');

$empresa = $_GET['empresa'];
$trocado = $_GET['trocado'];
$valorPago = $_GET['valorPago'];

if(isset($_GET['operador']))
    $operador = $_GET['operador'];
else
    $operador = '';

// Verifica se a conexão foi estabelecida
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Recebe os dados via POST
$itens = isset($_POST['itens']) ? $_POST['itens'] : null;
$metodoPagamento = isset($_POST['metodoPagamento']) ? $_POST['metodoPagamento'] : null;
$total = isset($_POST['total']) ? $_POST['total'] : null;

// Verifica se os dados foram enviados corretamente
if (empty($itens) || empty($metodoPagamento) || empty($total) || empty($empresa)) {
    die("Dados incompletos fornecidos.");
}

// Decodifica o JSON
$itensArray = json_decode($itens, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    die("Erro ao decodificar JSON: " . json_last_error_msg());
}

// Atualiza o estoque para cada item
foreach ($itensArray as $item) {
    $id = $item['id'];
    $quantidadeVendida = $item['quantidade'];

    $sql_update = "UPDATE estoque SET qtd = qtd - ? WHERE codigo = ? AND empresa = ?";
    $stmt_update = $conn->prepare($sql_update);

    if (!$stmt_update) {
        die("Erro na preparação da query de atualização de estoque: " . $conn->error);
    }

    $stmt_update->bind_param("iis", $quantidadeVendida, $id, $empresa);

    if (!$stmt_update->execute()) {
        die("Erro ao atualizar o estoque: " . $stmt_update->error);
    }

    $stmt_update->close();
}

// Insere o registro na tabela financeiro
$sql_insert = "INSERT INTO financeiro (data_hora, itens, valor, metodo_pagamento, empresa, troco, pagoux, vendedor) VALUES (NOW(), ?, ?, ?, ?,?,?,?)";
$stmt_insert = $conn->prepare($sql_insert);

if (!$stmt_insert) {
    die("Erro na preparação da query de inserção financeira: " . $conn->error);
}

$itens_json = json_encode($itensArray); // Converter a lista de itens para JSON
$stmt_insert->bind_param("sdsssss", $itens_json, $total, $metodoPagamento, $empresa, $trocado, $valorPago, $operador);

if ($stmt_insert->execute()) {
    echo "Venda registrada e estoque atualizado com sucesso.";
} else {
    echo "Erro ao registrar a venda: " . $stmt_insert->error;
}

$stmt_insert->close();
$conn->close();
?>
