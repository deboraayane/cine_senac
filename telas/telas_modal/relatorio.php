<?php

header('Content-Type: text/html; charset=utf-8');

require('../tela_pagamento/fpdf/fpdf.php');
require('../tela_pagamento/barcode.php');
include_once '../../config.php';




//configurar a geração de relatorios de acordo com o recebido na tela_modal - relatorio



// Recebe dados do POST
$nomeFilme = $_POST['nomeFilme'] ?? 'Filme Desconhecido';
$sala = $_POST['sala'] ?? 'Sala Desconhecida';
$total = $_POST['total'] ?? 'R$ 0,00';
$data = $_POST['data'] ?? 'Data não informada';
$hora = $_POST['hora'] ?? 'Hora não informada';
$nomeCliente = $_POST['nomeCliente'] ?? 'Cliente não informado';

// Criar PDF
$pdf = new FPDF();

$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,utf8_decode('Comprovante de Pagamento'),0,1,'C');

$pdf->SetFont('Arial','',12);
$pdf->Cell(0,8,utf8_decode(str_repeat('-', 130)),0,1);
$pdf->Cell(0,8,utf8_decode("Aqui está o seu ingresso, siga as orientações abaixo e bom filme!"),0,1,'C');
$pdf->Cell(0,8,utf8_decode("Filme: $nomeFilme"),0,1);

$pdf->SetFont('Arial','',12);
// Define larguras proporcionais para as 3 células, totalizando a largura da página menos as margens
$largura_total = 80; // largura da página
$margem = 10; // margem esquerda/direita (aprox), ajusta se necessário
$largura_util = $largura_total - 2 * $margem; // largura útil para as células

$pdf->SetX($margem);
$pdf->Cell(70, 8, utf8_decode("Data: $data"), 0, 0, 'L');
$pdf->Cell(70, 8, utf8_decode("Horário: $hora"), 0, 0, 'L');
$pdf->Cell(70, 8, utf8_decode("Sala: $sala"), 0, 1, 'L'); // o último 1 no final força quebra de linha

$pdf->Cell(0,8,utf8_decode("Cliente: $nomeCliente"),0,1);
$pdf->Cell(0,8,utf8_decode("Valor Pago: $total"),0,1);
$pdf->Cell(0,8,utf8_decode(str_repeat('-', 130)),0,1);
$pdf->Cell(0,8,utf8_decode("Status: Pagamento aprovado com sucesso!"),0,1,'C');

$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,8,utf8_decode(" INSTRUÇÕES "),0,1,'C');
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,8,utf8_decode("Apresente esse ingresso na entrada do CineSenac para validar seu acesso e bom filme para você."),0,1);
$pdf->Cell(0,8,utf8_decode("Não esqueça de levar um documento com foto."),0,1);
$pdf->Cell(0,8,utf8_decode("Obrigado por ser nosso Cliente."),0,1);
$pdf->Cell(0,8,utf8_decode(str_repeat('-', 130)),0,1);

// Código de Barras
$pdf->Ln(10);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,8,utf8_decode("Código de Barras para verificação:"),0,1,'C');

$codigo = strtoupper(substr(md5($nomeFilme . $sala . $total), 0, 10)); // código único
barcode($pdf, 67, $pdf->GetY(), $codigo, 0.5, 20); // x, y, code, largura da barra, altura
$pdf->Ln(25);
$pdf->Cell(0,8,$codigo,0,1,'C');

// Saída do PDF
$pdf->Output('I', 'Ingresso.pdf');



