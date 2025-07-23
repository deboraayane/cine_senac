<?php
require_once '../../php/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cpf = $_POST['cpf_usuario'] ?? '';
    $novoTipo = $_POST['novo_tipo'] ?? '';

    if (!preg_match('/^\d{3}\.\d{3}\.\d{3}-\d{2}$/', $cpf)) {
        header("Location: tela_modal.php?mensagem=cpf_invalido");
        exit;
    }

    if (!in_array($novoTipo, ['cliente', 'admin'])) {
        header("Location: tela_modal.php?mensagem=tipo_invalido");
        exit;
    }

    $stmt = $conexao->prepare("SELECT id_usuario FROM usuario WHERE cpf = ?");
    $stmt->bind_param("s", $cpf);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        header("Location: tela_modal.php?mensagem=nao_encontrado");
        exit;
    }

    $stmt->close();

    $update = $conexao->prepare("UPDATE usuario SET tipo_usuario = ? WHERE cpf = ?");
    $update->bind_param("ss", $novoTipo, $cpf);

    if ($update->execute()) {
        header("Location: tela_modal.php?mensagem=sucesso");
        exit;
    } else {
        header("Location: tela_modal.php?mensagem=erro_atualizar");
        exit;
    }
}
?>
