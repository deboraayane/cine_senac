<?php
header('Content-Type: application/json');
include_once '../../config.php'; // ajuste o caminho conforme sua estrutura

// Verifica se o ID do filme foi enviado
if (!isset($_POST['id_filme'])) {
    echo json_encode(['success' => false, 'message' => 'ID do filme não fornecido.']);
    exit;
}

$id_filme = intval($_POST['id_filme']); // Garante que seja um número inteiro

// Prepara a exclusão com segurança
$stmt = $conn->prepare("DELETE FROM filme WHERE id_filme = ?");
$stmt->bind_param("i", $id_filme);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Filme não encontrado ou já excluído.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao excluir filme: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
