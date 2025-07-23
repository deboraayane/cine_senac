<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/global/global.css">
    <title>Compra de Ingressos</title>
</head>

<body>
 
    <!-- Início da barra de navegação -->
    <header class="navBar">
        <div class="logo">
            <img src="/img/logo-cine-senac.png" alt="Logo Cine Senac" />
        </div>
        <nav class="paginas">
            <a href="/index.php">Início</a>
            <a href="/index.php#filmesEmCartaz">Filmes</a>
            <a href="#">Promoções</a>
            <a href="https://wa.me/5575999999999" target="_blank">Contato</a>
        </nav>
        <div class="perfil">
            <a href="/telas/tela_login/login.html">Login</a>
        </div>
    </header>

    <!-- ===== MAIN CONTENT ===== -->
    <main>
        <BR><BR>
        <!-- Informações do Filme -->
        <section class="info-filme">
            <img src="" alt="Cartaz do filme" class="placeholder-img" id="cartaz-img">
            <h2 id="sala-info">Sala</h2>
            <p id="horario-info">Horário: </p>
            <div class="resumo">
                <span>Quantidade de Ingressos:</span>
                <span id="total-ingressos">0</span>
            </div>
            <div class="resumo">
                <span>Total:</span>
                <span>R$ <span id="total-preco">0,00</span></span>
            </div>
        </section>

        <!-- Seleção de Ingressos -->
        <section class="selecao-ingresso">
            <h3>Selecione o seu ingresso</h3>

            <div class="tipo-ingresso">
                <h4>TIPO DE INGRESSO</h4>

                <div class="ticket-item">
                    <span>INTEIRA</span>
                    <div class="ticket-controls">
                        <button type="button" onclick="alterarQuantidade('inteira', -1)">-</button>
                        <span class="preco">R$ 25,00</span>
                        <button type="button" onclick="alterarQuantidade('inteira', 1)">+</button>
                    </div>
                </div>

                <div class="ticket-item">
                    <span>MEIA</span>
                    <div class="ticket-controls">
                        <button type="button" onclick="alterarQuantidade('meia', -1)">-</button>
                        <span class="preco">R$ 12,50</span>
                        <button type="button" onclick="alterarQuantidade('meia', 1)">+</button>
                    </div>
                </div>
            </div>

            <div class="resumo-compra">
                <div class="resumo-item">
                    <span>Quantidade de Ingressos:</span>
                    <span id="resumo-quantidade">0</span>
                </div>
                <div class="resumo-item">
                    <span>Total:</span>
                    <span>R$ <span id="resumo-total">0,00</span></span>
                </div>
            </div>

            <button type="button" class="btn-finalizar" id="btn-finalizar" onclick="finalizarCompra()">
                FINALIZAR COMPRA
            </button>
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
            <p>Telefone: (75) 99999-9999</p>
        </div>
    </footer>

    <script>
        let quantidades = {
            inteira: 0,
            meia: 0
        };

        const precos = {
            inteira: 25.00,
            meia: 12.50
        };

        function formatarPreco(valor) {
            return valor.toFixed(2).replace('.', ',');
        }

        function atualizarInterface() {
            const totalIngressos = quantidades.inteira + quantidades.meia;
            const totalPreco = (quantidades.inteira * precos.inteira) + (quantidades.meia * precos.meia);

            document.getElementById('total-ingressos').textContent = totalIngressos;
            document.getElementById('total-preco').textContent = formatarPreco(totalPreco);
            document.getElementById('resumo-quantidade').textContent = totalIngressos;
            document.getElementById('resumo-total').textContent = formatarPreco(totalPreco);

            document.getElementById('btn-finalizar').disabled = totalIngressos === 0;
        }

        function alterarQuantidade(tipo, delta) {
            const novaQuantidade = quantidades[tipo] + delta;
            if (novaQuantidade >= 0) {
                quantidades[tipo] = novaQuantidade;
                atualizarInterface();
            }
        }

        function finalizarCompra() {
            const totalIngressos = quantidades.inteira + quantidades.meia;
            const totalPreco = (quantidades.inteira * precos.inteira) + (quantidades.meia * precos.meia);

            const detalhes = {
                inteira: quantidades.inteira,
                meia: quantidades.meia,
                totalIngressos: totalIngressos,
                total: totalPreco
            };

            localStorage.setItem('detalhesCompra', JSON.stringify(detalhes));
            window.location.href = '/telas/tela_pagamento/pagamento.html';
        }

        
        // Carrega dados do filme selecionado do localStorage
        document.addEventListener("DOMContentLoaded", function () {
            const dadosFilme = JSON.parse(localStorage.getItem('filmeSelecionado'));

            if (dadosFilme) {
                document.getElementById('cartaz-img').src = dadosFilme.poster;
                document.getElementById('cartaz-img').alt = "Cartaz do filme " + dadosFilme.titulo;
                document.getElementById('sala-info').textContent = `Sala ${dadosFilme.sala}`;
                document.getElementById('horario-info').textContent = `Horário: ${dadosFilme.data} às ${dadosFilme.hora}`;
            } else {
                alert("Nenhum filme foi selecionado. Retorne à página anterior.");
            }

            atualizarInterface();
        });
    </script>



</body>

</html>
