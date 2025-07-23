<?php
// Conexão com o banco
include_once '../../config.php';

// Verifica se os campos foram enviados
if (
    isset($_POST['filme']) &&
    isset($_POST['tipo_exibicao_ingresso']) &&
    isset($_POST['data_ingresso']) &&
    isset($_POST['horario_ingresso'])
) {
    $filme_id = intval($_POST['filme']);
    $data = $_POST['data_ingresso'];
    $horario = $_POST['horario_ingresso'];

    // Se múltiplos tipos de exibição forem selecionados
    $tipos = $_POST['tipo_exibicao_ingresso'];
    
    foreach ($tipos as $tipo) {
        $tipo = mysqli_real_escape_string($conn, $tipo);

        $sql = "INSERT INTO sessoes (filme_id, tipo_exibicao, data_sessao, horario_sessao)
                VALUES ('$filme_id', '$tipo', '$data', '$horario')";

        if (!mysqli_query($conn, $sql)) {
            echo "Erro ao cadastrar sessão: " . mysqli_error($conn);
            exit;
        }
    }
    echo "<script>alert('Sessão(ões) cadastrada(s) com sucesso!'); window.history.back();</script>";
   
    // Redireciona de volta ou exibe uma mensagem
     header("Location: tela_modal.php");
} else {
    echo "<script>alert('Campos obrigatórios não preenchidos.'); window.history.back();</script>";
}
?>
