<?php
// config.php
$servername = "localhost";
$username = "root"; // Altere para seu usuário do banco
$password = "";   // Altere para sua senha do banco
$dbname = "cine_senac"; // Altere para o nome do seu banco de dados

// Cria conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>