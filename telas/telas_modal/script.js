// Aguarda o carregamento completo do DOM antes de executar o script
document.addEventListener("DOMContentLoaded", function () {
    // Referência aos modais e elementos principais
    const modal = document.getElementById("modalFilme");
    const botaoAbrir = document.getElementById("cadastrarfilme");
    const fechar = document.querySelector(".fechar-filme");
    const modalTitulo = document.getElementById("titulo-modal-filme");
    const botaoEnviar = document.getElementById("botao-enviar");

    const botaoVerfilmes = document.getElementById("Verfilmes");
    const modalVerfilmes = document.getElementById("modalVerfilmes");
    const fecharVerfilmes = document.querySelector(".fechar-Verfilmes");

    const modalConfirmar = document.getElementById("modalConfirmarExclusao");
    const botaoConfirmar = document.getElementById("confirmarExcluir");
    const botaoCancelar = document.getElementById("cancelarExcluir");

    const checkboxes = document.getElementById("checkboxes");
    const selectBox = document.querySelector(".custom-multiselect .select-box");
    const selectedSpan = document.getElementById("selected");
    const hiddenInput = document.getElementById("sub_genero_input");

    let linhaParaExcluir = null; // Variável usada para armazenar a linha da tabela a ser excluída

    // Ao clicar em "Cadastrar Filme", exibe o modal de cadastro e limpa o formulário
    botaoAbrir.onclick = () => {
        modal.style.display = "block";
        modalTitulo.textContent = "Cadastro de Filmes";
        botaoEnviar.value = "Enviar";

        const form = document.forms[0];
        form.reset();

        // Reseta subgêneros selecionados
        selectedSpan.textContent = "Selecione os subgêneros";
        hiddenInput.value = "";
        checkboxes.querySelectorAll("input[type=checkbox]").forEach(cb => cb.checked = false);
    };

    // Fecha o modal de cadastro
    fechar.onclick = () => modal.style.display = "none";

    // Abre o modal com a lista de filmes
    botaoVerfilmes.onclick = () => modalVerfilmes.style.display = "block";

    // Fecha o modal da lista de filmes
    fecharVerfilmes.onclick = () => modalVerfilmes.style.display = "none";

    // Alterna a exibição dos checkboxes ao clicar na selectBox
    selectBox.onclick = toggleCheckboxes;

    // Fecha os checkboxes caso clique fora da área
    document.addEventListener("click", (e) => {
        if (!selectBox.contains(e.target) && !checkboxes.contains(e.target)) {
            checkboxes.style.display = "none";
        }
    });

    // Atualiza o span e o input oculto ao selecionar subgêneros
    checkboxes.querySelectorAll("input[type=checkbox]").forEach((checkbox) => {
        checkbox.addEventListener("change", () => {
            const selecionados = Array.from(checkboxes.querySelectorAll("input[type=checkbox]:checked"))
                .map(cb => cb.value);

            selectedSpan.textContent = selecionados.length ? selecionados.join(", ") : "Selecione os subgêneros";
            hiddenInput.value = selecionados.join(",");
        });
    });

    // Ao clicar no botão de editar, preenche o formulário com os dados da linha
    document.querySelectorAll('button[title="Editar"]').forEach((botao) => {
        botao.addEventListener('click', () => {
            const linha = botao.closest('tr');
            const genero = linha.cells[1].textContent.trim();
            const titulo = linha.cells[2].textContent.trim();
            const classificacao = linha.cells[3].textContent.trim();
            const duracao = linha.cells[4].textContent.trim();

            modal.style.display = "block";
            modalTitulo.textContent = "Editar Filme";
            botaoEnviar.value = "Editar";

            const form = document.forms[0];
            form["titulo_filme"].value = titulo;
            form["classificacao_indicativa"].value = classificacao;
            form["genero"].value = genero;
            form["duracao"].value = duracao;
            form["sinopse"].value = "";
            form["trailer"].value = "";

            // Limpa seleção de subgêneros para nova edição
            selectedSpan.textContent = "Selecione os subgêneros";
            hiddenInput.value = "";
            checkboxes.querySelectorAll("input[type=checkbox]").forEach(cb => cb.checked = false);
        });
    });

    // Exibe modal de confirmação ao clicar em "Excluir"
    document.querySelectorAll('button[title="Excluir"]').forEach((botao) => {
        botao.addEventListener('click', () => {
            linhaParaExcluir = botao.closest('tr');
            modalConfirmar.style.display = "block";
        });
    });

    // Confirma a exclusão da linha
    botaoConfirmar.onclick = () => {
        if (linhaParaExcluir) linhaParaExcluir.remove();
        linhaParaExcluir = null;
        modalConfirmar.style.display = "none";
    };

    // Cancela a exclusão
    botaoCancelar.onclick = () => {
        linhaParaExcluir = null;
        modalConfirmar.style.display = "none";
    };

    // Fecha modais ao clicar fora deles
    window.addEventListener("click", function (event) {
        if (event.target === modal) modal.style.display = "none";
        if (event.target === modalVerfilmes) modalVerfilmes.style.display = "none";
        if (event.target === modalConfirmar) {
            modalConfirmar.style.display = "none";
            linhaParaExcluir = null;
        }
    });
});

