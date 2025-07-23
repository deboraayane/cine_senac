<?php
// Inclua o arquivo de configuração do banco de dados
// Ajuste o caminho conforme a localização do seu 'config.php' em relação a este arquivo
// Ex: se admin.php está em 'telas/admin/' e config.php em 'config/', então o caminho é '../../config.php'
include_once '../../config.php';

// Consulta para buscar TODOS os filmes, incluindo 'em_destaque' e 'posicao_banner'
// Certifique-se de que suas colunas no banco de dados são 'destaque' e 'posicao'
$sql_todos_filmes = "SELECT id_filme, titulo, classificacao_indicativa, genero, sub_genero, duracao, destaque, posicao FROM filme ORDER BY id_filme ASC";
$result_todos_filmes = $conn->query($sql_todos_filmes);

$sql_todas_salas = "SELECT id_sala, nome FROM sala ORDER BY id_sala ASC";
$result_todas_salas = $conn->query($sql_todas_salas);

$filmes_biblioteca = [];
    if ($result_todos_filmes->num_rows > 0) {
        while($row = $result_todos_filmes->fetch_assoc()) {
            $filmes_biblioteca[] = $row;
        }
    }

$salas_biblioteca = [];
    if ($result_todas_salas->num_rows > 0) {
        while($row = $result_todas_salas->fetch_assoc()) {
            $salas_biblioteca[] = $row;
        }
}

$conn->close(); // Feche a conexão após buscar os dados para a página
?>
<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tela do Administrador - Cinema</title>
    <link rel="stylesheet" href="style.css" />
    <link rel="icon" href="\img/favicon.png" type="image/png">
</head>

<body>
    <div class="logo-container">
        <a href="../../index.php">
        <img src="\img/logo-cine-senac-c.png" alt="CineSenac" class="logo-pequena" />
      </a>
    </div>

    <h1>Bem Vindo, ao CineSenac!</h1>
    <h2>Você está na área do Administrador.</h2>

    <div class="botoes-admin">
        <button id="cadastrarfilme">Cadastrar Filme</button>
        <button id="Verfilmes">Biblioteca de Filmes</button>
        <button id="cadastrarsessao">Cadastrar Sessão</button>
        <button id="cadastraringresso">Cadastrar Ingresso</button>
        <button id="relatoriovendas">Relatório de Vendas</button>
        <button id="alterarcadastro">Alterar Cadastro</button>
        <a href="\index.php" class="btn-voltar">Voltar a tela inicial</a>
        <a href="/php/logout.php" class="btn-sair">Sair</a>
<style>
.btn-voltar {
  display: inline-block;
  padding: 7px ;
  background-color: #e6762f;
  color: white;
  text-decoration: none;
  border-radius: 5px;
}
</style>
       

<style>
.btn-sair {
  display: inline-block;
  padding: 7px ;
  background-color: #e6762f;
  color: white;
  text-decoration: none;
  border-radius: 5px;
}
</style>
    </div>

    <div id="modalFilme" class="modal">
        <div class="modal-conteudo">
            <span class="fechar fechar-filme">&times;</span>
            <h1 id="titulo-modal-filme">Cadastro de Filmes</h1>
            <form method="post" action="http://localhost/PROJETO/form_cadastro_filmes.php" enctype="multipart/form-data"
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

