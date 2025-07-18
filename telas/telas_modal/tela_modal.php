<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tela do Administrador - Cinema</title>
    <link rel="stylesheet" href="style.css" />
</head>

<body>

    <h1>Bem Vindo, ao CineSenac!</h1>
    <h2>Você está na área do Administrador.</h2>

    <!-- Botões que abre os modais -->
    <div class="botoes-admin">
        <button id="cadastrarfilme">Cadastrar Filme</button>
        <button id="Verfilmes">Biblioteca de Filmes</button>
    </div>

    <!-- MODAL CADASTRAR/EDITAR FILME -->
    <div id="modalFilme" class="modal">
        <div class="modal-conteudo">
            <span class="fechar fechar-filme">&times;</span>
            <h1 id="titulo-modal-filme">Cadastro de Filmes</h1>
            <form method="post" action="http://localhost/telas/tela_cadastro_filme/form_cadastro_filme.php" enctype="multipart/form-data"
                id="form-filme">
                <label>
                    Título:
                    <input type="text" name="titulo_filme" placeholder="Escreva o título..." required />
                </label>

                <label>
                    Classificação indicativa:
                    <div class="select-wrapper">
                        <select name="classificacao_indicativa" required>
                            <option value="" disabled selected>Selecione...</option>
                            <option value="Livre">Livre</option>
                            <option value="10">10 anos</option>
                            <option value="12">12 anos</option>
                            <option value="14">14 anos</option>
                            <option value="16">16 anos</option>
                            <option value="18">18 anos</option>
                            <option value="Outro">Outro</option>
                        </select>
                    </div>
                </label>

                <label>
                    Gênero:
                    <div class="select-wrapper">
                        <select name="genero" required>
                            <option value="" disabled selected>Selecione...</option>
                            <option value="Ação">Ação</option>
                            <option value="Animação">Animação</option>
                            <option value="Comédia">Comédia</option>
                            <option value="Documentário">Documentário</option>
                            <option value="Drama">Drama</option>
                            <option value="Fantasia">Fantasia</option>
                            <option value="Ficção Científica">Ficção Científica</option>
                            <option value="Musical">Musical</option>
                            <option value="Romance">Romance</option>
                            <option value="Suspense">Suspense</option>
                            <option value="Terror">Terror</option>
                            <option value="Outro">Outro</option>
                        </select>
                    </div>
                </label>

                <label for="subgenero">Subgêneros:</label>
                <div class="custom-multiselect">
                    <div class="select-box" onclick="toggleCheckboxes()">
                        <span id="selected">Selecione os subgêneros</span>
                        <span class="arrow">&#9662;</span>
                    </div>
                    <div id="checkboxes" class="checkboxes">
                        <label><input type="checkbox" value="Policial/Investigativo" /> Policial/Investigativo</label>
                        <label><input type="checkbox" value="Romântica" /> Romântica</label>
                        <label><input type="checkbox" value="Cômica" /> Cômica</label>
                        <label><input type="checkbox" value="Histórica" /> Histórica</label>
                        <label><input type="checkbox" value="Fantástica" /> Fantástica</label>
                        <label><input type="checkbox" value="Psicológica" /> Psicológica</label>
                        <label><input type="checkbox" value="Sobrevivência" /> Sobrevivência</label>
                        <label><input type="checkbox" value="Musical" /> Musical</label>
                        <label><input type="checkbox" value="Super-heróis" /> Super-heróis</label>
                        <label><input type="checkbox" value="Biográfica / Realista" /> Biográfica / Realista</label>
                        <label><input type="checkbox" value="Histórico" /> Histórico</label>
                        <label><input type="checkbox" value="Outro" /> Outro</label>
                    </div>
                    <input type="hidden" name="sub_genero" id="sub_genero_input" />
                </div>

                <label>
                    Duração (Minutos):
                    <input type="number" name="duracao" placeholder="Duração em minutos" min="1" required />
                </label>

                <label>
                    Sinopse:
                    <textarea name="sinopse" rows="4" placeholder="Escreva a sinopse..." required></textarea>
                </label>

                <label>
                    Pôster:
                    <input type="file" name="poster" accept="image/*" required />
                </label>

                <label>
                    Trailer (URL):
                    <input type="url" name="trailer" placeholder="Link do trailer" />
                </label>

                <input type="submit" id="botao-enviar" value="Enviar" name="submit" />
            </form>
        </div>
    </div>

    <!-- MODAL BIBLIOTECA DE FILMES -->
    <div id="modalVerfilmes" class="modal">
        <div class="modal-conteudo">
            <span class="fechar fechar-Verfilmes">&times;</span>
            <h2>Biblioteca de Filmes</h2>

            <table>
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Gênero</th>
                        <th>Título</th>
                        <th>Classificação</th>
                        <th>Duração (min)</th>
                        <th>Opções</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Ação</td>
                        <td>Vingadores: Ultimato</td>
                        <td>12</td>
                        <td>181</td>
                        <td>
                            <button title="Editar">✏️</button>
                            <button title="Excluir">🗑️</button>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Comédia</td>
                        <td>As Branquelas</td>
                        <td>14</td>
                        <td>109</td>
                        <td>
                            <button title="Editar">✏️</button>
                            <button title="Excluir">🗑️</button>
                        </td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Drama</td>
                        <td>A Procura da Felicidade</td>
                        <td>Livre</td>
                        <td>117</td>
                        <td>
                            <button title="Editar">✏️</button>
                            <button title="Excluir">🗑️</button>
                        </td>
                    </tr>
                    <!-- Adicione mais linhas conforme necessário -->
                </tbody>
            </table>
        </div>
    </div>

    <script src="script.js"></script>
</body>

</html>