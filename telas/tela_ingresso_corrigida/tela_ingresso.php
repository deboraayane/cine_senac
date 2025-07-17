<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/global/global.css">
    <link rel="stylesheet" href="ingresso.css">
    <title>Document</title>

</head>

<body>

    <!-- Início da barra de navegação -->
    <header class="navBar">
      <div class="logo">
        <img src="/img/logo-cine-senac.png" alt="" />
      </div>
      <nav class="paginas">
        <a href="/index.html">Início</a>
        <a href="/index.html#filmesEmCartaz">Filmes</a>
        <a href="#">Promoções</a>
        <a href="https://wa.me/5575983236764" target="_blank">Contato</a>
      </nav>
      <div class="perfil">
        <a href="/telas/tela_login/login.html">Login</a>
      </div>
    </header>

    <!-- ===== MAIN CONTENT ===== -->
    <main>
        <!-- Informações do Filme -->
        <section class="info-filme">
            <img src="" alt="Cartaz do filme" class="placeholder-img">
            <h2>Sala 1 - 2D</h2>
            <p>Horário: 23/05 às 14:00</p>
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
        <a href="https://www.facebook.com/SenacBahia/" target="_blank"
          >Facebook</a
        >
        <a href="https://www.instagram.com/senacbahia/" target="_blank"
          >Instagram</a
        >
        <a href="https://x.com/senacpituba" target="_blank">Twitter</a>
      </div>
      <div class="contato">
        <p>Email: contato@cinesenac.com</p>
        <p>Telefone: (75) 98336-6742</p>
      </div>
    </footer>

    <script>
        // Variáveis globais
        let quantidades = {
            inteira: 0,
            meia: 0
        };

        const precos = {
            inteira: 25.00,
            meia: 12.50
        };

        // Função para formatar preço
        function formatarPreco(valor) {
            return valor.toFixed(2).replace('.', ',');
        }

        // Função para atualizar interface
        function atualizarInterface() {
            const totalIngressos = quantidades.inteira + quantidades.meia;
            const totalPreco = (quantidades.inteira * precos.inteira) + (quantidades.meia * precos.meia);
            
            // Atualizar elementos
            document.getElementById('total-ingressos').textContent = totalIngressos;
            document.getElementById('total-preco').textContent = formatarPreco(totalPreco);
            document.getElementById('resumo-quantidade').textContent = totalIngressos;
            document.getElementById('resumo-total').textContent = formatarPreco(totalPreco);
            
            // Habilitar/desabilitar botão
            const btnFinalizar = document.getElementById('btn-finalizar');
            btnFinalizar.disabled = totalIngressos === 0;
        }

        // Função para alterar quantidade
        function alterarQuantidade(tipo, delta) {
            const novaQuantidade = quantidades[tipo] + delta;
            
            if (novaQuantidade >= 0) {
                quantidades[tipo] = novaQuantidade;
                atualizarInterface();
            }
        }

        // Função para finalizar compra
        //function finalizarCompra() {
            //const totalIngressos = quantidades.inteira + quantidades.meia;
            //const totalPreco = (quantidades.inteira * precos.inteira) + (quantidades.meia * precos.meia);
            
            //let detalhes = [];
            //if (quantidades.inteira > 0) {
               // detalhes.push(`${quantidades.inteira} ingresso(s) inteira  - R$ ${formatarPreco(quantidades.inteira * precos.inteira)}`);
           // }
            //if (quantidades.meia > 0) {
               // detalhes.push(`${quantidades.meia} ingresso(s) meia  - R$ ${formatarPreco(quantidades.meia * precos.meia)}`);
            //}

            //* Cria mensagem de confirmação
            //const mensagem = ` Bora para a parte boa crianças...O pagamento! 
            
//DETALHES DA COMPRA:
//${detalhes.join('\n')}

//TOTAL: R$ ${formatarPreco(totalPreco)}

//SESSÃO:
//Sala 1 - 2D
//Data: 23/05
//Horário: 14:00

//Obrigado pela preferência! `;
            
            //alert(mensagem); // Mostra popup com a mensagem
           // resetarCompra(); // Reseta a compra
      //  }

        // Função para resetar compra
        //function resetarCompra() {
          //  quantidades.inteira = 0;
           // quantidades.meia = 0;
           // atualizarInterface();
       // }

        // Inicializar interface
       // atualizarInterface();

// Função para finalizar compra
function finalizarCompra() {
    const totalIngressos = quantidades.inteira + quantidades.meia;
    const totalPreco = (quantidades.inteira * precos.inteira) + (quantidades.meia * precos.meia);

    // Cria objeto com os dados da compra
    const detalhes = {
        inteira: quantidades.inteira,
        meia: quantidades.meia,
        totalIngressos: totalIngressos,
        total: totalPreco
    };

    // Salva os dados no localStorage
    localStorage.setItem('detalhesCompra', JSON.stringify(detalhes));

    // Redireciona para a página de pagamento
    window.location.href = '/telas/tela_pagamento/pagamento.html';
}





    </script>
</body>

</html>