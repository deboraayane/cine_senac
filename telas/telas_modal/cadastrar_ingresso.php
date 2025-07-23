<?php
header('Content-Type: application/json');
include_once '../../config.php'; // Ajuste o caminho conforme seu projeto

// Verifica se os dados foram enviados corretamente
if (!isset($_POST['id_sessao'], $_POST['quantidade'], $_POST['valor_unitario'])) {
    echo json_encode(['success' => false, 'message' => 'Dados incompletos.']);
    exit;
}

$id_sessao = intval($_POST['id_sessao']);
$quantidade = intval($_POST['quantidade']);
$valor_unitario = floatval($_POST['valor_unitario']);
$data_venda = date('Y-m-d H:i:s');

// Calcula valor total
$valor_total = $quantidade * $valor_unitario;

// Insere os dados no banco
$stmt = $conn->prepare("INSERT INTO ingresso (id_sessao, quantidade, valor_unitario, valor_total, data_venda) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("iidds", $id_sessao, $quantidade, $valor_unitario, $valor_total, $data_venda);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Ingresso cadastrado com sucesso.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao cadastrar ingresso: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