<!-- BIBLIOTECA DE FILMES-->
    <div id="modalVerFilmes" class="modal">
        <div class="modal-conteudo">
            <span class="fechar fechar-Verfilmes">&times;</span>
            <h2>Biblioteca de Filmes</h2>

            <table>
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Título</th>
                        <th>Classificação</th>
                        <th>Gênero</th>
                        <th>Subgênero</th>
                        <th>Duração (min)</th>
                        <th>Em Destaque</th>
                        <th>Posição Poster</th>
                        <th>Opções</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($filmes_biblioteca)): ?>
                        <tr>
                            <td colspan="9">Nenhum filme cadastrado.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($filmes_biblioteca as $filme): ?>
                            <tr>
                                <td><?php echo $filme['id_filme']; ?></td>
                                <td><?php echo htmlspecialchars($filme['titulo']); ?></td>
                                <td><?php echo htmlspecialchars($filme['classificacao_indicativa']); ?></td>
                                <td><?php echo htmlspecialchars($filme['genero']); ?></td>
                                <td><?php echo htmlspecialchars($filme['sub_genero']); ?></td>
                                <td><?php echo htmlspecialchars($filme['duracao']); ?></td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" class="toggle-status" <?php echo ($filme['destaque'] == 1) ? 'checked' : ''; ?>
                                               data-filme-id="<?php echo $filme['id_filme']; ?>"
                                               onchange="toggleDestaque(this)">
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <div class="contador">
                                        <button onclick="alterarPosicao(<?php echo $filme['id_filme']; ?>, this, -1)">-</button>
                                        <span class="valor" id="posicao-<?php echo $filme['id_filme']; ?>"><?php echo htmlspecialchars($filme['posicao']); ?></span>
                                        <button onclick="alterarPosicao(<?php echo $filme['id_filme']; ?>, this, 1)">+</button>
                                    </div>
                                </td>
                                <td>
                                    <button class="btn-editar" data-id="<?php echo $filme['id_filme']; ?>" title="Editar">✏️</button>
                                    <button class="btn-excluir" data-id="<?php echo $filme['id_filme']; ?>" title="Excluir">🗑️</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="modalConfirmarExclusao" class="modal">
        <div class="modal-conteudo">
            <p>Tem certeza que deseja excluir este item?</p>
            <div style="margin-top: 20px;">
                <button id="confirmarExcluir">Confirmar</button>
                <button id="cancelarExcluir">Cancelar</button>
            </div>
        </div>
    </div>

<!-- CADASTRO DE SESSÃO -->

    <div id="modalSessao" class="modal">
      <div class="modal-conteudo">
        <span class="fechar fechar-sessao">&times;</span>
         <h2>Cadastrar Sessão</h2>

        <form method="post" action="form_cadastro_sessao.php">
          <label for="filme">
            Filme:
            
              <div class="select-wrapper">
            <select name="filme" id="filme" required>
            <option value="" disabled selected>Selecione um filme...</option>
            <?php 
            if (!empty($filmes_biblioteca)) {
                foreach ($filmes_biblioteca as $filme) {
                    echo '<option value="' . $filme['id'] . '">' . htmlspecialchars($filme['titulo']) . '</option>';
                }
            } else {
                echo '<option value="">Nenhum filme cadastrado</option>';
            }
            ?>
            </select>
            </div>
          </label>
