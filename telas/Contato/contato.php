<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Cine Senac - Contato</title>
  <link rel="stylesheet" href="/css/global/global.css" />
  <link rel="stylesheet" href="/css/pages/pag_contato.css" />
  <link rel="icon" href="/img/favicon.png" type="image/png" />
</head>

<body>
  <header class="navBar">
    <div class="logo">
      <img src="/img/logo-cine-senac.png" alt="CineSenac" />
    </div>

    <div class="hamburger" onclick="toggleMenu()">&#9776;</div>

    <nav class="paginas" id="menuPaginas">
      <a href="/index.php">Início</a>
      <a href="/index.php#filmesEmCartaz">Filmes</a>
      <a href="#">Promoções</a>
      <a href="/telas/Contato/contato.php">Contato</a>

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
    </nav>
  </header>

  <main class="container">
    <h1 class="title-primary">Fale Conosco</h1>

    <div class="top-sections-wrapper">
      <section class="section-card about-us">
        <h2 class="title-secondary">Sobre o Cine Senac</h2>
        <p>
          Bem-vindo ao Cine Senac, o seu destino para a melhor experiência
          cinematográfica. Desde a nossa fundação em 2025, estamos
          comprometidos em oferecer aos nossos espectadores uma seleção de
          filmes de alta qualidade, desde os grandes lançamentos de Hollywood
          até joias independentes, tudo em um ambiente confortável e moderno.
        </p>
        <p>
          Nosso objetivo é proporcionar momentos inesquecíveis para toda a
          família e amigos, com tecnologia de ponta em som e imagem, além de
          um atendimento excepcional. Venha nos visitar e descubra a magia do
          cinema no Cine Senac!<br /><br />
        </p>
      </section>

      <section class="section-card contact-info">
        <h2 class="title-secondary">Contato Direto</h2>
        <div class="contact-grid">
          <div class="contact-item">
            <img
              src="/img/whatsapp-icon-o.png"
              alt="WhatsApp"
              class="contact-icon" />
            <p>
              WhatsApp:
              <a href="https://wa.me/5575999999999" target="_blank">(75) 99999-9999</a>
            </p>
          </div>
          <div class="contact-item">
            <img
              src="/img/phone_icon.png"
              alt="Telefone"
              class="contact-icon" />
            <p>Telefone: <a href="tel:+5575999999999">(75) 99999-9999</a></p>
          </div>
          <div class="contact-item">
            <img src="/img/mail_icon.png" alt="Email" class="contact-icon" />
            <p>
              Email:
              <a href="mailto:contato@cinesenac.com">contato@cinesenac.com</a>
            </p>
          </div>
        </div>
        <p class="working-hours">
          Atendimento: Terça a Domingo, das 15h às 22h
        </p>
      </section>
    </div>

    <div class="section-card contact-info">
      <h2 class="title-secondary">Nosso Endereço</h2>
      <section class="section-card location-info">

        <p>
          Rua Exemplo, 123 - Centro<br />Santo Antônio de Jesus - BA,
          44700-000
        </p>
        <div class="map-container">
          <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3885.6793132717056!2d-39.2657876!3d-12.9647265!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x738a9235e18412b%3A0x677b1029281a8b30!2sSenac%20Santo%20Ant%C3%B4nio%20de%20Jesus!5e0!3m2!1spt-BR!2sbr!4v1700000000000!5m2!1spt-BR!2sbr"
            width="100%"
            height="450"
            style="border: 0"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade">
          </iframe>
        </div>
      </section>
    </div>
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
  
  <script>
  function toggleMenu() {
    const menu = document.getElementById('menuPaginas');
    menu.classList.toggle('active');
  }
</script>

</body>

</html>