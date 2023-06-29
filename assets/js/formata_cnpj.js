const cnpjInput = document.getElementsByName("cnpj")[0];
cnpjInput.addEventListener("input", formatarCNPJ);

function formatarCNPJ() {
  let cnpj = cnpjInput.value.replace(/\D/g, ""); // Remove todos os caracteres não numéricos

  if (cnpj.length > 14) {
    cnpj = cnpj.slice(0, 14); // Limita o campo a 14 caracteres
  }

  let cnpjFormatado = "";
  for (let i = 0; i < cnpj.length; i++) {
    if (i === 2 || i === 5) {
      cnpjFormatado += ".";
    } else if (i === 8) {
      cnpjFormatado += "/";
    } else if (i === 12) {
      cnpjFormatado += "-";
    }
    cnpjFormatado += cnpj[i];
  }

  cnpjInput.value = cnpjFormatado;
}
