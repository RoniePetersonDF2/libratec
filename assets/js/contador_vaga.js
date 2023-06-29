const descricaoVaga = document.getElementById('descricao-vaga');
const contadorVaga = document.getElementById('contador-vaga');

// Função para atualizar o contador da descrição da vaga
function atualizarContadorVaga() {
  const caracteresDigitados = descricaoVaga.value.length;
  contadorVaga.innerText = caracteresDigitados + ' / 255';

  // Verifica se excedeu o limite de caracteres
  if (caracteresDigitados >= 255) {
    contadorVaga.style.color = 'red';
    contadorVaga.style.fontSize = '0.9em';
  } else {
    contadorVaga.style.color = 'black';
    contadorVaga.style.fontSize = '0.9em';
  }
}

// Chama a função para exibir o contador inicialmente
atualizarContadorVaga();

// Adiciona o evento de input para atualizar o contador da descrição da vaga
descricaoVaga.addEventListener('input', atualizarContadorVaga);