<br>
 
            <label for="sala">Sala:
                <select name="sala" id="sala" required>
                <option value="" disabled selected>Selecione uma sala...</option>
                <?php 
                    if (!empty($salas_biblioteca)){
                        foreach ($salas_biblioteca as $sala) {
                            echo '<option value="' . $sala['id_sala'] . '">' . htmlspecialchars($sala['nome']) . '</option>';
                    }
                }
                ?>
                </select>
            </label><br><br>

            <label for="tipo_exibicao_sessao">Tipo de Exibição:</label><br><!-- adicionou o br  -->
            
            <label>
                <br><!-- adicionou o br  -->
                <input
                type="checkbox"
                name="tipo_exibicao_sessao[]"
                value="2D"
                />
                2D
            </label>
            <label>
                <input
                type="checkbox"
                name="tipo_exibicao_sessao[]"
                value="3D"
                />
                3D
            </label>

          <h4 style="margin-top: 30px;">Selecione uma data e horário</h4>
          <div style="display:flex;align-items:center;gap:10px;margin-bottom:20px;">
            <button id="btnAnterior-sessao"
            style="padding:6px 12px;background:#444;color:white;border:none;border-radius:5px;cursor:pointer;">&larr;
            Anterior</button>
            <h3 id="mesAnoAtual-sessao" style="margin:0;"></h3>
            <button id="btnProximo-sessao"
            style="padding:6px 12px;background:#444;color:white;border:none;border-radius:5px;cursor:pointer;">Próximo
            &rarr;</button>
          </div>
          <div id="calendario-sessao" style="display:flex;flex-wrap:wrap; align-items:center; gap:10px;"></div>
          <h4 id="info-sessao"></h4>
          <div id="horarios-sessao" style="display:flex;flex-wrap:wrap;gap:10px;"></div>
          <input type="hidden" name="data_sessao" id="dataSessaoSelecionada">
          <input type="hidden" name="horario_sessao" id="horarioSessaoSelecionado">

          

          <input type="submit" value="Cadastrar Sessão"> </form>
      </div>
    </div>


        <!-- CADASTRO DE INGRESSO -->
    <div id="modalIngresso" class="modal">
      <div class="modal-conteudo">
        <span class="fechar fechar-ingresso">&times;</span>
        <h2>Cadastrar Ingresso</h2>

        <form method="post" action="form_cadastro_ingresso.php">
          <label for="filme">   
            Filme:
            <select name="filme" id="filme" required>
            <option value="" disabled selected>Selecione um filme...</option>
            <?php 
            if (!empty($filmes_biblioteca)) {
                foreach ($filmes_biblioteca as $filme) {
                    echo '<option value="' . $filme['id'] . '">' . htmlspecialchars($filme['titulo']) . '</option>';
                }
            } else {
                echo '<option value="">Nenhum filme cadastrado</option>';
            }
            ?>
            </select>

          </label>

            <label for="tipo_exibicao_ingresso">Tipo de Exibição:</label>
            
            <label>
                <input
                type="checkbox"
                name="tipo_exibicao_ingresso[]"
                value="2D"
                />
                2D
            </label>
            <label>
                <input
                type="checkbox"
                name="tipo_exibicao_ingresso[]"
                value="3D"
                />
                3D
            </label>

          <h4 style="margin-top: 30px;">Selecione as datas e horários</h4>
          <div style="display:flex; align-items:center; gap:10px;margin-bottom:20px;">
            <button id="btnAnterior-ingresso"
            style="padding:6px 12px;background:#444;color:white;border:none;border-radius:5px;cursor:pointer;">&larr;
            Anterior</button>
            <h3 id="mesAnoAtual-ingresso" style="margin:0;"></h3>
            <button id="btnProximo-ingresso"
            style="padding:6px 12px;background:#444;color:white;border:none;border-radius:5px;cursor:pointer;">Próximo
            &rarr;</button>
          </div>
          <div id="calendario-ingresso" style="display:flex;flex-wrap:wrap; align-items:center; gap:10px;"></div>
          <h4 id="info-ingresso"></h4>
          <div id="horarios-ingresso" style="display:flex;flex-wrap:wrap; align-items:center; gap:10px;"></div>
          <input type="hidden" name="data_ingresso" id="dataIngressoSelecionada">
          <input type="hidden" name="horario_ingresso" id="horarioIngressoSelecionado">

            <label>
                   <p> Quantidade de ingressos <br>
                    disponíveis para esse filme/sessão. </p>
                    <input type="number" name="qtdingressos" placeholder="Quantidade de ingressos" min="1" required />
                </label>


          <input type="submit" value="Cadastrar Ingresso"> </form>
      </div>
    </div>




     <!-- RELATORIO -->
    <div id="modalRelatorio" class="modal">
      <div class="modal-conteudo">
        <span class="fechar fechar-relatorio">&times;</span>
        <h2>Relatorio</h2>
 
        <form method="post" action="relatorio.php">
        <label for="filme">
        Filme:
        <div class="custom-multiselect">
        <select name="filme" id="filme" required>
            <option value="" disabled selected>Selecione um filme...</option>
            <option value="todos">Todos os filmes</option>
            <?php
            if (!empty($filmes_biblioteca)) {
                foreach ($filmes_biblioteca as $filme) {
                    echo '<option value="' . $filme['id'] . '">' . htmlspecialchars($filme['titulo']) . '</option>';
                }
            } else {
                echo '<option value="">Nenhum filme cadastrado</option>';
            }
            ?>
            </select>
            </label>
            </div>
 <BR> <BR> <BR>
 
            <label for="tipo_exibicao_relatorio">Tipo de Exibição:</label>
            <BR> <BR> <BR>
            <label>
                <input
                type="checkbox"
                name="tipo_exibicao_relatorio[]"
                value="2d"
                />
                2D
            </label> <BR> <BR> <BR>
            <label>
                <input
                type="checkbox"
                name="tipo_exibicao_relatorio[]"
                value="3d"
                />
                3D
            </label>
       
           
            <BR> <BR> <BR>
            <label for="periodo_relatorio">Escolha o período:</label>
            <label>
                <input type="radio" name="periodo" value="15dias">
                    15 dias
            </label>
 
            <label>
                <input type="radio" name="periodo" value="30dias">
                    30 dias
            </label>
            <p style="font-weight: bold;">ou</p>
            <label>
                <input type="date" name="periodo_perso" value="">
                   
            </label><br> <BR>
 
         
         
            <input type="submit" value="Emitir Relatorio">
        </form>
      </div>
    </div>

    <!-- ALTERAR CADASTRO AINDA PRECISA FAZER TUDO--> 
    <div id="alterarcadastro" class="modal">
      <div class="modal-conteudo">
        <span class="fechar fechar-alterarcadastro">&times;</span>
        <h2>Alterar Cadastro</h2>

        <form method="post" action="relatorio.php">
        <label for="filme">
        Filme:
        <select name="filme" id="filme" required>
            <option value="" disabled selected>Selecione um filme...</option>
            <option value="todos">Todos os filmes</option>
            <?php 
            if (!empty($filmes_biblioteca)) {
                foreach ($filmes_biblioteca as $filme) {
                    echo '<option value="' . $filme['id'] . '">' . htmlspecialchars($filme['titulo']) . '</option>';
                }
            } else {
                echo '<option value="">Nenhum filme cadastrado</option>';
            }
            ?>
            </select>
            </label>


            <label for="tipo_exibicao_alterarcadastro">Tipo de Exibição:</label>
            
            <label>
                <input
                type="checkbox"
                name="tipo_exibicao_alterarcadastro[]"
                value="2d"
                />
                2D
            </label>
            <label>
                <input
                type="checkbox"
                name="tipo_exibicao_alterarcadastro[]"
                value="3d"
                />
                3D
            </label>
        
            
            
            <label for="periodo_alterarcadastro">Escolha o período:</label>
            <label>
                <input type="radio" name="periodo" value="15dias">
                    15 dias
            </label>

            <label>
                <input type="radio" name="periodo" value="30dias">
                    30 dias
            </label>
            <p style="font-weight: bold;">ou</p>
            <label>
                <input type="date" name="periodo_perso" value="">
                    
            </label><br>

          
          
            <input type="submit" value="Alterar Cadastro"> 
        </form>
      </div>
    </div>

    <script src="script.js"></script>

    <script>
    
        // Funções do calendário 
        const diasSemana = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];
        const hoje = new Date();

        const sessoesDisponiveis = {};
        for (let i = 1; i <= 31; i++) {
            sessoesDisponiveis[i] = ['13:00', '15:30', '18:00', '20:30'];
        }

        const ingressosDisponiveis = {};
        for (let i = 1; i <= 31; i++) {
            ingressosDisponiveis[i] = ['13:00', '15:30', '18:00', '20:30'];
        }

         const relatoriosDisponiveis = {};
        for (let i = 1; i <= 31; i++) {
            relatoriosDisponiveis[i] = ['13:00', '15:30', '18:00', '20:30'];
        }