// Função para alternar visibilidade dos checkboxes de subgêneros
//let expanded = false;
function toggleCheckboxes() {
    const checkboxes = document.getElementById("checkboxes");
    //checkboxes.style.display = (checkboxes.style.display === "block") ? "none" : "block";
    checkboxes.classList.toggle("show");
    //expanded = !expanded;
}
document.addEventListener("DOMContentLoaded", () => {
    const selectedSpan = document.getElementById("selected");
    const checkboxes = document.querySelectorAll('input[name="filmes[]"]');

    checkboxes.forEach(cb => {
        cb.addEventListener('change', () => {
            const selected = Array.from(checkboxes)
                .filter(c => c.checked)
                .map(c => c.parentElement.textContent.trim());

            selectedSpan.textContent = selected.length > 0
                ? selected.join(", ")
                : "Selecione um ou mais Filmes:";
        });
    });
});

// Função para alterar contador (ex: botão de aumentar/diminuir sessões)
function alterarContador(botao, direcao) {
    const span = botao.parentElement.querySelector(".valor");
    let valorAtual = parseInt(span.textContent);
    const novoValor = valorAtual + direcao;
    if (novoValor >= 0 && novoValor <= 4) {
        span.textContent = novoValor;
    }
}

//CADASTRO DE SESSÃO
const modalSessao = document.getElementById("modalSessao"); // pega o modal
const botaoAbrirSessao = document.getElementById("cadastrarsessao"); // botão que abre o modal
const fecharSessao = document.querySelector(".fechar-sessao"); // botão "X" de fechar

// Abrir modal ao clicar no botão
botaoAbrirSessao.onclick = () => {
  modalSessao.style.display = "block";
};

// Fechar modal ao clicar no X
fecharSessao.onclick = () => {
  modalSessao.style.display = "none";
};

// Fechar modal ao clicar fora do conteúdo
window.addEventListener("click", (event) => {
  if (event.target === modalFilme) {
    modalFilme.style.display = "none";
  }
  if (event.target === modalVerFilmes) {
    modalVerFilmes.style.display = "none";
  }
});

// Função para permitir apenas uma seleção de tipo de exibição
function onlyOneSessao(checkbox) {
  const checkboxes = document.getElementsByName("tipo_exibicao_sessao");
  checkboxes.forEach((item) => {
    if (item !== checkbox) item.checked = false;
  });

  // Atualiza o texto do campo simulado
  const selected = document.getElementById("selectedTipoExibicaoSessao");
  selected.textContent = checkbox.checked
    ? checkbox.parentElement.textContent.trim()
    : "Selecione...";
}

// Toggle do "dropdown" customizado do tipo de exibição
let tipoExibicaoSessaoExpanded = false;
function toggleTipoExibicaoSessao() {
  const checkboxes = document.getElementById("tipoExibicaoSessaoCheckboxes");
  tipoExibicaoSessaoExpanded = !tipoExibicaoSessaoExpanded;
  checkboxes.style.display = tipoExibicaoSessaoExpanded ? "block" : "none";
}

