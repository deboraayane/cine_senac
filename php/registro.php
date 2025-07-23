<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nomeCompleto = trim($_POST["nomeCompleto"]);
    $email = trim($_POST["email"]);
    $cpf = trim($_POST["cpf"]); 
    $telefone = trim($_POST["telefone"]);
    $senha = trim($_POST["senha"]);
    $confirmarSenha = trim($_POST["confirmarSenha"]);


    if (empty($nomeCompleto) || empty($email) || empty($cpf) || empty($telefone) || empty($senha) || empty($confirmarSenha)) {
        echo "Preencha todos os campos corretamente!";
        exit;
    }
    
    if ($senha != $confirmarSenha) {
        echo "Senhas diferentes! Tente novamente.";
        exit;
    }

    require("conexao.php");

    if(!$conexao || mysqli_connect_error()) {
        die("Erro na conexão ocm o banco de dados: ". mysqli_connect_error());
    }   

    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);




    $inserir = "INSERT INTO usuario(cpf, nome, email, senha_hash,  telefone) VALUES ('$cpf', '$nomeCompleto', '$email', '$senhaHash', '$telefone')";

    if (mysqli_query($conexao, $inserir)) {
        echo "<br>Usuário cadastrado com sucesso!";
        header("Location: login.html");
        exit;
    } else {
        echo "<br>Erro ao cadastrar o usuário! ";
    }

    mysqli_close($conexao);
}