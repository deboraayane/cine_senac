function validarFormulario(event) {
    const titulo = document.forms[0]["titulo_filme"].value.trim();
    const classificacao = document.forms[0]["classificacao_indicativa"].value;
    const genero = document.forms[0]["genero"].value;
    const duracao = document.forms[0]["duracao"].value;
    const sinopse = document.forms[0]["sinopse"].value.trim();
    const trailer = document.forms[0]["trailer"].value.trim();

    if (!titulo || !classificacao || !genero || !duracao || !sinopse) {
        alert("Por favor, preencha todos os campos obrigatórios.");
        event.preventDefault();
        return false;
    }

    if (trailer && !trailer.startsWith("http")) {
        alert("Por favor, insira uma URL válida para o trailer.");
        event.preventDefault();
        return false;
    }

    // Alerta de confirmação antes de enviar
    if (!confirm("Deseja realmente enviar o cadastro do filme?")) {
        event.preventDefault();
        return false;
    }
}

window.onload = function () {
    document.forms[0].addEventListener("submit", validarFormulario);
};


let expanded = false;

function toggleCheckboxes() {
    const checkboxes = document.getElementById("checkboxes");
    checkboxes.style.display = expanded ? "none" : "block";
    expanded = !expanded;
}

document.addEventListener("click", function (e) {
    const container = document.querySelector(".custom-multiselect");
    if (!container.contains(e.target)) {
        document.getElementById("checkboxes").style.display = "none";
        expanded = false;
    }
});

// Atualiza o texto e o input hidden com os valores selecionados
const checkboxes = document.querySelectorAll("#checkboxes input[type=checkbox]");
checkboxes.forEach(cb =>
    cb.addEventListener("change", () => {
        const selected = Array.from(checkboxes)
        .filter(i => i.checked)
        .map(i => i.value);

        document.getElementById("selected").innerText = selected.length > 0
        ? selected.join(', ')
        : 'Selecione os subgêneros';

        document.getElementById("sub_genero_input").value = JSON.stringify(selected);
    })
);

