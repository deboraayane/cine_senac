<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $senha_login = trim($_POST["senha"]);

    if (empty($email) || empty($senha_login)) {
        echo "Preencha todos os campos corretamente!";
        exit;
    }

    require("../../php/conexao.php");

    $query = "SELECT * FROM usuario WHERE email = ?";
    $stmt = mysqli_prepare($conexao, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    if ($usuario = mysqli_fetch_assoc($resultado)) {
        if (password_verify($senha_login, $usuario['senha_hash'])) {
            header("Location: /index.html");
            exit;
        }
    }

    mysqli_close($conexao);
    echo "E-mail ou senha incorretos.";
}
?>



