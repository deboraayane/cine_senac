<?php

header('Content-Type: text/html; charset=utf-8');

require('../tela_pagamento/fpdf/fpdf.php');
require('../tela_pagamento/barcode.php');
include_once '../../config.php';

//DEVIDO CONFLITODE INTERESSE DO CODIGO ACHEI MELHOR COLOCAR ESSE CODIGO AQUI E NAO EM CONFG.PHP
try {
    $conn = new PDO("mysql:host=localhost;dbname=cine_senac", "root", ""); // ← ajuste aqui
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}

// Captura dos dados
$filme = $_POST['filme'];
$id_filme = $_POST['filme'];
$tipos_exibicao = $_POST['tipo_exibicao_relatorio'] ?? [];
$periodo = $_POST['periodo'] ?? null;
$periodo_perso = $_POST['periodo_perso'] ?? null;

// Base da query
$query = "SELECT id_venda, id_usuario, data_venda, valor_total, forma_pagamento, id_filme 
          FROM venda 
          WHERE 1=1";

$params = [] ;
// Filtro por filme
if ($filme !== 'todos') {
    $query .= " AND id_filme = ?";
    $params[] = $filme;
}

// Filtro por tipo de exibição (2D/3D)
if (!empty($tipos_exibicao)) {
    $placeholders = implode(',', array_fill(0, count($tipos_exibicao), '?'));
    $query .= " AND tipo_exibicao IN ($placeholders)";
    $params = array_merge($params ?? [], $tipos_exibicao);
}

// Filtro por período
if ($periodo === '15dias') {
    $query .= " AND data_venda >= DATE_SUB(CURDATE(), INTERVAL 15 DAY)";
} elseif ($periodo === '30dias') {
    $query .= " AND data_venda >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
} elseif (!empty($periodo_perso)) {
    $query .= " AND DATE(data_venda) = ?";
    $params[] = $periodo_perso;
}

echo "<pre>$query</pre>";

// Preparar e executar
$stmt = $conn->prepare($query);
//$stmt->execute($params ?? []);

$stmt = $conn->prepare($query);

try {
    $stmt = $conn->prepare($query);
    $stmt->execute($params ?? []);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro no banco de dados: " . $e->getMessage();
    exit;
}

$stmt->execute($params ?? []);

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Classe personalizada
class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, 'Relatorio de Vendas', 0, 1, 'C');
        $this->Ln(5);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Página ' . $this->PageNo(), 0, 0, 'C');
    }
}

// Criar PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,utf8_decode('Relatório de Vendas'),0,1,'C');

$pdf->Cell(0,8,utf8_decode(str_repeat('-', 100)),0,1);

$pdf->SetFont('Arial','',12);

// Cabeçalho da tabela
$pdf->SetFillColor(200, 200, 200); // cinza claro
$pdf->Cell(25, 10, 'ID Venda', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'ID Usuario', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Data', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Valor', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Pagamento', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'ID Filme', 1, 1, 'C', true);

// Conteúdo
$pdf->SetFont('Arial', '', 10);

if (!empty($result)) {
    foreach ($result as $row) {
        $pdf->Cell(25, 8, $row['id_venda'], 1);
        $pdf->Cell(30, 8, $row['id_usuario'], 1);
        $pdf->Cell(30, 8, date('d/m/Y', strtotime($row['data_venda'])), 1);
        $pdf->Cell(30, 8, 'R$ ' . number_format($row['valor_total'], 2, ',', '.'), 1);
        $pdf->Cell(40, 8, $row['forma_pagamento'], 1);
        $pdf->Cell(30, 8, $row['id_filme'], 1);
        $pdf->Ln();
    }
} else {
    $pdf->Cell(0, 10, 'Nenhuma venda encontrada para os filtros selecionados.', 1, 1, 'C');
}
ob_clean(); // limpa tudo que foi enviado antes (se houver)
// Saída do PDF
$pdf->Output('I', 'relatorio_vendas.pdf'); // I = abre no navegador, D = força download
