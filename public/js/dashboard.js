let despesaIdExcluir = null;

// Abrir modal e armazenar id da despesa a ser excluída
function confirmarExclusao(id)
{
    despesaIdExcluir = id
    const janela = new bootstrap.Modal(document.getElementById('deletarModal'))
    janela.show()
}

// Redirecionar para a rota de exclusão
function excluirDespesa()
{
    if(despesaIdExcluir){
        window.location.href = `index.php?acao=excluir_despesas&id=${despesaIdExcluir}`
    }
}

// Fechar modal
function fecharModal()
{
    const janela = new bootstrap.Modal.getInstance(document.getElementById('deletarModal'))
    janela.hide()
    despesaIdExcluir = null
}


// Fecha se clicar fora do modal
document.getElementById('deletarModal').addEventListener('click', function (e) {
    if (e.target === this) {
        fecharModal()
    }
})