// Fechar o dropdown de tipo de exibição ao clicar fora
document.addEventListener("click", (e) => {
  const selectBox = document.querySelector(
    "#tipo_exibicao_sessao_wrapper .select-box"
  );
  const checkboxes = document.getElementById("tipoExibicaoSessaoCheckboxes");

  if (!selectBox.contains(e.target) && !checkboxes.contains(e.target)) {
    checkboxes.style.display = "none";
    tipoExibicaoSessaoExpanded = false;
  }
});

// Gerenciar as seleções dos checkboxes de tipo de exibição (apenas um pode ser selecionado)
document.querySelectorAll(".tipoExibicaoSessaoCheckbox").forEach((checkbox) => {
  checkbox.addEventListener("change", () => {
    onlyOneSessao(checkbox);
  });
});



const botaoAbrirIngresso = document.getElementById("cadastraringresso"); // botão que abre o modal
const modalIngresso = document.getElementById("modalIngresso"); // pega o modal
const fecharIngresso = document.querySelector(".fechar-ingresso"); // botão "X" de fechar

// Abrir modal ao clicar no botão
botaoAbrirIngresso.onclick = () => {
  modalIngresso.style.display = "block";
};

// Fechar modal ao clicar no X
fecharIngresso.onclick = () => {
  modalIngresso.style.display = "none";
};

// Fechar modal ao clicar fora do conteúdo
window.addEventListener("click", (event) => {
  if (event.target === modalFilme) {
    modalFilme.style.display = "none";
  }
  if (event.target === modalVerFilmes) {
    modalVerFilmes.style.display = "none";
  }
});

// Função para permitir apenas uma seleção de tipo de exibição
function onlyOneIngresso(checkbox) {
  const checkboxes = document.getElementsByName("tipo_exibicao_ingresso");
  checkboxes.forEach((item) => {
    if (item !== checkbox) item.checked = false;
  });

  // Atualiza o texto do campo simulado
  const selected = document.getElementById("selectedTipoExibicaoIngresso");
  selected.textContent = checkbox.checked
    ? checkbox.parentElement.textContent.trim()
    : "Selecione...";
}

// Toggle do "dropdown" customizado do tipo de exibição
let tipoExibicaoIngressoExpanded = false;
function toggleTipoExibicaoIngresso() {
  const checkboxes = document.getElementById("tipoExibicaoIngressoCheckboxes");
  tipoExibicaoIngressoExpanded = !tipoExibicaoIngressoExpanded;
  checkboxes.style.display = tipoExibicaoIngressoExpanded ? "block" : "none";
}

// Fechar o dropdown de tipo de exibição ao clicar fora
document.addEventListener("click", (e) => {
  const selectBox = document.querySelector(
    "#tipo_exibicao_ingresso_wrapper .select-box"
  );
  const checkboxes = document.getElementById("tipoExibicaoIngressoCheckboxes");

  if (!selectBox.contains(e.target) && !checkboxes.contains(e.target)) {
    checkboxes.style.display = "none";
    tipoExibicaoIngressoExpanded = false;
  }
});

// Gerenciar as seleções dos checkboxes de tipo de exibição (apenas um pode ser selecionado)
document.querySelectorAll(".tipoExibicaoIngressoCheckbox").forEach((checkbox) => {
  checkbox.addEventListener("change", () => {
    onlyOneIngresso(checkbox);
  });
});


//BOTAO RELATORIO

const botaoAbriRelatorio = document.getElementById("relatoriovendas"); // botão que abre o modal
const modalRelatorio = document.getElementById("modalrelatorio"); // pega o modal
const fecharRelatorio = document.querySelector(".fechar-relatorio"); // botão "X" de fechar

// Abrir modal ao clicar no botão
botaoAbriRelatorio.onclick = () => {
  modalRelatorio.style.display = "block";
};

// Fechar modal ao clicar no X
fecharRelatorio.onclick = () => {
  modalRelatorio.style.display = "none";
};

// Fechar modal ao clicar fora do conteúdo
window.addEventListener("click", (event) => {
  if (event.target === modalFilme) {
    modalFilme.style.display = "none";
  }
  if (event.target === modalVerFilmes) {
    modalVerFilmes.style.display = "none";
  }
});

