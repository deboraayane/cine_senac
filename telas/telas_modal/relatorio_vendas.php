<?php
include_once '../../config.php'; // ajuste o caminho se necessário

$sql = "
    SELECT 
        s.id_sessao,
        f.titulo AS filme,
        COUNT(i.id_ingresso) AS total_ingressos,
        SUM(i.valor_total) AS total_arrecadado,
        MAX(i.data_venda) AS ultima_venda
    FROM ingresso i
    JOIN sessao s ON i.id_sessao = s.id_sessao
    JOIN filme f ON s.id_filme = f.id_filme
    GROUP BY s.id_sessao, f.titulo
    ORDER BY total_arrecadado DESC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Vendas</title>
    <style>
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { padding: 8px 12px; border: 1px solid #ccc; text-align: center; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Relatório de Vendas por Sessão</h1>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID Sessão</th>
                    <th>Filme</th>
                    <th>Total de Ingressos</th>
                    <th>Total Arrecadado (R$)</th>
                    <th>Última Venda</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id_sessao']; ?></td>
                        <td><?php echo htmlspecialchars($row['filme']); ?></td>
                        <td><?php echo $row['total_ingressos']; ?></td>
                        <td><?php echo number_format($row['total_arrecadado'], 2, ',', '.'); ?></td>
                        <td><?php echo $row['ultima_venda']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nenhuma venda registrada até o momento.</p>
    <?php endif; ?>

</body>
</html>

<?php
$conn->close();
?>
