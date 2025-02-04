let despesaIdExcluir = null;

function confirmarExclusao(id)
{
    despesaIdExcluir = id;
    document.getElementById('deletarModal').style.display = 'flex';
}

function excluirDespesa()
{
    if(despesaIdExcluir){
        window.location.href = `index.php?acao=excluir_despesas&id=${despesaIdExcluir}`;
    }
}

function fecharModal()
{
    document.getElementById('deletarModal').style.display = 'none';
    despesaIdExcluir = null;
}


// Fecha janela se clicar fora
window.onclick = function(event) {
    const modal = document.getElementById('deletarModal');
    if(event.target === modal){
        fecharModal();
    }
}