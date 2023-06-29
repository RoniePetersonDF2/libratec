const cpfInput = document.getElementsByName("cpf")[0];
cpfInput.addEventListener("input", formatarCPF);

function formatarCPF() {
  let cpf = cpfInput.value.replace(/\D/g, ""); // Remove todos os caracteres não numéricos

  if (cpf.length > 11) {
    cpf = cpf.slice(0, 11); // Limita o campo a 11 caracteres
  }

  let cpfFormatado = "";
  for (let i = 0; i < cpf.length; i++) {
    if (i === 3 || i === 6) {
      cpfFormatado += ".";
    } else if (i === 9) {
      cpfFormatado += "-";
    }
    cpfFormatado += cpf[i];
  }

  cpfInput.value = cpfFormatado;
}
