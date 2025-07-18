<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cine Senac</title>
    <link rel="stylesheet" href="/css/global/global.css" />
  </head>
  <body>
    <!-- Início da barra de navegação -->
    <header class="navBar">
      <div class="logo">
        <a href="/telas/telas_modal/tela_modal.php">
          <img src="/img/logo-cine-senac.png" alt="" /></a>
      </div>
      <nav class="paginas">
        <a href="/index.php">Início</a>
        <a href="#filmesEmCartaz">Filmes</a>
        <a href="#">Promoções</a>
        <a href="https://wa.me/5575983236764" target="_blank">Contato</a>
      </nav>
      <div class="perfil">
        <a href="/telas/tela_login/login.html">Login</a>
      </div>
    </header>

    <main>
      <!-- Seção do carrossel com 3 imagens -->
      <section class="carrossel container">
        <h2>Em Destaque</h2>
        <div class="banner">
          <img src="/img/pag_inicial/verde.png" alt="Banner 1" />
          <img src="/img/pag_inicial/amarelo.png" alt="Banner 2" />
          <img src="/img/pag_inicial/vermelho.png" alt="Banner 3" />
        </div>
        <div id="filmesEmCartaz"></div>
      </section>

      <!-- Seção dos cartazes dos filmes -->
      <section class="cartaz container">
        <h2>Filmes em Cartaz</h2>
        <div class="filmes">
          <!-- Repetição dos filmes -->
          <a href="/telas/tela_detalhe_filme/tela_detalhe_filme.php">
            <div class="filme">
              <img
                src="https://assets.cine3.com.br/vibezz_15525517.png"
                alt="Filme 1"
              />
              <p>Filme 1</p>
            </div>
          </a>
          <a href="telas\tela_detalhe_filme\tela_detalhe_filme.php">
            <div class="filme">
              <img
                src="https://www.cinemark.com.br/_next/image?url=https%3A%2F%2Fcdnim.prd.cineticket.com.br%2Fimages%2Fcms%2FmoviePoster%2FMoviePoster-85f6e75d-c26c-44a0-9a70-d47f5999fa54.png&w=1920&q=100"
                alt="Filme 2"
              />
              <p>Filme 2</p>
            </div>
          </a>
          <a href="telas\tela_detalhe_filme\tela_detalhe_filme.php">
            <div class="filme">
              <img
                src="https://acdn-us.mitiendanube.com/stores/004/687/740/products/pos-01744-7ee7fa554b354294de17181315528687-480-0.jpg"
                alt="Filme 3"
              />
              <p>Filme 3</p>
            </div>
          </a>
          <a href="telas\tela_detalhe_filme\tela_detalhe_filme.php">
            <div class="filme">
              <img
                src="https://acdn-us.mitiendanube.com/stores/004/687/740/products/pos-01744-7ee7fa554b354294de17181315528687-480-0.jpg"
                alt="Filme 4"
              />
              <p>Filme 4</p>
            </div>
          </a>
          <a href="telas\tela_detalhe_filme\tela_detalhe_filme.php">
            <div class="filme">
              <img
                src="https://acdn-us.mitiendanube.com/stores/004/687/740/products/pos-01744-7ee7fa554b354294de17181315528687-480-0.jpg"
                alt="Filme 5"
              />
              <p>Filme 5</p>
            </div>
          </a>
          <a href="telas\tela_detalhe_filme\tela_detalhe_filme.php">
            <div class="filme">
              <img
                src="https://acdn-us.mitiendanube.com/stores/004/687/740/products/pos-01744-7ee7fa554b354294de17181315528687-480-0.jpg"
                alt="Filme 6"
              />
              <p>Filme 6</p>
            </div>
          </a>
          <a href="telas\tela_detalhe_filme\tela_detalhe_filme.php">
            <div class="filme">
              <img
                src="https://acdn-us.mitiendanube.com/stores/004/687/740/products/pos-01744-7ee7fa554b354294de17181315528687-480-0.jpg"
                alt="Filme 7"
              />
              <p>Filme 7</p>
            </div>
          </a>
          <a href="telas\tela_detalhe_filme\tela_detalhe_filme.php">
            <div class="filme">
              <img
                src="https://acdn-us.mitiendanube.com/stores/004/687/740/products/pos-01744-7ee7fa554b354294de17181315528687-480-0.jpg"
                alt="Filme 8"
              />
              <p>Filme 8</p>
            </div>
          </a>
        </div>
      </section>
    </main>

  <!-- Rodapé -->
  <footer class="rodape container">
    <div class="logo">
      <p>&copy; 2025 Cine Senac</p>
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
  document.addEventListener('DOMContentLoaded', function () {
    const hamburger = document.querySelector('.hamburger');
    const menu = document.querySelector('.menu');

    hamburger.addEventListener('click', function () {
      menu.classList.toggle('active');
    });
  });
</script>

<script src="/js/navbar.js"></script>


</html>