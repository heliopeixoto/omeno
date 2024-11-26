<?php
include 'db.php';

$id = $_GET['id'];

$sql = "DELETE FROM estoque WHERE id=$id";

if ($conn->query($sql) === TRUE) {
    echo "Registro apagado com sucesso";
} else {
    echo "Erro ao apagar registro: " . $conn->error;
}

$conn->close();
?>
