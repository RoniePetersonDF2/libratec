const descricaoExperiencia = document.getElementById('descricao-experiencia');
const contadorExperiencia = document.getElementById('contador-experiencia');

// Função para atualizar o contador da descrição da experiência
function atualizarContadorExperiencia() {
  const caracteresDigitados = descricaoExperiencia.value.length;
  contadorExperiencia.innerText = caracteresDigitados + ' / 255';

  // Verifica se excedeu o limite de caracteres
  if (caracteresDigitados >= 255) {
    contadorExperiencia.style.color = 'red';
    contadorExperiencia.style.fontSize = '0.9em';
  } else {
    contadorExperiencia.style.color = 'black';
    contadorExperiencia.style.fontSize = '0.9em';
  }
}

// Chama a função para exibir o contador inicialmente
atualizarContadorExperiencia();

// Adiciona o evento de input para atualizar o contador da descrição da experiência
descricaoExperiencia.addEventListener('input', atualizarContadorExperiencia);