// Aguarda o carregamento completo do DOM
document.addEventListener("DOMContentLoaded", function () {
  // Elementos principais dos modais
  const modalSessao = document.getElementById("modalSessao");
const modalIngresso = document.getElementById("modalIngresso");
const modalRelatorio = document.getElementById("modalrelatorio");

  const modalFilme = document.getElementById("modalFilme");
  const botaoCadastrarFilme = document.getElementById("cadastrarfilme");
  const fecharFilme = document.querySelector(".fechar-filme");
  const modalTitulo = document.getElementById("titulo-modal-filme");
  const botaoEnviar = document.getElementById("botao-enviar");

  const botaoVerFilmes = document.getElementById("Verfilmes");
  const modalVerFilmes = document.getElementById("modalVerFilmes");
  const fecharVerFilmes = document.querySelector(".fechar-Verfilmes");

  const botaoalterarcadastro = document.getElementById("alterarcadastro");
  const modalalterarcadastro = document.getElementById("modalalterarcadastro");
  const fecharalterarcadastro = document.querySelector(".fechar-alterarcadastro");

  const modalConfirmar = document.getElementById("modalConfirmarExclusao");
  const botaoConfirmar = document.getElementById("confirmarExcluir");
  const botaoCancelar = document.getElementById("cancelarExcluir");

  const checkboxes = document.getElementById("checkboxes");
  const selectBox = document.querySelector(".custom-multiselect .select-box");
  const selectedSpan = document.getElementById("selected");
  const hiddenInput = document.getElementById("sub_genero_input");

  let linhaParaExcluir = null;

  // Abrir modal de cadastro
  botaoCadastrarFilme.onclick = () => {
    modalFilme.style.display = "block";
    modalTitulo.textContent = "Cadastro de Filmes";
    botaoEnviar.value = "Enviar";
    const form = document.forms[0];
    form.reset();
    selectedSpan.textContent = "Selecione os subgêneros";
    hiddenInput.value = "";
    checkboxes.querySelectorAll("input[type=checkbox]").forEach(cb => cb.checked = false);
  };

  // Fechar modal
  fecharFilme.onclick = () => modalFilme.style.display = "none";
  botaoVerFilmes.onclick = () => modalVerFilmes.style.display = "block";
  fecharVerFilmes.onclick = () => modalVerFilmes.style.display = "none";
  selectBox.onclick = () => checkboxes.classList.toggle("show");

  // Fechar modal
  botaoalterarcadastro.onclick = () => modalalterarcadastro.style.display = "block";
  fecharalterarcadastro.onclick = () => modalalterarcadastro.style.display = "none";
  selectBox.onclick = () => checkboxes.classList.toggle("show");


  // Fechar dropdowns ao clicar fora
  document.addEventListener("click", (e) => {
    if (!selectBox.contains(e.target) && !checkboxes.contains(e.target)) {
      checkboxes.classList.remove("show");
    }
  });

  // Subgêneros selecionados
  checkboxes.querySelectorAll("input[type=checkbox]").forEach(checkbox => {
    checkbox.addEventListener("change", () => {
      const selecionados = Array.from(checkboxes.querySelectorAll("input[type=checkbox]:checked")).map(cb => cb.value);
      selectedSpan.textContent = selecionados.length ? selecionados.join(", ") : "Selecione os subgêneros";
      hiddenInput.value = selecionados.join(",");
    });
  });

  // Editar filme
  document.querySelectorAll('button[title="Editar"]').forEach(botao => {
    botao.addEventListener("click", () => {
      const linha = botao.closest("tr");
      const [genero, titulo, classificacao, duracao] = [1, 2, 3, 4].map(i => linha.cells[i].textContent.trim());

      modalFilme.style.display = "block";
      modalTitulo.textContent = "Editar Filme";
      botaoEnviar.value = "Editar";

      const form = document.forms[0];
      form["titulo_filme"].value = titulo;
      form["classificacao_indicativa"].value = classificacao;
      form["genero"].value = genero;
      form["duracao"].value = duracao;
      form["sinopse"].value = "";
      form["trailer"].value = "";

      selectedSpan.textContent = "Selecione os subgêneros";
      hiddenInput.value = "";
      checkboxes.querySelectorAll("input[type=checkbox]").forEach(cb => cb.checked = false);
    });
  });

  // Excluir filme
  document.querySelectorAll('button[title="Excluir"]').forEach(botao => {
    botao.addEventListener("click", () => {
      linhaParaExcluir = botao.closest("tr");
      modalConfirmar.style.display = "block";
    });
  });

  botaoConfirmar.onclick = () => {
    if (linhaParaExcluir) linhaParaExcluir.remove();
    linhaParaExcluir = null;
    modalConfirmar.style.display = "none";
  };

  botaoCancelar.onclick = () => {
    linhaParaExcluir = null;
    modalConfirmar.style.display = "none";
  };

  // Fecha modais ao clicar fora deles
  window.addEventListener("click", (event) => {
    if ([modalFilme, modalVerFilmes, modalConfirmar, modalSessao, modalIngresso, modalRelatorio, modalalterarcadastro].includes(event.target)) {
      event.target.style.display = "none";
      if (event.target === modalConfirmar) linhaParaExcluir = null;
    }
  });

  // Contador sessões
  window.alterarContador = function (botao, direcao) {
    const span = botao.parentElement.querySelector(".valor");
    let valorAtual = parseInt(span.textContent);
    const novoValor = valorAtual + direcao;
    if (novoValor >= 0 && novoValor <= 4) span.textContent = novoValor;
  };

  // Utilitário para tipo de exibição (sessão, ingresso, relatório)
  function configurarTipoExibicao(wrapperId, checkboxName, selectedId) {
    const selectBox = document.querySelector(`#${wrapperId} .select-box`);
    const checkboxesContainer = document.getElementById(`${wrapperId}Checkboxes`);
    let expanded = false;

    selectBox.addEventListener("click", () => {
      expanded = !expanded;
      checkboxesContainer.style.display = expanded ? "block" : "none";
    });

    document.addEventListener("click", (e) => {
      if (!selectBox.contains(e.target) && !checkboxesContainer.contains(e.target)) {
        checkboxesContainer.style.display = "none";
        expanded = false;
      }
    });

    document.querySelectorAll(`.${wrapperId}Checkbox`).forEach((checkbox) => {
      checkbox.addEventListener("change", () => {
        document.getElementsByName(checkboxName).forEach(item => {
          if (item !== checkbox) item.checked = false;
        });

        const selected = document.getElementById(selectedId);
        selected.textContent = checkbox.checked ? checkbox.parentElement.textContent.trim() : "Selecione...";
      });
    });
  }

  // Sessão, Ingresso, Relatório
  configurarTipoExibicao("tipo_exibicao_sessao_wrapper", "tipo_exibicao_sessao", "selectedTipoExibicaoSessao");
  configurarTipoExibicao("tipo_exibicao_ingresso_wrapper", "tipo_exibicao_ingresso", "selectedTipoExibicaoIngresso");
  configurarTipoExibicao("tipo_exibicao_relatorio_wrapper", "tipo_exibicao_relatorio", "selectedTipoExibicaoRelatorio");
  

  // Modais de sessão, ingresso, relatório
  function configurarModal(botaoId, modalId, fecharClass) {
    const botao = document.getElementById(botaoId);
    const modal = document.getElementById(modalId);
    const fechar = document.querySelector(fecharClass);

    botao.onclick = () => modal.style.display = "block";
    fechar.onclick = () => modal.style.display = "none";
  }

  configurarModal("cadastrarsessao", "modalSessao", ".fechar-sessao");
  configurarModal("cadastraringresso", "modalIngresso", ".fechar-ingresso");
  configurarModal("relatoriovendas", "modalrelatorio", ".fechar-relatorio");
  configurarModal("alterarcadastro", "modalalterarcadastro", ".fechar-alterarcadastro");
});
