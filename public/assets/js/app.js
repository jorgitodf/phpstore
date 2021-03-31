var base_url = window.location.origin+window.location.pathname;

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

function confirmar_limpar_carrinho()
{
    let e = document.getElementById('confirmar_limpar_carrinho');
    e.style.display = "inline";
}
function limpar_carrinho_off()
{
    let e = document.getElementById('confirmar_limpar_carrinho');
    e.style.display = "none";
}

function usar_outro_endereco()
{
    let e = document.getElementById('alterar_endereco_entrega');
    if (e.checked == true) {
        document.getElementById('outro_endereco').style.display = "block";
    } else {
        document.getElementById('outro_endereco').style.display = "none";
    }
    
}

function outro_endereco()
{
    axios.defaults.withCredentials = true;
    axios({
        method: 'post',
        url: '?r=outro_endereco',
        data: {
            endereco_alternativo: document.getElementById('endereco_alternativo').value,
            cidade: document.getElementById('cidade').value
        }
    }).then(function(response) {
            console.log(response.data);
        });
    
}


