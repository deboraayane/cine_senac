<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclui o arquivo de configuração do banco de dados
include 'config.php'; // Certifique-se de que o caminho está correto

// === Lógica para o carrossel de filmes em destaque ===
$filmes_destaque = [];
$sql_destaque = "SELECT id_filme, titulo, poster FROM filme WHERE destaque = TRUE ORDER BY posicao ASC LIMIT 3"; // Pega os 3 primeiros filmes marcados como destaque, ordenados pela posição
$result_destaque = $conn->query($sql_destaque);

if ($result_destaque->num_rows > 0) {
  while ($row = $result_destaque->fetch_assoc()) {
    $filmes_destaque[] = $row;
  }
}

// === Lógica para os filmes em cartaz ===
// Pega os filmes com posições de 1 a 4, garantindo ordem
$filmes_em_cartaz_map = []; // Usaremos uma array associativa para mapear por posição
$sql_cartaz = "SELECT id_filme, titulo, poster, posicao FROM filme WHERE posicao >= 1 AND posicao <= 4 ORDER BY posicao ASC";
$result_cartaz = $conn->query($sql_cartaz);

if ($result_cartaz->num_rows > 0) {
  while ($row = $result_cartaz->fetch_assoc()) {
    // Armazena o filme usando a posição como chave
    $filmes_em_cartaz_map[$row['posicao']] = $row;
  }
}

// Fecha a conexão com o banco de dados
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" href="/img/favicon.png" type="image/png" />
  <title>Cine Senac</title>
  <link rel="stylesheet" href="/css/global/global.css" />
  <style>
    .cartaz .filmes {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      /* Ajuste o minmax conforme o tamanho desejado */
      gap: 20px;
      /* Espaçamento entre os posters */
      justify-content: center;
    }

    .cartaz .filme {
      /* Seus estilos existentes para .filme */
      text-align: center;
    }

    .cartaz .filme img {
      width: 100%;
      height: auto;
      border-radius: 8px;
      /* Arredondar bordas se desejar */
    }
  </style>
</head>

<body>
  <header class="navBar">

    <div class="logo">
      <img src="/img/logo-cine-senac.png" alt="CineSenac" />
    </div>

    <div class="hamburger">&#9776;</div>

    <nav class="paginas">
      <a href="/index.php">Início</a>
      <a href="#filmesEmCartaz">Filmes</a>
      <a href="#">Promoções </a>
      <a href="/telas/Contato/contato.html">Contato </a>
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


  </header>

  <main>
    <section class="carrossel container">
      <h2>Em Destaque</h2>
      <div class="banner" id="bannerCarrossel">
        <?php if (!empty($filmes_destaque)): ?>
          <?php foreach ($filmes_destaque as $filme):
            // Extrai o nome base do arquivo do pôster
            $nome_arquivo_poster = basename($filme['poster']);
            // Remove '_poster.jpg' para obter o nome base do filme para o banner
            $base_name = str_replace('_poster.jpg', '', $nome_arquivo_poster);
            // Constrói o caminho do banner baseado na convenção
            $caminho_banner = "/img/banners/" . $base_name . "_banner.jpg";
          ?>
            <a href="/telas/tela_detalhe_filme/tela_detalhe_filme.php?id_filme=<?php echo $filme['id_filme']; ?>">
              <img src="<?php echo $caminho_banner; ?>" alt="Banner do filme: <?php echo htmlspecialchars($filme['titulo']); ?>" />
            </a>
          <?php endforeach; ?>
        <?php else: ?>
          <p>Nenhum filme em destaque para exibir.</p>
        <?php endif; ?>
      </div>
      <div id="filmesEmCartaz"></div>
    </section>

    <section class="cartaz container">
      <h2>Filmes em Cartaz</h2>
      <div class="filmes">
        <?php
        // Itera pelas posições de 1 a 4
        for ($i = 1; $i <= 4; $i++):
          // Verifica se existe um filme para a posição atual
          if (isset($filmes_em_cartaz_map[$i])):
            $filme = $filmes_em_cartaz_map[$i];
        ?>
            <a href="/telas/tela_detalhe_filme/tela_detalhe_filme.php?id_filme=<?php echo $filme['id_filme']; ?>">
              <div class="filme">
                <img
                  src="/img/posters/<?php echo htmlspecialchars($filme['poster']); ?>"
                  alt="Pôster do filme:<?php echo htmlspecialchars($filme['titulo']); ?>" />
                <p><?php echo htmlspecialchars($filme['titulo']); ?></p>
              </div>
            </a>
          <?php
          else:
            // Se não houver filme para a posição, exibe um placeholder vazio
          ?>
            <div class="filme placeholder">
              <p>Vazio</p>
            </div>
        <?php
          endif;
        endfor;
        ?>
      </div>
    </section>
  </main>

  <footer class="rodape container">
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
      <p>Telefone: (75) 99999-9999</p>
    </div>
  </footer>
</body>
<script>
  let index = 0;
  const images = document.querySelectorAll(".banner img");

  function showSlide() {
    images.forEach((img, i) => {
      img.classList.remove("active");
      if (i === index) {
        img.classList.add("active");
      }
    });

    index = (index + 1) % images.length;
  }

  showSlide(); // mostra a primeira imagem
  setInterval(showSlide, 4000); // troca a cada 4 segundos

  // === HAMBÚRGUER ===
  document.addEventListener('DOMContentLoaded', function() {
    const hamburger = document.querySelector('.hamburger');
    const paginas = document.querySelector('.paginas');

    hamburger.addEventListener('click', function() {
      paginas.classList.toggle('active');
    });
  });

</script>



</html>