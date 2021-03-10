function adicionar_carrinho(id_produto)
{
    axios.defaults.withCredentials = true;
    axios.get('?r=adicionar_carrinho&id_produto=' + id_produto)
        .then(function(response) {
            let total_produtos = response.data;
            document.getElementById('carrinho').innerText = total_produtos;
            console.log(response.data);
        });
}

function limpar_carrinho()
{
    window.location.reload();    
    axios.defaults.withCredentials = true;
    axios.get('?r=limpar_carrinho')
        .then(function(response) {
            document.getElementById('carrinho').innerText = 0;
        });
    
}