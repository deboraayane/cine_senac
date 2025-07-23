<?php
// form_cadastro_filme.php
// Este arquivo processa o cadastro e a edição de filmes.

// Inclua o arquivo de conexão com o banco de dados.
// Certifique-se de que o caminho está correto em relação a este arquivo.
require_once "../../php/conexao.php"; // Ajuste se 'conexao.php' não estiver diretamente em ../../php/

// Defina o cabeçalho para indicar que a resposta será JSON.
header('Content-Type: application/json');

// Verifica se a requisição é POST.
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Recupera o ID do filme, se existir (para edição).
    // Se o ID não for fornecido ou for 0, consideramos que é um novo cadastro.
    $id_filme = isset($_POST["id_filme"]) ? (int)$_POST["id_filme"] : 0;

    // Recupera os dados do formulário, usando o operador de coalescência null (??) para evitar avisos de índice indefinido.
    $titulo = $_POST["titulo_filme"] ?? '';
    $classificacao = $_POST["classificacao_indicativa"] ?? '';
    $genero = $_POST["genero"] ?? '';
    $subgenero = $_POST["sub_genero"] ?? '';
    $duracao = (int)($_POST["duracao"] ?? 0);
    $sinopse = $_POST["sinopse"] ?? '';
    $trailer = $_POST["trailer"] ?? '';

    $caminho_para_banco = null; // Inicializa como nulo

    // Define o diretório de destino para os pôsteres.
    $pasta_destino = "../../img/filme/"; // Ajuste se 'img/filme/' não estiver em ../../img/filme/

    // Cria a pasta de destino se ela não existir.
    if (!is_dir($pasta_destino)) {
        if (!mkdir($pasta_destino, 0777, true)) {
            echo json_encode(['success' => false, 'message' => 'Erro: Não foi possível criar o diretório de upload.']);
            exit;
        }
    }

    // Lida com o upload do arquivo do pôster, se um novo arquivo for enviado.
    if (isset($_FILES["poster"]) && $_FILES["poster"]["error"] === UPLOAD_ERR_OK) {
        $nome_original = basename($_FILES["poster"]["name"]);
        // Gera um nome de arquivo único para evitar conflitos e aumentar a segurança.
        $nome_arquivo = uniqid() . "_" . $nome_original;
        $caminho_completo = $pasta_destino . $nome_arquivo;

        if (move_uploaded_file($_FILES["poster"]["tmp_name"], $caminho_completo)) {
            // Caminho que será salvo no banco de dados (relativo à raiz do seu site).
            $caminho_para_banco = "img/filme/" . $nome_arquivo;
        } else {
            // Se o upload falhar, retorna erro.
            echo json_encode(['success' => false, 'message' => 'Erro ao fazer upload do pôster.']);
            exit;
        }
    }

    // --- Lógica para EDIÇÃO ou CADASTRO ---
    if ($id_filme > 0) { // Se um ID de filme válido foi fornecido, é uma EDIÇÃO.
        $updates = [];
        $params = [];
        $types = ""; // String de tipos para bind_param

        // Adiciona campos para atualização apenas se eles forem fornecidos
        if (!empty($titulo)) { $updates[] = "titulo = ?"; $params[] = &$titulo; $types .= "s"; }
        if (!empty($classificacao)) { $updates[] = "classificacao_indicativa = ?"; $params[] = &$classificacao; $types .= "s"; }
        if (!empty($genero)) { $updates[] = "genero = ?"; $params[] = &$genero; $types .= "s"; }
        if (!empty($subgenero)) { $updates[] = "sub_genero = ?"; $params[] = &$subgenero; $types .= "s"; }
        if ($duracao > 0) { $updates[] = "duracao = ?"; $params[] = &$duracao; $types .= "i"; }
        if (!empty($sinopse)) { $updates[] = "sinopse = ?"; $params[] = &$sinopse; $types .= "s"; }
        if (!empty($trailer)) { $updates[] = "trailer = ?"; $params[] = &$trailer; $types .= "s"; }
        if ($caminho_para_banco !== null) { // Se um novo pôster foi enviado
            $updates[] = "poster = ?";
            $params[] = &$caminho_para_banco;
            $types .= "s";
        }

        // Se não houver nada para atualizar, retorna um erro.
        if (empty($updates)) {
            echo json_encode(['success' => false, 'message' => 'Nenhum dado fornecido para atualização.']);
            exit;
        }

        // Constrói a query de atualização dinamicamente.
        $sql = "UPDATE filme SET " . implode(", ", $updates) . " WHERE id_filme = ?";
        $params[] = &$id_filme; // Adiciona o ID do filme como último parâmetro
        $types .= "i"; // Tipo para o ID do filme

        $stmt = $conexao->prepare($sql);

        if (!$stmt) {
            echo json_encode(['success' => false, 'message' => 'Erro na preparação da consulta de edição: ' . $conexao->error]);
            exit;
        }

        // Binda os parâmetros dinamicamente.
        // Necessário criar referências para os parâmetros para call_user_func_array
        $bind_params = array_merge([$types], $params);
        $refs = [];
        foreach($bind_params as $key => $value) {
            $refs[$key] = &$bind_params[$key];
        }
        call_user_func_array([$stmt, 'bind_param'], $refs);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Filme atualizado com sucesso!', 'id_filme' => $id_filme]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao atualizar o filme: ' . $stmt->error]);
        }
        $stmt->close();

    } else { // Se não houver ID de filme ou for 0, é um NOVO CADASTRO.
        // Validação básica para garantir que um pôster foi enviado para um novo cadastro.
        if ($caminho_para_banco === null) {
            echo json_encode(['success' => false, 'message' => 'O pôster é obrigatório para novos filmes.']);
            exit;
        }

        $stmt = $conexao->prepare("INSERT INTO filme (titulo, classificacao_indicativa, genero, sub_genero, duracao, sinopse, poster, trailer)
                                   VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

        if (!$stmt) {
            echo json_encode(['success' => false, 'message' => 'Erro na preparação da consulta de cadastro: ' . $conexao->error]);
            exit;
        }

        // Binda os parâmetros para a inserção.
        $stmt->bind_param("ssssisss", $titulo, $classificacao, $genero, $subgenero, $duracao, $sinopse, $caminho_para_banco, $trailer);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Filme cadastrado com sucesso!', 'id_filme' => $conexao->insert_id]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao cadastrar o filme: ' . $stmt->error]);
        }
        $stmt->close();
    }

} else {
    // Se a requisição não for POST, ou não houver $_FILES["poster"] (para novos cadastros).
    echo json_encode(['success' => false, 'message' => 'Método de requisição inválido ou dados incompletos.']);
}

// Fechar a conexão com o banco de dados.
$conexao->close();
?>