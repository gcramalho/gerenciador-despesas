// Função para formatar moeda
const mascaraValor = (valor) => {
    // Remove pontos e vírgulas
    valor = valor.replace(/[^\d]/g, '');

    // Formata o valor na moeda brasileira
    const resultado = new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL',
        minimumFractionDigits: 2
    }).format(parseFloat(valor) / 100);

    return resultado;
};

// Função para remover a formatação antes de enviar o formulário
const removerFormatacao = (valor) => {

    valor = valor.replace(/[^\d,]/g, '');
    valor = valor.replace(',', '.');

    return parseFloat(valor)
};

/* const removerFormatacao = (valor) => {
    return valor.replace(/[^\d]/g, '') / 100;
}; */


// Aplica a máscara ao campo de valor
document.addEventListener('DOMContentLoaded', function () {
    const moedaInput = document.getElementById('valor');

    if (moedaInput) {
        // Formata o valor inicial (se houver)
        moedaInput.value = mascaraValor(moedaInput.value);

        // Formata o valor enquanto o usuário digita
        moedaInput.addEventListener('input', function (e) {
            e.target.value = mascaraValor(e.target.value);
        });
    }
});

// Remove a formatação antes de enviar o formulário
document.getElementById("formAddDespesa")?.addEventListener("submit", function (e) {
    const valorInput = document.getElementById("valor");
    valorInput.value = removerFormatacao(valorInput.value);
});

document.getElementById("formEditarDespesa")?.addEventListener("submit", function (e) {
    const valorInput = document.getElementById("valor");
    valorInput.value = removerFormatacao(valorInput.value);
});