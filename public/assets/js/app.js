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

function redirect(base_url, route) {
    return window.location.replace(base_url + route);
}


window.addEventListener('load', ()=> {

    /** Validação dos Dados do Cadastro de Endereço */
    let form = document.querySelector('#form-cad-end');

    
    var base_url = window.location.origin+window.location.pathname;

    if (form != null) {
        form.addEventListener('submit', (e)=> {

            e.preventDefault();
    
            let public_place_id = document.getElementById('public_place_id').value;
            let tipo_endereco = document.getElementById('tipo_endereco').value;
            let complemento = document.getElementById('complemento').value;
            let numero = document.getElementById('numero').value;
            let bairro = document.getElementById('bairro').value;
            let cep = document.getElementById('cep').value;
            let uf = document.getElementById('uf').value;
    
            let data = {
                0: {
                    complemento: complemento, numero:numero, bairro: bairro, cep: cep, uf: uf, 
                    public_place_id: public_place_id
                },
                1: {
                    tipo_endereco: tipo_endereco
                }    
            }
    
            axios.defaults.withCredentials = true;
            
            axios({
                method: 'post',
                url: "?r=criar-endereco",
                data: JSON.stringify(data),
                dataType: 'JSON',
                headers: {
                    'Accept': 'application/json, text/plain, */*',
                    'Content-Type': 'application/json;charset=utf-8'
                }
            }).then((res) => {
                $(".msgSuccess").css("display", "block");
                $(".msgSuccess").html("<span>"+res.data.success+"</span>");
                setTimeout(() => {
                    $(".msgSuccess").css("display", "none");
                }, 4000)                
            }).catch((err) => {

                if (err.response.status == 403) {
                    $(".msgError").css("display", "block");
                    $(".msgError").html("<span>"+err.response.data.error+"</span>");
                    setTimeout(() => {
                        $(".msgError").css("display", "none");
                    }, 4000)
                } else if (err.response.status == 404) {
                    $(".msgError").css("display", "block");
                    $(".msgError").html("<span>"+err.response.data.error+"</span>");
                    setTimeout(() => {
                        $(".msgError").css("display", "none");
                    }, 4000)
                    // setInterval(function() {
                    //     redirect(base_url, "/");
                    // }, 4000);
                }

            });
        });
    }

});


