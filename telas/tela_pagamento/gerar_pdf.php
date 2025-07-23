<?php
session_start();

if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['usuario_nome'])) {
    die("Usuário não autenticado.");
}

include '../../config.php';
require('fpdf/fpdf.php');
require('barcode.php');

// Captura os dados da venda
$id_usuario = $_SESSION['usuario_id'];
$usuario_nome = $_SESSION['usuario_nome'];
$data_venda = date("Y-m-d H:i:s");

$valor_formatado = str_replace(['R$', ','], ['', '.'], $_POST['total'] ?? '0');
$valor_total = (float) $valor_formatado;
$forma_pagamento = 'cartao'; // ou $_POST['forma_pagamento'] se estiver usando input do usuário
$id_filme = 5; // ou $_POST['id_filme'] se for dinâmico

// Insere a venda no banco
try {
    $sql = "INSERT INTO venda (id_usuario, data_venda, valor_total, forma_pagamento, id_filme)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('isdsi', $id_usuario, $data_venda, $valor_total, $forma_pagamento, $id_filme);
    $stmt->execute();
    $id_venda = $stmt->insert_id;
    $stmt->close();
} catch (mysqli_sql_exception $e) {
    die("Erro ao registrar venda: " . $e->getMessage());
}

// Captura dados do formulário para o PDF
$nomeFilme = $_POST['nomeFilme'] ?? 'Filme Desconhecido';
$sala = $_POST['sala'] ?? 'Sala Desconhecida';
$total = $_POST['total'] ?? 'R$ 0,00';
$data = $_POST['data'] ?? 'Data não informada';
$hora = $_POST['hora'] ?? 'Hora não informada';

// Inicia geração do PDF
ob_clean(); // limpa qualquer saída anterior

$pdf = new FPDF();
$pdf->AddPage();

// Título
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, utf8_decode('Comprovante de Pagamento'), 0, 1, 'C');

// Subtítulo
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 8, utf8_decode(str_repeat('-', 130)), 0, 1);
$pdf->Cell(0, 8, utf8_decode("Aqui está o seu ingresso, siga as orientações abaixo e bom filme!"), 0, 1, 'C');

// Informações principais
$pdf->Cell(0, 8, utf8_decode("Filme: $nomeFilme"), 0, 1);
$pdf->Cell(70, 8, utf8_decode("Data: $data"), 0, 0, 'L');
$pdf->Cell(70, 8, utf8_decode("Horário: $hora"), 0, 0, 'L');
$pdf->Cell(50, 8, utf8_decode("Sala: $sala"), 0, 1, 'L');
$pdf->Cell(0, 8, utf8_decode("Cliente: $usuario_nome"), 0, 1);
$pdf->Cell(0, 8, utf8_decode("Valor Pago: $total"), 0, 1);
$pdf->Cell(0, 8, utf8_decode(str_repeat('-', 130)), 0, 1);
$pdf->Cell(0, 8, utf8_decode("Status: Pagamento aprovado com sucesso!"), 0, 1, 'C');

// Instruções
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 8, utf8_decode(" INSTRUÇÕES "), 0, 1, 'C');

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 8, utf8_decode("Apresente esse ingresso na entrada do CineSenac para validar seu acesso e bom filme para você."), 0, 1);
$pdf->Cell(0, 8, utf8_decode("Não esqueça de levar um documento com foto."), 0, 1);
$pdf->Cell(0, 8, utf8_decode("Obrigado por ser nosso Cliente."), 0, 1);
$pdf->Cell(0, 8, utf8_decode(str_repeat('-', 130)), 0, 1);

// Código de barras
$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, utf8_decode("Código de Barras para verificação:"), 0, 1, 'C');

$codigo = strtoupper(substr(md5($nomeFilme . $sala . $total), 0, 10));
barcode($pdf, 67, $pdf->GetY(), $codigo, 0.5, 20);
$pdf->Ln(25);
$pdf->Cell(0, 8, $codigo, 0, 1, 'C');

// Exibe o PDF no navegador
$pdf->Output('I', 'Ingresso.pdf');
