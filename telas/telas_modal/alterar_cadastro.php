<?php
require_once '../../php/conexao.php'; // ou o caminho correto para seu arquivo de conexão

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recebe os dados
    $id = $_POST['id_usuario'] ?? null;
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $filme = $_POST['filme'] ?? '';
    $tipo_exibicao = isset($_POST['tipo_exibicao']) ? implode(',', $_POST['tipo_exibicao']) : '';
    $periodo = $_POST['periodo'] ?? $_POST['periodo_perso'] ?? '';

    // Validação mínima
    if (!$id || empty($nome) || empty($email)) {
        die("Campos obrigatórios não preenchidos.");
    }

    // Atualiza no banco de dados
    try {
        $stmt = $pdo->prepare("UPDATE usuarios SET nome = :nome, email = :email, telefone = :telefone, filme_preferido = :filme, tipo_exibicao = :tipo_exibicao, periodo = :periodo WHERE id = :id");

        $stmt->execute([
            ':nome' => $nome,
            ':email' => $email,
            ':telefone' => $telefone,
            ':filme' => $filme,
            ':tipo_exibicao' => $tipo_exibicao,
            ':periodo' => $periodo,
            ':id' => $id
        ]);

        echo "Cadastro atualizado com sucesso!";
        // redirecionar ou retornar para a tela anterior, se necessário
    } catch (PDOException $e) {
        echo "Erro ao atualizar: " . $e->getMessage();
    }
}
?>
