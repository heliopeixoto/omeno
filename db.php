<?php
//$servername = "omenoo.mysql.dbaas.com.br";
//$username = "omenoo";
//$password = "Pudim020202@";
//$dbname = "omenoo";

$servername = "localhost";
$username = "root";
$password = "shkt1217";
$dbname = "estoque";

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>
 