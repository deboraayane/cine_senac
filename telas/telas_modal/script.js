 // Modal filme
        const modal = document.getElementById("modalFilme");
        const botaoAbrir = document.getElementById("cadastrarfilme");
        const fechar = document.querySelector(".fechar-filme");
        const modalTitulo = document.getElementById("titulo-modal-filme");
        const botaoEnviar = document.getElementById("botao-enviar");

        // Abrir modal para cadastrar filme
        botaoAbrir.onclick = () => {
            modal.style.display = "block";
            modalTitulo.textContent = "Cadastro de Filmes";
            botaoEnviar.value = "Enviar";

            // Limpar formulário
            const form = document.forms[0];
            form.reset();

            document.getElementById("selected").textContent = "Selecione os subgêneros";
            document.getElementById("sub_genero_input").value = "";
            document.querySelectorAll("#checkboxes input[type=checkbox]").forEach(cb => cb.checked = false);
        };

        // Fechar modal filme
        fechar.onclick = () => {
            modal.style.display = "none";
        };

        // Modal biblioteca de filmes
        const botaoVerfilmes = document.getElementById("Verfilmes");
        const modalVerfilmes = document.getElementById("modalVerfilmes");
        const fecharVerfilmes = document.querySelector(".fechar-Verfilmes");

        botaoVerfilmes.onclick = () => modalVerfilmes.style.display = "block";
        fecharVerfilmes.onclick = () => modalVerfilmes.style.display = "none";

        // Fechar modais clicando fora
        window.addEventListener("click", (event) => {
            if (event.target === modal) modal.style.display = "none";
            if (event.target === modalVerfilmes) modalVerfilmes.style.display = "none";
        });

        // Custom multiselect subgêneros
        const selectBox = document.querySelector(".custom-multiselect .select-box");
        const checkboxes = document.getElementById("checkboxes");
        const selectedSpan = document.getElementById("selected");
        const hiddenInput = document.getElementById("sub_genero_input");

        function toggleCheckboxes() {
            if (checkboxes.style.display === "block") {
                checkboxes.style.display = "none";
            } else {
                checkboxes.style.display = "block";
            }
        }

        document.addEventListener("click", (e) => {
            if (!selectBox.contains(e.target) && !checkboxes.contains(e.target)) {
                checkboxes.style.display = "none";
            }
        });

        checkboxes.querySelectorAll("input[type=checkbox]").forEach((checkbox) => {
            checkbox.addEventListener("change", () => {
                const selecionados = [];
                checkboxes.querySelectorAll("input[type=checkbox]:checked").forEach((checked) => {
                    selecionados.push(checked.value);
                });

                if (selecionados.length > 0) {
                    selectedSpan.textContent = selecionados.join(", ");
                } else {
                    selectedSpan.textContent = "Selecione os subgêneros";
                }

                hiddenInput.value = selecionados.join(",");
            });
        });

        // Botões editar filmes
        const botoesEditar = document.querySelectorAll('button[title="Editar"]');

        botoesEditar.forEach((botao) => {
            botao.addEventListener('click', () => {
                const linha = botao.closest('tr');
                const genero = linha.cells[1].textContent.trim();
                const titulo = linha.cells[2].textContent.trim();
                const classificacao = linha.cells[3].textContent.trim();
                const duracao = linha.cells[4].textContent.trim();

                // Abrir modal com dados preenchidos para edição
                modal.style.display = "block";
                modalTitulo.textContent = "Editar Filme";
                botaoEnviar.value = "Editar";

                const form = document.forms[0];
                form["titulo_filme"].value = titulo;
                form["classificacao_indicativa"].value = classificacao;
                form["genero"].value = genero;
                form["duracao"].value = duracao;

                // Limpar campos opcionais por enquanto
                form["sinopse"].value = "";
                form["trailer"].value = "";
                document.getElementById("sub_genero_input").value = "";
                document.getElementById("selected").textContent = "Selecione os subgêneros";
                document.querySelectorAll("#checkboxes input[type=checkbox]").forEach(cb => cb.checked = false);
            });
        });

      document.addEventListener('DOMContentLoaded', function () {
    // Seleciona todos os botões com título "Excluir"
    const botoesExcluir = document.querySelectorAll('button[title="Excluir"]');

    botoesExcluir.forEach(function(botao) {
        botao.addEventListener('click', function() {
            const linha = botao.closest('tr'); // encontra a linha da tabela
            if (linha) {
                linha.remove(); // remove a linha
            }
        });
    });
});
