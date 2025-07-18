<?php

$host = "localhost";
$usuario = "GB_database";
$senha_db = "0803";
$db = "cine_senac";

$conexao = mysqli_connect($host, $usuario, $senha_db, $db);

if (!$conexao) {
    die("Não foi possível conectar ao banco de dados. Erro detectado: " . mysqli_connect_error() . "<br>");
}


/*
echo "Conexão bem-secedida! <br>";
mysqli_set_charset($conexao, "utf8");


$criar_db_sql = "CREATE DATABASE IF NOT EXISTS " . $db;

if (mysqli_query($conexao, $criar_db_sql)) {
    echo "Banco de dados criado com sucesso!<br>";
} else {
    echo "Erro ao criar banco: " . mysqli_error($conexao);
}

mysqli_select_db($conexao, $db);
*/
