window.addEventListener('load', start);

var base_url = window.location.origin+window.location.pathname;

function start() {
    validaDadosCadastroEndereco();
    validationUpdateUser();
}
function verDetalheCompra(id)
{
    let token = document.querySelector('[name="csrf_token"]').value;
    axios.defaults.headers.common['Authorization'] = token;

    axios.get('?r=detalhe-compra&id=' + id)
        .then(function(response) {
            if (response.status == 200) {
                createElementDetalhesPedidido(response.data);
            }
        }).catch((err) => {
            if (err.response.status == 404) {
                console.log(err.response.data.error);
            }
        });
}

function validaDadosCadastroEndereco()
{
    let form = document.querySelector('#form-cad-end');

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
                    redirect(base_url, "/");
                }, 4000)                
            }).catch((err) => {
                if (err.response.status == 403) {
                    $(".msgError").css("display", "block");
                    $(".msgError").html("<span>"+err.response.data.error+"</span>");
                    setTimeout(() => {
                        $(".msgError").css("display", "none");
                    }, 4000);
                } else if (err.response.status == 404) {
                    $(".msgError").css("display", "block");
                    $(".msgError").html("<span>"+err.response.data.error+"</span>");
                    setTimeout(() => {
                        $(".msgError").css("display", "none");
                    }, 4000);
                }
            });
        });
    }
}

function createElementDetalhesPedidido(data)
{
    const elem = document.querySelector('.rhc'); 

    const div = document.querySelector("#div-his-comp");
    div.remove();

    let div1 = document.createElement('div');
    div1.setAttribute("class", "col-sm-10 col-lg-10 col-md-10 offset-sm-1 offset-lg-1 offset-md-1");
        
    let h3 = document.createElement('h3');
    h3.textContent = "Detalhes do Pedido";

    let divCard = document.createElement('div');
    divCard.setAttribute("class", "card");     

    let divCardheader = document.createElement('div');
    divCardheader.setAttribute("class", "card-header d-flex flex-row justify-content-around");    

    let dDtPedido = document.createElement('div');
    let d1 = document.createElement('div');
    d1.textContent = "Data do Pedido";
    let d2 = document.createElement('div');
    let spanDtPed = document.createElement('span');
    spanDtPed.setAttribute("class", "negrito");
    spanDtPed.textContent = formataData(data.dados[0].data_compra);

    let dTotal = document.createElement('div');
    let d3 = document.createElement('div');
    d3.textContent = "Total";
    let d4 = document.createElement('div');
    let spanTotal = document.createElement('span');
    spanTotal.setAttribute("class", "negrito");
    spanTotal.textContent = numberToReal(data.dados[0].total);

    let dNumCompra = document.createElement('div');
    let d5 = document.createElement('div');
    let spanD2 = document.createElement('span');
    spanD2.setAttribute("class", "negrito");
    d5.textContent = "Número da Compra: ";
    spanD2.textContent = data.dados[0].codigo_compra;
    let d6= document.createElement('div');
    d6.setAttribute("class", "text-center my-1");
    const span = document.createElement('span');
    span.setAttribute("class", "badge "+data.dados[0].cor+"");
    span.textContent = data.dados[0].status;

    let divCardBody = document.createElement('div');
    divCardBody.setAttribute("class", "card-body");   

    let dCardBody = document.createElement('div');

    elem.appendChild(div1);
    div1.appendChild(h3);
    div1.appendChild(divCard);
    divCard.appendChild(divCardheader);

    divCardheader.appendChild(dDtPedido);
    dDtPedido.appendChild(d1);
    dDtPedido.appendChild(d2);
    d2.appendChild(spanDtPed);

    divCardheader.appendChild(dTotal);
    dTotal.appendChild(d3);
    dTotal.appendChild(d4);
    d4.appendChild(spanTotal);

    divCardheader.appendChild(dNumCompra);
    dNumCompra.appendChild(d5);
    d5.appendChild(spanD2);
    dNumCompra.appendChild(d6);
    d6.appendChild(span);

    divCard.appendChild(divCardBody);
    divCardBody.appendChild(dCardBody);

    let statusPg = data.dados[0].statuspagamento;
    statusPg.forEach(function(valor) {
        let divStPg = document.createElement('div');
        divStPg.setAttribute("class", "table table-hover col-sm-12 col-lg-12 col-md-12 hover");   
        divStPg.textContent = valor.nome_status + " em " + formataDataHora(valor.data_status) + " -> " + valor.mensagem_status;
        dCardBody.appendChild(divStPg);
    });

    let dCardBodyProds = document.createElement('div');
    divCardBody.appendChild(dCardBodyProds);

    let produtos = data.dados[0].produtos;
    produtos.forEach(function(valor) {
        let dCardBodyProds1 = document.createElement('div');
        dCardBodyProds1.setAttribute("class", "my-1 d-flex flex-nowrap align-self-center");  
        dCardBodyProds.appendChild(dCardBodyProds1);

        let divOrd1 = document.createElement('div');
        divOrd1.setAttribute("class", "order-1 p-2 align-self-center");
        let img = document.createElement("img");
        img.setAttribute('src', 'assets/images/produtos/'+valor.imagem);
        img.setAttribute("class", "img-fluid");  
        img.setAttribute("width", "50px"); 
        let divOrd2 = document.createElement('div');
        divOrd2.setAttribute("class", "order-2 p-2 align-self-center");
        divOrd2.textContent = valor.nome_produto
        let divOrd3 = document.createElement('div');
        divOrd3.setAttribute("class", "order-3 p-2 align-self-center");
        divOrd3.textContent = numberToReal(valor.preco_unidade);
        let divOrd4 = document.createElement('div');
        divOrd4.setAttribute("class", "order-4 p-2 align-self-center");
        divOrd4.textContent = "Quantidade: " + valor.quantidade;

        divOrd1.appendChild(img);
        dCardBodyProds1.appendChild(divOrd1);
        dCardBodyProds1.appendChild(divOrd2);
        dCardBodyProds1.appendChild(divOrd3);
        dCardBodyProds1.appendChild(divOrd4);
    });
}

