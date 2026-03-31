// Os blocos fixos, mode ser mudado depois
const blocosFixos = [
  { inicio: "08:00", fim: "10:00", nome: "Manhã 1" },
  { inicio: "10:00", fim: "12:00", nome: "Manhã 2" },
  { inicio: "14:00", fim: "16:00", nome: "Tarde 1" },
  { inicio: "16:00", fim: "18:00", nome: "Tarde 2" },
];

// funcao 1: monta a grade
function montarGradeCinema() {
  const salaInput = document.getElementById("salaBusca");
  const dataInput = document.getElementById("dataBusca");

  if (!salaInput || !dataInput) return;

  const salaEscolhida = salaInput.value;
  const dataEscolhida = dataInput.value;

  if (!dataEscolhida) return;

  const containerGrade = document.getElementById("gradeCinema");
  containerGrade.innerHTML =
    '<p class="text-center text-muted w-100">Buscando horários...</p>'; // Volta a mensagem de loading

  fetch("api.php")
    .then((res) => res.json())
    .then((todasReservas) => {
      const reservasDaSalaHj = todasReservas.filter(
        (r) =>
          r.sala === salaEscolhida &&
          r.data === dataEscolhida &&
          r.status !== "cancelada",
      );

      containerGrade.innerHTML = ""; // limpa a mensagem de carregando

      blocosFixos.forEach((bloco) => {
        const div = document.createElement("div");
        div.className = "col-6";

        const reservaOcupando = reservasDaSalaHj.find(
          (r) => r.horaInicio === bloco.inicio,
        );

        // pega as variáveis globais com segurança
        const papelUsuario =
          typeof papelAtual !== "undefined" ? papelAtual : "cliente";

        if (reservaOcupando) {
          let textoOcupado =
            papelUsuario === "admin"
              ? `Ocupado por ${reservaOcupando.nome}`
              : "Horário Indisponível";

          div.innerHTML = `
            <div class="bloco-horario bloco-ocupado p-3 mb-3 border rounded text-center" style="background-color: #f8d7da; color: #721c24;">
                <strong>${bloco.nome}</strong><br>
                <small>${bloco.inicio} às ${bloco.fim}</small><br>
                <span class="badge bg-light text-danger mt-1">${textoOcupado}</span>
            </div>
          `;
        } else {
          div.innerHTML = `
            <div class="bloco-horario bloco-livre p-3 mb-3 border rounded text-center" style="background-color: #d4edda; color: #155724; cursor: pointer;" onclick="fazerReservaClick('${salaEscolhida}', '${dataEscolhida}', '${bloco.inicio}', '${bloco.fim}')">
                <strong>${bloco.nome}</strong><br>
                <small>${bloco.inicio} às ${bloco.fim}</small><br>
                <span class="badge bg-light text-success mt-1">Livre - Clique para Reservar</span>
            </div>
          `;
        }
        containerGrade.appendChild(div);
      });

      atualizarListaLateral(todasReservas);
    })
    .catch((erro) => {
      console.error("Erro ao buscar a grade:", erro);
      containerGrade.innerHTML =
        '<p class="text-danger text-center w-100">Erro ao carregar a grade.</p>';
    });
}

// funcao 2: quando usuario clica no verde
function fazerReservaClick(sala, data, horaInicio, horaFim) {
  // Verifica se o nome existe e impede a reserva se não houver usuário logado
  if (
    typeof usuarioAtual === "undefined" ||
    !usuarioAtual ||
    usuarioAtual === ""
  ) {
    alert("Erro: Você precisa estar logado corretamente para reservar.");
    return;
  }

  if (
    !confirm(
      `Deseja confirmar a reserva da ${sala} no dia ${data} das ${horaInicio} às ${horaFim}?`,
    )
  )
    return;

  fetch("api.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      nome: usuarioAtual,
      sala: sala,
      data: data,
      horaInicio: horaInicio,
      horaFim: horaFim,
    }),
  }).then(() => {
    montarGradeCinema(); // recarrega a grade para pintar o bloco de vermelho
  });
}

// funcao 3: atualiza a lista lateral e os botões de cancelar
function atualizarListaLateral(todasReservas) {
  const ul = document.getElementById("listaReservas");
  ul.innerHTML = "";

  const papelSeguro =
    typeof papelAtual !== "undefined" ? papelAtual : "cliente";
  const usuarioSeguro = typeof usuarioAtual !== "undefined" ? usuarioAtual : "";

  // se for cliente, mostra so as reservas dele / pro admin mostra tudo
  let reservasParaMostrar = todasReservas;
  if (papelSeguro === "cliente") {
    reservasParaMostrar = todasReservas.filter((r) => r.nome === usuarioSeguro);
  }

  if (reservasParaMostrar.length === 0) {
    ul.innerHTML =
      '<li class="list-group-item text-muted text-center">Nenhuma reserva encontrada.</li>';
    return;
  }

  reservasParaMostrar.forEach((item) => {
    const li = document.createElement("li");
    li.className =
      "list-group-item d-flex justify-content-between align-items-center";

    // Oculta o botão por padrão
    let botaoCancelar = "";

    // REGRA: Só mostra botão se for Admin OU se o nome na reserva for o do usuário atual
    const souDono = item.nome === usuarioSeguro;
    const souAdmin = papelSeguro === "admin";

    if (item.status !== "cancelada" && (souAdmin || souDono)) {
      botaoCancelar = `<button class="btn btn-sm btn-outline-danger ms-2" onclick="cancelarReserva(${item.id})">Cancelar</button>`;
    }

    let texto = `<div><strong>${item.sala}</strong> - ${item.data} (${item.horaInicio})<br><small>Por: ${item.nome}</small></div>`;

    if (item.status === "cancelada") {
      texto = `<div><del class="text-muted"><strong>${item.sala}</strong> - ${item.data} (${item.horaInicio})</del><br><small>Por: ${item.nome}</small></div> <span class="badge bg-danger rounded-pill">Cancelada</span>`;
    } else {
      texto += botaoCancelar;
    }

    li.innerHTML = texto;
    ul.appendChild(li);
  });
}

// funcao 4: cancelar
function cancelarReserva(id) {
  if (confirm("Tem certeza que deseja cancelar esta reserva?")) {
    fetch("api.php", {
      method: "PUT",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id: id }),
    }).then(() => montarGradeCinema());
  }
}

// quando abre a tela monta a grade com a data de hoje
window.onload = montarGradeCinema;