// ESSAS 13 LINHAS ABAIXO PEGUEI DE DEBORA

       let datasSelecionadasPorTipo = {
    'Sessão': { inicio: null, fim: null },
    'Ingressos': { inicio: null, fim: null },
    'Relatorio': { inicio: null, fim: null }
};
// Função para formatar uma data para YYYY-MM-DD
function formatarData(dataObj) {
    if (!dataObj) return ''; // Retorna vazio se a data for nula
    const ano = dataObj.getFullYear();
    const mes = String(dataObj.getMonth() + 1).padStart(2, '0');
    const dia = String(dataObj.getDate()).padStart(2, '0');
    return `${ano}-${mes}-${dia}`;
}




        function criarCalendario(tipo, containerId, infoId, horariosId, sessoes, mesAnoId, btnAntId, btnProxId,  modoSelecao = 'unica') { // ADICIONEI  modoSelecao = 'unica' DO DE DEBORA
            let mes = hoje.getMonth();
            let ano = hoje.getFullYear();

            const container = document.getElementById(containerId);
            const info = document.getElementById(infoId);
            const horarios = document.getElementById(horariosId);
            const mesAnoLabel = document.getElementById(mesAnoId);
            const btnAnterior = document.getElementById(btnAntId);
            const btnProximo = document.getElementById(btnProxId);

            function render() {
                container.innerHTML = '';
                info.textContent = '';
                horarios.innerHTML = '';

                const grade = document.createElement('div');
                grade.style.display = 'grid';
                grade.style.gridTemplateColumns = 'repeat(7, 1fr)';
                grade.style.gap = '5px';
                grade.style.maxWidth = '300px';

                diasSemana.forEach(dia => {
                    const th = document.createElement('div');
                    th.textContent = dia;
                    th.style.fontWeight = 'bold';
                    th.style.textAlign = 'center';
                    grade.appendChild(th);
                });

                const primeiroDia = new Date(ano, mes, 1).getDay();
                const diasNoMes = new Date(ano, mes + 1, 0).getDate();

                for (let i = 0; i < primeiroDia; i++) grade.appendChild(document.createElement('div'));

                for (let d = 1; d <= diasNoMes; d++) {
                    const box = document.createElement('div');
                    box.textContent = d;
                    box.style.padding = '10px';
                    box.style.background = 'black';
                    box.style.color = 'white';
                    box.style.borderRadius = '5px';
                    box.style.textAlign = 'center';
                    box.style.cursor = 'pointer';


// ESSAS 12 LINHAS ABAIXO PEGUEI DE DEBORA

                    const dataAtual = new Date(ano, mes, d);
                    dataAtual.setHours(0, 0, 0, 0); // Normaliza para comparação

                    // === Lógica de Destaque das Datas ===
                    const inicio = datasSelecionadasPorTipo[tipo].inicio;
                    const fim = datasSelecionadasPorTipo[tipo].fim;

                    if (modoSelecao === 'intervalo' && inicio && fim && dataAtual >= inicio && dataAtual <= fim) {
                        box.style.background = '#ff8c5a'; // Laranja
                    } else if (modoSelecao === 'intervalo' && inicio && dataAtual.toDateString() === inicio.toDateString()) {
                        box.style.background = '#ff8c5a'; // Laranja (data inicial)
                    } else if (modoSelecao === 'unica' && inicio && dataAtual.toDateString() === inicio.toDateString()) {
                        box.style.background = '#ff8c5a'; // Laranja (data única selecionada)
                    }


                    //const data = new Date(ano, mes, d);
                    //const diaSemana = diasSemana[data.getDay()]; // VER SE PERMANECE 
                    //const rotulo = `${diaSemana} - ${d}/${mes + 1}`; // VER SE PERMANECE






// === Lógica de Clique para Seleção ===
                   /* box.onclick = () => {
                        [...grade.children].forEach(el => {
                            if (el.textContent && !diasSemana.includes(el.textContent)) el.style.background = 'black';
                        });
                        box.style.background = '#ff8c5a';
                        info.textContent = `Sessões em ${rotulo}`;
                        horarios.innerHTML = '';
                        (sessoes[d] || []).forEach(h => {
                            const btn = document.createElement('button');
                            btn.textContent = h;
                            btn.style.padding = '6px 10px';
                            btn.style.margin = '5px';
                            btn.style.border = 'none';
                            btn.style.borderRadius = '5px';
                            btn.style.background = '#4caf50';
                            btn.style.color = 'white';
                            btn.style.cursor = 'pointer';
                            btn.onclick = () => {
                                document.getElementById('dataSessaoSelecionada').value = `${ano}-${("0" + (mes + 1)).slice(-2)}-${("0" + d).slice(-2)}`;
                                document.getElementById('horarioSessaoSelecionado').value = h;
                                alert(`Você escolheu ${h} no dia ${rotulo} (${tipo})`);
                            }
                            horarios.appendChild(btn);
                        });
                    };*/
  box.onclick = () => {
                        const dataClicada = new Date(ano, mes, d);
                        dataClicada.setHours(0, 0, 0, 0); // Normaliza

                        if (modoSelecao === 'intervalo') {
                            // Lógica para seleção de intervalo (usada no Cadastro de Sessão)
                            if (!datasSelecionadasPorTipo[tipo].inicio || (datasSelecionadasPorTipo[tipo].inicio && datasSelecionadasPorTipo[tipo].fim)) {
                                // Primeiro clique ou reiniciar seleção
                                datasSelecionadasPorTipo[tipo].inicio = dataClicada;
                                datasSelecionadasPorTipo[tipo].fim = null; // Reseta a data final
                            } else if (dataClicada < datasSelecionadasPorTipo[tipo].inicio) {
                                // Se a nova data for menor que a inicial, inverte
                                datasSelecionadasPorTipo[tipo].fim = datasSelecionadasPorTipo[tipo].inicio;
                                datasSelecionadasPorTipo[tipo].inicio = dataClicada;
                            } else {
                                // Segundo clique, define a data final
                                datasSelecionadasPorTipo[tipo].fim = dataClicada;
                            }

                            // Atualiza o input hidden com o intervalo
                            const inputDataSessao = document.getElementById('dataSessaoSelecionada');
                            if (datasSelecionadasPorTipo[tipo].inicio && !datasSelecionadasPorTipo[tipo].fim) {
                                inputDataSessao.value = `${formatarData(datasSelecionadasPorTipo[tipo].inicio)}|${formatarData(datasSelecionadasPorTipo[tipo].fim)}`;
                                info.textContent = `Sessão de ${formatarData(datasSelecionadasPorTipo[tipo].inicio)} até ${formatarData(datasSelecionadasPorTipo[tipo].fim)}`;
                                // Aqui você pode também limpar os horários se eles não forem relevantes para um intervalo
                                horarios.innerHTML = '';
                            } else if (datasSelecionadasPorTipo[tipo].inicio) {
                                inputDataSessao.value = formatarData(datasSelecionadasPorTipo[tipo].inicio);
                                info.textContent = `Data Inicial da Sessão: ${formatarData(datasSelecionadasPorTipo[tipo].inicio)}`;
                                //horarios.innerHTML = ''; // Limpa horários se apenas a data inicial está selecionada
                                horarios.innerHTML = ''; // Limpa horários anteriores

                               /* // Exibe horários se houver data inicial
                                if (datasSelecionadasPorTipo[tipo].inicio && !datasSelecionadasPorTipo[tipo].fim) {
                                    const dia = datasSelecionadasPorTipo[tipo].inicio.getDate();
                                    (sessoes[dia] || []).forEach(h => {
                                        const btn = document.createElement('button');
                                        btn.textContent = h;
                                        btn.style.padding = '6px 10px';
                                        btn.style.margin = '5px';
                                        btn.style.border = 'none';
                                        btn.style.borderRadius = '5px';
                                        btn.style.background = '#4caf50';
                                        btn.style.color = 'white';
                                        btn.style.cursor = 'pointer';
                                        btn.onclick = () => {
                                            document.getElementById('dataSessaoSelecionada').value = formatarData(datasSelecionadasPorTipo[tipo].inicio);
                                            document.getElementById('horarioSessaoSelecionado').value = h;
                                        }
                                        horarios.appendChild(btn);
                                    });
                                }*/

                            } else {
                                inputDataSessao.value = '';
                                info.textContent = '';
                                horarios.innerHTML = '';
                            }

                            // Redesenha o calendário para atualizar o destaque visual
                            render();

                        } else {
                            // Lógica existente para seleção de data única (para Ingressos e Relatórios, por exemplo)
                            // Limpa destaques anteriores
                            [...grade.children].forEach(el => {
                                if (el.textContent && !diasSemana.includes(el.textContent)) el.style.background = 'black';
                            });
                            box.style.background = '#ff8c5a'; // Laranja para a data única

                            // Armazena a data única
                            datasSelecionadasPorTipo[tipo].inicio = dataClicada;
                            datasSelecionadasPorTipo[tipo].fim = null; // Garante que a data final seja nula para seleção única

                            const diaSemana = diasSemana[dataClicada.getDay()];
                            const rotulo = `${diaSemana} - ${d}/${mes + 1}`;


                            
                            info.textContent = `Sessões em ${rotulo}`;
                            horarios.innerHTML = ''; // Limpa horários ao selecionar uma nova data única

                            (sessoes[d] || []).forEach(h => {
                                const btn = document.createElement('button');
                                btn.textContent = h;
                                btn.style.padding = '6px 10px';
                                btn.style.margin = '5px';
                                btn.style.border = 'none';
                                btn.style.borderRadius = '5px';
                                btn.style.background = '#4caf50';
                                btn.style.color = 'white';
                                btn.style.cursor = 'pointer';
                                btn.onclick = () => {
                                    // Este é o clique do horário, relevante para seleção de data única
                                    document.getElementById(`data${tipo}Selecionada`).value = `${ano}-${("0" + (mes + 1)).slice(-2)}-${("0" + d).slice(-2)}`;
                                    document.getElementById(`horario${tipo}Selecionado`).value = h;
                                    // alert(`Você escolheu ${h} no dia ${rotulo} (${tipo})`);
                                }
                                horarios.appendChild(btn);
                            });
                        }
                    };
                    grade.appendChild(box);
                }

                container.appendChild(grade);
                mesAnoLabel.textContent = `${("0" + (mes + 1)).slice(-2)}/${ano}`;
                btnAnterior.disabled = (ano === hoje.getFullYear() && mes === hoje.getMonth());
            }

            btnAnterior.onclick = () => {
                if (!(ano === hoje.getFullYear() && mes === hoje.getMonth())) {
                    mes--;
                    if (mes < 0) { mes = 11; ano--; }
                    render();
                }
            };

            btnProximo.onclick = () => {
                mes++;
                if (mes > 11) { mes = 0; ano++; }
                render();
            };

            render(); // Renderiza o calendário inicial
        }

        // Inicializa o calendário ao carregar a página SESSÃO
        window.addEventListener('DOMContentLoaded', () => {
            criarCalendario(
                'Sessão',
                'calendario-sessao',
                'info-sessao',
                'horarios-sessao',
                sessoesDisponiveis,
                'mesAnoAtual-sessao',
                'btnAnterior-sessao',
                'btnProximo-sessao',
                'intervalo' //ADICIONADO DO DE DEBORA
            );            
        });

        // Inicializa o calendário ao carregar a página INGRESSO
        window.addEventListener('DOMContentLoaded', () => {
            criarCalendario(
                'Ingressos',
                'calendario-ingresso',
                'info-ingresso',
                'horarios-ingresso',
                ingressosDisponiveis,
                'mesAnoAtual-ingresso',
                'btnAnterior-ingresso',
                'btnProximo-ingresso',
                'unica  ' //ADICIONADO DO DE DEBORA
            );
        });

 // Inicializa o calendário ao carregar a página RELATORIO ADICIONADO 13 LINHAS DO DE DEBORA
        window.addEventListener('DOMContentLoaded', () => {
            criarCalendario(
                'Relatorio',
                'calendario-relatorio',
                'info-relatorio',
                'horarios-relatorio',
                relatoriosDisponiveis,
                'mesAnoAtual-relatorio',
                'btnAnterior-relatorio',
                'btnProximo-relatorio',
                'unica'
            );
        });

        // Funções para os contadores e toggles da biblioteca de filmes
        function alterarPosicao(filmeId, buttonElement, delta) {
            const spanValor = document.getElementById('posicao-' + filmeId);
            let valorAtual = parseInt(spanValor.textContent);
            let novoValor = valorAtual + delta;

            // Limitar a posição entre 0 e 4 (ou o máximo de banners que você tem)
            // Ajuste aqui se o seu limite de posição for diferente
            if (novoValor < 0) novoValor = 0;
            if (novoValor > 4) novoValor = 4;

            spanValor.textContent = novoValor;

            // Enviar requisição AJAX para atualizar no banco de dados
            fetch('update_filme_data.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id_filme=${filmeId}&campo=posicao&valor=${novoValor}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log(`Posição do filme ${filmeId} atualizada para ${novoValor}`);
                } else {
                    console.error('Erro ao atualizar posição:', data.message);
                    spanValor.textContent = valorAtual; // Reverte se houver erro
                }
            })
            .catch(error => {
                console.error('Erro na requisição:', error);
                spanValor.textContent = valorAtual; // Reverte se houver erro
            });
        }

        function toggleDestaque(checkbox) {
            const filmeId = checkbox.dataset.filmeId;
            const isChecked = checkbox.checked ? 1 : 0; // 1 para true, 0 para false

            fetch('update_filme_data.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id_filme=${filmeId}&campo=destaque&valor=${isChecked}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log(`Destaque do filme ${filmeId} alterado para ${isChecked}`);
                } else {
                    console.error('Erro ao alterar destaque:', data.message);
                    checkbox.checked = !checkbox.checked; // Reverte o estado do checkbox se houver erro
                }
            })
            .catch(error => {
                console.error('Erro na requisição:', error);
                checkbox.checked = !checkbox.checked; // Reverte o estado do checkbox se houver erro
            });
        }

        // Script para abrir e fechar modais 
      
        document.addEventListener('DOMContentLoaded', () => {
            const modalConfirmarExclusao = document.getElementById('modalConfirmarExclusao');
            const confirmarExcluirBtn = document.getElementById('confirmarExcluir');
            const cancelarExcluirBtn = document.getElementById('cancelarExcluir');
            let filmeParaExcluirId = null;

            // Event listener para os botões de EXCLUIR
            document.querySelectorAll('.btn-excluir').forEach(button => {
                button.addEventListener('click', function() {
                    filmeParaExcluirId = this.dataset.id;
                    modalConfirmarExclusao.style.display = 'block';
                });
            });

            confirmarExcluirBtn.addEventListener('click', function() {
                if (filmeParaExcluirId) {
                    // Chamar função para excluir o filme via AJAX
                    excluirFilme(filmeParaExcluirId);
                    modalConfirmarExclusao.style.display = 'none';
                    filmeParaExcluirId = null;
                }
            });

            cancelarExcluirBtn.addEventListener('click', function() {
                modalConfirmarExclusao.style.display = 'none';
                filmeParaExcluirId = null;
            });

            // Adicione a função de exclusão via AJAX
            function excluirFilme(id) {
                fetch('delete_filme.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id_filme=${id}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Filme excluído com sucesso!');
                        location.reload(); // Recarregar a página para atualizar a tabela
                    } else {
                        alert('Erro ao excluir filme: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Erro na requisição:', error);
                    alert('Erro ao excluir filme.');
                });
            }

            // Para os botões de editar, você precisaria de uma lógica para preencher o formulário do modalFilme
            // com os dados do filme clicado. Isso geralmente envolve outra requisição AJAX para buscar os dados
            // do filme pelo ID e depois preencher o formulário.
            document.querySelectorAll('.btn-editar').forEach(button => {
                button.addEventListener('click', function() {
                    const filmeId = this.dataset.id;
                    // Implementação futura: Abrir modalFilme e preencher com dados do filmeId
                    // Ex:
                    // fetch('get_filme_data.php?id=' + filmeId)
                    // .then(response => response.json())
                    // .then(data => {
                    //    // Preencher formulário com data (data.titulo, data.classificacao, etc.)
                    //    document.getElementById('titulo-modal-filme').textContent = 'Editar Filme';
                    //    document.querySelector('input[name="titulo_filme"]').value = data.titulo;
                    //    // ... preencher outros campos ...
                    //    document.getElementById('modalFilme').style.display = 'block';
                    // });
                    alert(`Funcionalidade de Edição para o filme ID: ${filmeId} será implementada.`);
                });
            });

// Função genérica para abrir/fechar modais
function configurarModal(botaoId, modalId, fecharClass) {
    const botao = document.getElementById(botaoId);
    const modal = document.getElementById(modalId);
    const fechar = modal.querySelector('.' + fecharClass);

    if (botao && modal && fechar) {
        botao.addEventListener('click', () => modal.style.display = 'block');
        fechar.addEventListener('click', () => modal.style.display = 'none');
    }
}

// Chamada para cada modal
configurarModal('cadastrarfilme', 'modalFilme', 'fechar-filme');
configurarModal('Verfilmes', 'modalVerFilmes', 'fechar-Verfilmes');
configurarModal('cadastrarsessao', 'modalSessao', 'fechar-sessao');
configurarModal('cadastraringresso', 'modalIngresso', 'fechar-ingresso');
configurarModal('relatoriovendas', 'modalRelatorio', 'fechar-relatorio');
configurarModal('alterarcadastro', 'modalalterarcadastro', 'fechar-alterarcadastro');

        }); // Fim do DOMContentLoaded


    </script>
    </body>

</html>