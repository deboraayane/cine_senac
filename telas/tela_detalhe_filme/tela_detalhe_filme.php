<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" href="/img/favicon.png" type="image/png" />
  <title>Cine Senac - Detalhes do Filme</title>
  <link rel="stylesheet" href="/css/global/global.css" />
  <link rel="stylesheet" href="/css/pages/pag_filme.css" /> <link rel="stylesheet" href="/css/pages/pag_detalhe_filme.css"/>
</head>

<body>

  <?php
  // Inclui o arquivo de configuração do banco de dados
  // Ajuste o caminho conforme a localização do seu config.php
  // Por exemplo, se tela_detalhe_filme.php está em 'telas/tela_detalhe_filme/' e config.php está na raiz, use '../../config.php'
  include '../../config.php';

  $filme = null; // Variável para armazenar os dados do filme

  // Verifica se o ID do filme foi passado na URL
  if (isset($_GET['id_filme'])) {
    $filme_id = intval($_GET['id_filme']); // Converte para inteiro para segurança

    // Prepara a consulta SQL para evitar SQL Injection
    $sql = "SELECT titulo, poster, genero, duracao, sinopse, trailer FROM filme WHERE id_filme = ?";

    if ($stmt = $conn->prepare($sql)) {
      $stmt->bind_param("i", $filme_id); // 'i' indica que o parâmetro é um inteiro
      $stmt->execute();
      $result = $stmt->get_result(); // Obtém o resultado da consulta

      if ($result->num_rows > 0) {
        $filme = $result->fetch_assoc(); // Busca os dados do filme
      }
      $stmt->close(); // Fecha o statement
    } else {
      // Erro ao preparar a consulta
      // Em um ambiente de produção, logar o erro e exibir uma mensagem genérica
      echo "<p>Erro interno ao carregar filme.</p>";
    }
  }

  // Se o filme não foi encontrado ou ID não foi passado, redireciona ou exibe uma mensagem
  if (!$filme) {
    // Você pode redirecionar para a página inicial ou exibir uma mensagem de erro
    header("Location: /index.php"); // Exemplo de redirecionamento
    exit(); // Garante que o script pare de executar após o redirecionamento
  }

  $conn->close(); // Fecha a conexão com o banco de dados
  ?>
  <header class="navBar">

    <div class="logo">
      <a href="/index.php">
        <img src="/img/logo-cine-senac.png" alt="CineSenac" />
      </a>
    </div>

    <div class="hamburger">&#9776;</div>
    
    <div class="paginas">
      <nav >
        <a href="/index.php">Início</a>
        <a href="#filmesEmCartaz">Filmes</a>
        <a href="#">Promoções</a>
        <a href="/telas/Contato/contato.php">Contato</a>
      </nav>

      <div class="perfil">
        <?php if (isset($_SESSION['usuario_id'])): ?>
          <?php if ($_SESSION['tipo_usuario'] === 'admin'): ?>
            <a href="/telas/telas_modal/tela_modal.php">Painel Admin</a>
          <?php else: ?>
            <span>Olá, <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?></span>
          <?php endif; ?>
          <a href="/php/logout.php">Sair</a>
        <?php else: ?>
          <a href="/telas/tela_login/login.html">Login</a>
        <?php endif; ?>
      </div>
        </div>

  </header>

  <main class="container">
    <section class="top-section">
      <br><br><br>


     <div class="filme-escolha">
        <img
          src="/img/posters/<?php echo htmlspecialchars($filme['poster']); ?>"
          alt="Pôster do filme: <?php echo htmlspecialchars($filme['titulo']); ?>"
        />
      </div>

      <div class="info-filme">
        <h2 class="title-secondary"><?php echo htmlspecialchars($filme['titulo']); ?></h2>
        <p><strong>Gênero:</strong> <?php echo htmlspecialchars($filme['genero']); ?></p>
        <p><strong>Duração:</strong> <?php echo htmlspecialchars($filme['duracao']); ?></p>
        <p>
          <?php echo htmlspecialchars($filme['sinopse']); ?>
        </p>
        <div>
          <button style="
    background-color: #2a2a2a;
    border: groove 2px #ff8c5a;
    border-radius: 6px;
    padding: 5px 10px;
    display: inline-flex;
    align-items: center;
  ">
            <a href="https://wa.me/?text=VI%20ESSE%20FILME%20NO%20CINE%20SENAC%20BORA%20ASSISTIR%3F"
              style="color: white; text-decoration: none; display: inline-flex; align-items: center;">
              <b style="margin-right: 5px;">Compartilhar</b>
              <img src="/img/whatsapp.png" alt="whatsapp" height="20" /> </a>
          </button>
        </div>
      </div>
    </section>

    <section class="trailer">
      <h3 class="title-secondary">Assista ao trailer</h3>
      <?php if (!empty($filme['trailer'])): ?>
        <iframe width="100%" height="400" src="<?php echo htmlspecialchars($filme['trailer']); ?>"
          title="Trailer do filme: <?php echo htmlspecialchars($filme['titulo']); ?>" frameborder="0"
          allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
          referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
      <?php else: ?>
        <p>Trailer não disponível para este filme.</p>
      <?php endif; ?>
    </section>

    <section class="schedule-section">
      <h3 class="title-secondary">Horários</h3>


       <div class="session">
        <h4>2D</h4>
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:25px;">
          <button id="btnAnterior-2d"
          style="padding:6px 45px;background:#2a2a2a;color:white;border:none;border-radius:5px;cursor:pointer;"
          >←
            Anterior</button>
          <h3 id="mesAnoAtual-2d" style="margin: 5px;"></h3>
          <button id="btnProximo-2d"
          style="padding:6px 45px;background:#2a2a2a;color:white;border:none;border-radius:5px;cursor:pointer;"
          >Próximo
            →</button>
        </div>
        <div id="calendario-2d"></div>
        <br>
        <h4 id="info-2d"></h4>
        <div id="horarios-2d" style="display:flex;flex-wrap:wrap;gap:10px;"></div>
      </div>

      <div class="session">
        <h4>3D</h4>
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:25px;">
          <button id="btnAnterior-3d"
          style="padding:6px 45px;background:#2a2a2a;color:white;border:none;border-radius:5px;cursor:pointer;"
          >←
            Anterior</button>
          <h3 id="mesAnoAtual-3d" style="margin:5px;"></h3>
          <button id="btnProximo-3d"
          style="padding:6px 45px;background:#2a2a2a;color:white;border:none;border-radius:5px;cursor:pointer;"
          >Próximo
            →</button>
        </div>
        <div id="calendario-3d"></div>
        <br>
        <h4 id="info-3d"></h4>
        <div id="horarios-3d" style="display:flex;flex-wrap:wrap;gap:10px;"></div>
      </div>
    </section>
  </main>

  <footer class="rodape">
    <div class="logo">
      <p>© 2025 Cine Senac</p>
    </div>
    <div class="redesSociais">
      <a href="https://www.facebook.com/SenacBahia/" target="_blank">Facebook</a>
      <a href="https://www.instagram.com/senacbahia/" target="_blank">Instagram</a>
      <a href="https://x.com/senacpituba" target="_blank">Twitter</a>
    </div>
    <div class="contato">
      <p>Email: contato@cinesenac.com</p>
      <p>Telefone: (75) 98336-6742</p>
    </div>
  </footer>

  <script>
    const diasSemana = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];
    const hoje = new Date();

    const sessoes2D = {},
      sessoes3D = {};
    for (let i = 1; i <= 31; i++) {
      sessoes2D[i] = ['14:00', '16:30', '19:00', '21:30'];
      sessoes3D[i] = ['13:00', '15:30', '18:00', '20:30'];
    }

    function criarCalendario(tipo, containerId, infoId, horariosId, sessoes, mesAnoId, btnAntId, btnProxId) {
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
        grade.style.gap = '15px';
        grade.style.maxWidth = '350px';

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
          box.style.padding = '15px';
          box.style.background = 'black';
          box.style.color = 'white';
          box.style.borderRadius = '5px';
          box.style.textAlign = 'center';
          box.style.cursor = 'pointer';

          const data = new Date(ano, mes, d);
          const diaSemana = diasSemana[data.getDay()];
          const rotulo = `${diaSemana} - ${d}/${mes + 1}`;

          box.onclick = () => {
            [...grade.children].forEach(el => {
              if (el.textContent && !diasSemana.includes(el.textContent)) el.style.background = 'black';
            });
            box.style.background = '#ff8c5a';
            info.textContent = `Sessões em ${rotulo}`;
            horarios.innerHTML = '';
            (sessoes[d] || []).forEach(h => {
              const btn = document.createElement('button');
              btn.textContent = h;
              btn.style.padding = '10px 20px';
              btn.style.margin = '5px';
              btn.style.border = 'none';
              btn.style.borderRadius = '15px';
              btn.style.background = '#4caf50';
              btn.style.color = 'white';
              btn.style.cursor = 'pointer';
              // Modificado para redirecionar para tela_ingresso.html com o horário

              btn.onclick = () => {
                const diaFormatado = ("0" + d).slice(-2);
                const mesFormatado = ("0" + (mes + 1)).slice(-2);
                const dadosFilme = {
                  titulo: "<?php echo addslashes($filme['titulo']); ?>",
                  poster: "/img/posters/<?php echo addslashes($filme['poster']); ?>",
                  data: `${diaFormatado}/${mesFormatado}`,
                  hora: h,
                  sala: tipo // "2D" ou "3D"
                };

                localStorage.setItem("filmeSelecionado", JSON.stringify(dadosFilme));
                window.location.href = "/telas/tela_ingresso_corrigida/tela_ingresso.php";
              };

              horarios.appendChild(btn);
            });
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
          if (mes < 0) {
            mes = 11;
            ano--;
          }
          render();
        }
      };

      btnProximo.onclick = () => {
        mes++;
        if (mes > 11) {
          mes = 0;
          ano++;
        }
        render();
      };

      render();
    }

    criarCalendario('2D', 'calendario-2d', 'info-2d', 'horarios-2d', sessoes2D, 'mesAnoAtual-2d', 'btnAnterior-2d', 'btnProximo-2d');
    
    criarCalendario('3D', 'calendario-3d', 'info-3d', 'horarios-3d', sessoes3D, 'mesAnoAtual-3d', 'btnAnterior-3d', 'btnProximo-3d');

    function selecionarFilme(filme) {
      const dadosFilme = {
        titulo: filme.titulo,
        poster: filme.poster,
        data: filme.data,
        hora: filme.hora,
        sala: filme.sala
      };
      localStorage.setItem('filmeSelecionado', JSON.stringify(dadosFilme));
      window.location.href = '/telas/tela_ingresso/tela_ingresso.php';
    }

    // === HAMBÚRGUER ===
    document.addEventListener('DOMContentLoaded', function() {
      const hamburger = document.querySelector('.hamburger');
      const paginas = document.querySelector('.paginas');

      hamburger.addEventListener('click', function() {
        paginas.classList.toggle('active');
      });
    });
  </script>



</body>

</html>