<?php
require_once "../../php/conexao.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["poster"])) {
    $pasta_destino = "../../img/filme/";
    if (!is_dir($pasta_destino)) {
        mkdir($pasta_destino, 0777, true);
    }

    $titulo = $_POST["titulo_filme"] ?? '';
    $classificacao = $_POST["classificacao_indicativa"] ?? '';
    $genero = $_POST["genero"] ?? '';
    $subgenero = $_POST["sub_genero"] ?? '';
    $duracao = (int)($_POST["duracao"] ?? 0);
    $sinopse = $_POST["sinopse"] ?? '';
    $trailer = $_POST["trailer"] ?? '';

    $nome_original = basename($_FILES["poster"]["name"]);
    $nome_arquivo = uniqid() . "_" . $nome_original;
    $caminho_completo = $pasta_destino . $nome_arquivo;

    if (move_uploaded_file($_FILES["poster"]["tmp_name"], $caminho_completo)) {
        $caminho_para_banco = "img/filme/" . $nome_arquivo;

        $stmt = $conexao->prepare("INSERT INTO filme (titulo, classificacao_indicativa, genero, sub_genero, duracao, sinopse, poster, trailer)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssisss", $titulo, $classificacao, $genero, $subgenero, $duracao, $sinopse, $caminho_para_banco, $trailer);

        if ($stmt->execute()) {
            // Redireciona com parâmetro success=1 para mostrar alerta
            header("Location: form_cadastro_filmes.html?success=1");
            exit;
        } else {
            echo "<p>❌ Erro ao salvar no banco: " . $stmt->error . "</p>";
        }
    } else {
        echo "<p>❌ Erro ao fazer upload do pôster.</p>";
    }
} else {
    echo "<p>❌ Formulário inválido.</p>";
}
?>