function redirect(base_url, route) {
    return window.location.replace(base_url + route);
}

function numberToReal(valor) {
    let numero = parseFloat(valor).toFixed(2).split('.');
    numero[0] = "R$ " + numero[0].split(/(?=(?:...)*$)/).join('.');
    return numero.join(',');
}

function formataData(data) {
    dt = data.substring(10, 0);
    split = dt.split('-');
    novadata = split[2] + "/" + split[1] + "/" + split[0];
    return novadata;
}

function formataDataHora(data) {
    dt = data.substring(10, 0);
    hr = data.substring(11, 19);
    split = dt.split('-');
    novadata = split[2] + "/" + split[1] + "/" + split[0] + " às " + hr;
    return novadata;
}





function validationUpdateUser() {

    let form = document.querySelector('#form-update-user');

    if (form != null) {
        form.addEventListener('submit', function(event) {

            event.preventDefault();
       
            let nome = document.getElementById('nome').value;
            let email = document.getElementById('email').value;
            let public_place_id = document.getElementById('public_place_id').value;
            let complemento = document.getElementById('complemento').value;
            let numero = document.getElementById('numero').value;
            let bairro = document.getElementById('bairro').value;
            let cep = document.getElementById('cep').value;
            let uf = document.getElementById('uf').value;
            let tipo_endereco = document.getElementById('tipo_endereco').value;
    
            let data = {
                0: {
                    nome:nome, email: email, complemento: complemento, numero:numero, bairro: bairro, cep: cep, uf: uf, 
                    public_place_id: public_place_id
                },
                1: {
                    tipo_endereco: tipo_endereco
                }    
            }
    
            let token = document.querySelector('[name="_csrf_token"]').value;
            axios.defaults.headers.common['Authorization'] = token;
    
            axios({
                method: 'post',
                url: "?r=update-user",
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
                    //redirect(base_url, "/");
                }, 4000)                
            }).catch((err) => {
                if (err.response.status == 403) {
                    $(".msgError").css("display", "block");
                    $(".msgError").html("<span>"+err.response.data.error+"</span>");
                    setTimeout(() => {
                        $(".msgError").css("display", "none");
                    }, 4000);
                } else if (err.response.status == 404) {
                    $(".msgError").css("display", "block");
                    $(".msgError").html("<span>"+err.response.data.error+"</span>");
                    setTimeout(() => {
                        $(".msgError").css("display", "none");
                    }, 4000);
                }
            });
    
        });
    }


};
