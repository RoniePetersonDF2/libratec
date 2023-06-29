const inputTelefone = document.getElementsByName("celular")[0];
inputTelefone.addEventListener("input", formatarTelefone);

function formatarTelefone(e) {
  var v = e.target.value.replace(/\D/g, "");
  v = v.slice(0, 11); // Limita o número de caracteres para 11
  v = v.replace(/^(\d{2})(\d)/g, "($1) $2"); // Adiciona o espaço após o DDD
  v = v.replace(/(\d{5})(\d)/, "$1-$2"); // Adiciona o traço entre o quinto e sexto dígitos
  e.target.value = v;
}