// Função para permitir apenas uma seleção de tipo de exibição
function onlyOneRelatorio(checkbox) {
  const checkboxes = document.getElementsByName("tipo_exibicao_relatorio");
  checkboxes.forEach((item) => {
    if (item !== checkbox) item.checked = false;
  });

  // Atualiza o texto do campo simulado
  const selected = document.getElementById("selectedTipoExibicaoRelatorio");
  selected.textContent = checkbox.checked
    ? checkbox.parentElement.textContent.trim()
    : "Selecione...";
}

// Toggle do "dropdown" customizado do tipo de exibição
let tipoExibicaoRelatorioExpanded = false;
function toggleTipoExibicaoRelatorio() {
  const checkboxes = document.getElementById("tipoExibicaoRelatorioCheckboxes");
  tipoExibicaoRelatorioExpanded = !tipoExibicaoRelatorioExpanded;
  checkboxes.style.display = tipoExibicaoRelatorioExpanded ? "block" : "none";
}

// Fechar o dropdown de tipo de exibição ao clicar fora
document.addEventListener("click", (e) => {
  const selectBox = document.querySelector(
    "#tipo_exibicao_relatorio_wrapper .select-box"
  );
  const checkboxes = document.getElementById("tipoExibicaoRelatorioCheckboxes");

  if (!selectBox.contains(e.target) && !checkboxes.contains(e.target)) {
    checkboxes.style.display = "none";
    tipoExibicaoRelatorioExpanded = false;
  }
});

// Gerenciar as seleções dos checkboxes de tipo de exibição (apenas um pode ser selecionado)
document.querySelectorAll(".tipoExibicaoRelatorioCheckbox").forEach((checkbox) => {
  checkbox.addEventListener("change", () => {
    onlyOneRelatorio(checkbox);
  });
});






/*
  const calendario = document.getElementById("calendario-relatorio");
  const mesAno = document.getElementById("mesAnoAtual-relatorio");
  const datasSelecionadas = [];

  let dataAtual = new Date();

  function renderizarCalendario() {
    calendario.innerHTML = "";
    const ano = dataAtual.getFullYear();
    const mes = dataAtual.getMonth();

    const primeiroDia = new Date(ano, mes, 1).getDay();
    const ultimoDia = new Date(ano, mes + 1, 0).getDate();

    mesAno.textContent = `${dataAtual.toLocaleString('pt-BR', { month: 'long' })} ${ano}`;

    for (let i = 0; i < primeiroDia; i++) {
      const vazio = document.createElement("div");
      vazio.style.width = "40px";
      calendario.appendChild(vazio);
    }

    for (let dia = 1; dia <= ultimoDia; dia++) {
      const btn = document.createElement("button");
      btn.textContent = dia;
      btn.style.padding = "10px";
      btn.style.border = "1px solid #ccc";
      btn.style.borderRadius = "4px";
      btn.style.cursor = "pointer";
      btn.dataset.dataCompleta = `${ano}-${String(mes + 1).padStart(2, "0")}-${String(dia).padStart(2, "0")}`;

      btn.addEventListener("click", () => {
        const data = btn.dataset.dataCompleta;
        const index = datasSelecionadas.indexOf(data);

        if (index === -1) {
          datasSelecionadas.push(data);
          btn.style.background = "#4caf50";
          btn.style.color = "#fff";
        } else {
          datasSelecionadas.splice(index, 1);
          btn.style.background = "";
          btn.style.color = "";
        }

        // Atualiza o campo hidden
        document.getElementById("datasRelatorioSelecionadas").value = JSON.stringify(datasSelecionadas);
      });

      calendario.appendChild(btn);
    }
  }

  document.getElementById("btnAnterior-relatorio").addEventListener("click", () => {
    dataAtual.setMonth(dataAtual.getMonth() - 1);
    renderizarCalendario();
  });

  document.getElementById("btnProximo-relatorio").addEventListener("click", () => {
    dataAtual.setMonth(dataAtual.getMonth() + 1);
    renderizarCalendario();
  });

  renderizarCalendario();
*/


