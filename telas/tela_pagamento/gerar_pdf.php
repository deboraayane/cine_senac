<?php
session_start();

if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['usuario_nome'])) {
    die("Usuário não autenticado.");
}

include '../../config.php';  // ajuste o caminho conforme necessário
require('fpdf/fpdf.php');
require('barcode.php');

// Captura dados do POST com validação básica
$nomeFilme = $_POST['nomeFilme'] ?? '';
$sala = $_POST['sala'] ?? 'Sala Desconhecida';
$data = $_POST['data'] ?? 'Data não informada';
$hora = $_POST['hora'] ?? 'Hora não informada';
$totalStr = $_POST['total'] ?? 'R$ 0,00';

// Limpa espaços e verifica nome do filme
$nomeFilme = trim($nomeFilme);
if (empty($nomeFilme)) {
    die("Nome do filme não foi informado.");
}

// Remove "R$" e converte valor para float
$valor_formatado = str_replace(['R$', ' '], '', $totalStr);
$valor_formatado = str_replace(',', '.', $valor_formatado);
$valor_total = (float) $valor_formatado;

// Dados do usuário
$id_usuario = $_SESSION['usuario_id'];
$usuario_nome = $_SESSION['usuario_nome'];
$data_venda = date("Y-m-d H:i:s");
$forma_pagamento = 'cartao'; // Ou pode pegar via POST se desejar

// Busca o id_filme pelo título
$sql_filme = "SELECT id_filme FROM filme WHERE titulo = ?";
$stmt = $conn->prepare($sql_filme);
if (!$stmt) {
    die("Erro na preparação da consulta: " . $conn->error);
}
$stmt->bind_param("s", $nomeFilme);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $id_filme = $row['id_filme'];
} else {
    die("Filme '{$nomeFilme}' não encontrado no banco de dados.");
}
$stmt->close();

// Insere a venda no banco
$sql_insert = "INSERT INTO venda (id_usuario, data_venda, valor_total, forma_pagamento, id_filme) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql_insert);
if (!$stmt) {
    die("Erro na preparação da inserção: " . $conn->error);
}
$stmt->bind_param('isdsi', $id_usuario, $data_venda, $valor_total, $forma_pagamento, $id_filme);
if (!$stmt->execute()) {
    die("Erro ao registrar venda: " . $stmt->error);
}
$stmt->close();

// Gerar PDF
ob_clean();

$pdf = new FPDF();
$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, utf8_decode('Comprovante de Pagamento'), 0, 1, 'C');

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 8, utf8_decode(str_repeat('-', 130)), 0, 1);
$pdf->Cell(0, 8, utf8_decode("Aqui está o seu ingresso, siga as orientações abaixo e bom filme!"), 0, 1, 'C');

$pdf->Cell(0, 8, utf8_decode("Filme: $nomeFilme"), 0, 1);
$pdf->Cell(70, 8, utf8_decode("Data: $data"), 0, 0, 'L');
$pdf->Cell(70, 8, utf8_decode("Horário: $hora"), 0, 0, 'L');
$pdf->Cell(50, 8, utf8_decode("Sala: $sala"), 0, 1, 'L');
$pdf->Cell(0, 8, utf8_decode("Cliente: $usuario_nome"), 0, 1);
$pdf->Cell(0, 8, utf8_decode("Valor Pago: $totalStr"), 0, 1);
$pdf->Cell(0, 8, utf8_decode(str_repeat('-', 130)), 0, 1);
$pdf->Cell(0, 8, utf8_decode("Status: Pagamento aprovado com sucesso!"), 0, 1, 'C');

$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 8, utf8_decode(" INSTRUÇÕES "), 0, 1, 'C');

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 8, utf8_decode("Apresente esse ingresso na entrada do CineSenac para validar seu acesso e bom filme para você."), 0, 1);
$pdf->Cell(0, 8, utf8_decode("Não esqueça de levar um documento com foto."), 0, 1);
$pdf->Cell(0, 8, utf8_decode("Obrigado por ser nosso Cliente."), 0, 1);
$pdf->Cell(0, 8, utf8_decode(str_repeat('-', 130)), 0, 1);

$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, utf8_decode("Código de Barras para verificação:"), 0, 1, 'C');

$codigo = strtoupper(substr(md5($nomeFilme . $sala . $totalStr), 0, 10));
barcode($pdf, 67, $pdf->GetY(), $codigo, 0.5, 20);
$pdf->Ln(25);
$pdf->Cell(0, 8, $codigo, 0, 1, 'C');

$pdf->Output('I', 'Ingresso.pdf');
