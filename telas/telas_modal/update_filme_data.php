<?php
header('Content-Type: application/json');
include_once '../../config.php'; // Ajuste conforme a localização real

// Verifica se os dados foram enviados corretamente
if (!isset($_POST['id_filme'], $_POST['campo'], $_POST['valor'])) {
    echo json_encode(['success' => false, 'message' => 'Dados incompletos.']);
    exit;
}

$id = intval($_POST['id_filme']);
$campo = $_POST['campo'];
$valor = $_POST['valor'];

// Validação dos campos permitidos
$campos_permitidos = ['posicao', 'destaque'];
if (!in_array($campo, $campos_permitidos)) {
    echo json_encode(['success' => false, 'message' => 'Campo inválido.']);
    exit;
}

// Prepara a SQL dinamicamente e com segurança
$stmt = $conn->prepare("UPDATE filme SET $campo = ? WHERE id_filme = ?");
$stmt->bind_param("si", $valor, $id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => $stmt->error]);
}

$stmt->close();
$conn->close();

