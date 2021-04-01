<?php

namespace core\controllers;

use core\classes\Functions;
use core\classes\SendEmail;
use core\classes\Carrinho as Cart;
use core\models\Products;
use core\models\Purchasing;
use core\models\PurchasingStatus;
use core\models\PaymentStatus;
use core\models\Users;
use DateTime;

class Carrinho
{
    private $produto;
    private $cliente;
    private $email;
    private $purchasing;
    private $purchasingStatus;
    private $paymentStatus;

    public function __construct()
    {
        $this->produto = new Products();
        $this->cliente = new Users();
        $this->email = new SendEmail();
        $this->purchasing = new Purchasing();
        $this->purchasingStatus = new PurchasingStatus();
        $this->paymentStatus = new PaymentStatus();
    }

    public function carrinho()
    {
        if (!isset($_SESSION['carrinho']) || count($_SESSION['carrinho']) == 0) {
            $dados = [
                'carrinho' => null
            ];
        } else {
            $ids = [];

            foreach ($_SESSION['carrinho'] as $id_produto => $quantidade) {
                array_push($ids, filter_var($id_produto, FILTER_SANITIZE_NUMBER_INT));
            }

            $ids = implode(",", $ids);

            $res = $this->produto->buscar_produtos_por_id($ids);

            $dados_tmp = [];

            foreach ($_SESSION['carrinho'] as $id_produto => $quantidade_carrinho) {
                foreach ($res as $produto) {
                    if ($produto->id == $id_produto) {
                        $id_produto = $produto->id;
                        $imagem = $produto->imagem;
                        $titulo = $produto->nome_produto;
                        $quantidade = $quantidade_carrinho;
                        $preco = $produto->preco * $quantidade;

                        array_push($dados_tmp, [
                            'id_produto' => $id_produto,
                            'imagem' => $imagem,
                            'titulo' => $titulo,
                            'quantidade' => $quantidade,
                            'preco' => $preco,
                        ]);
                        break;
                    }
                }
            }

            $total = 0;

            foreach ($dados_tmp as $item) {
                $total += $item['preco'];
            }

            array_push($dados_tmp, $total);

            $dados = [
                'carrinho' => $dados_tmp
            ];
        }

        Functions::Layout([
            'layouts/html_header',
            'layouts/header',
            'carrinho',
            'layouts/footer',
            'layouts/html_footer',
        ], $dados);
    }


    public function adicionar_carrinho()
    {
        if (!isset($_GET['id_produto'])) {
            isset($_SESSION['carrinho']) ? count($_SESSION['carrinho']) : '';
            return;
        }

        $id_produto = trim(filter_var($_GET['id_produto'], FILTER_SANITIZE_NUMBER_INT));

        if (!$this->produto->verificar_produto_estoque($id_produto)) {
            isset($_SESSION['carrinho']) ? count($_SESSION['carrinho']) : '';
            return;
        }

        $carrinho = [];

        if (isset($_SESSION['carrinho'])) {
            $carrinho = $_SESSION['carrinho'];
        }

        if (key_exists($id_produto, $carrinho)) {
            $carrinho[$id_produto]++;
        } else {
            $carrinho[$id_produto] = 1;
        }

        $_SESSION['carrinho'] = $carrinho;

        $total_produtos = 0;

        foreach ($carrinho as $produto) {
            $total_produtos += $produto;
        }

        echo $total_produtos;
    }

    public function limpar_carrinho()
    {
        unset($_SESSION['carrinho']);
        unset($_SESSION['codigo_compra']);
        $this->carrinho();
    }

    public function esvaziarCarrinho()
    {
        unset($_SESSION['carrinho']);
        unset($_SESSION['codigo_compra']);
    }

    public function remover_produto_carrinho()
    {
        $id_produto = trim(filter_var($_GET['id_produto'], FILTER_SANITIZE_NUMBER_INT));

        if (isset($_SESSION['carrinho'])) {
            $carrinho = $_SESSION['carrinho'];
            unset($carrinho[$id_produto]);
            $_SESSION['carrinho'] = $carrinho;
        }

        $this->carrinho();
    }

    public function finalizar_compra()
    {
        if (!isset($_SESSION['id_cliente'])) {
            $_SESSION['tmp_carrinho'] = true;
            Functions::redirect('login');
        } else {
            Functions::redirect('finalizar_compra_resumo');
        }
    }

    public function finalizar_compra_resumo()
    {
        if (!isset($_SESSION['id_cliente'])) {
            Functions::redirect();
        }

        $ids = [];
        foreach ($_SESSION['carrinho'] as $id_produto => $quantidade) {
            array_push($ids, filter_var($id_produto, FILTER_SANITIZE_NUMBER_INT));
        }
        $ids = implode(",", $ids);
        $res = $this->produto->buscar_produtos_por_id($ids);

        $dados_tmp = [];
        foreach ($_SESSION['carrinho'] as $id_produto => $quantidade_carrinho) {
            foreach ($res as $produto) {
                if ($produto->id == $id_produto) {
                    $id_produto = $produto->id;
                    $imagem = $produto->imagem;
                    $titulo = $produto->nome_produto;
                    $quantidade = $quantidade_carrinho;
                    $preco = $produto->preco * $quantidade;

                    array_push($dados_tmp, [
                        'id_produto' => $id_produto,
                        'imagem' => $imagem,
                        'titulo' => $titulo,
                        'quantidade' => $quantidade,
                        'preco' => $preco,
                    ]);
                    break;
                }
            }
        }

        $total = 0;
        foreach ($dados_tmp as $item) {
            $total += $item['preco'];
        }
        array_push($dados_tmp, $total);

        $_SESSION['total_compra'] = $total;

        $dados = [];
        $dados = ['carrinho' => $dados_tmp];

        $dados_cliente = $this->cliente->buscar_dados_cliente($_SESSION['id_cliente']);
        $dados['cliente'] = $dados_cliente;

        if (!isset($_SESSION['codigo_compra'])) {
            $codigo_compra = Functions::gerarCódigoCompra();
            $_SESSION['codigo_compra'] = $codigo_compra;
        }

        $dados['codigo_compra'] = $codigo_compra;

        Functions::Layout([
            'layouts/html_header',
            'layouts/header',
            'compra_resumo',
            'layouts/footer',
            'layouts/html_footer',
        ], $dados);
    }

    public function confirmar_compra()
    {
        /** Busca os produtos que estão no carrinho pelo Id na base de dados */
        $ids = [];
        foreach ($_SESSION['carrinho'] as $id_produto => $quantidade) {
            array_push($ids, filter_var($id_produto, FILTER_SANITIZE_NUMBER_INT));
        }
        $ids = implode(",", $ids);
        $results = $this->produto->buscar_produtos_por_id($ids);

        /** Lista dos produtos que estão no carrinho para preparar para o envio do e-mail da confirmação da compra */
        $dados_compra = Cart::mountListPurschasing($results);
        

        /** Envia o e-mail com os dados da Confirmação da Compra e com os dados para o pagamento */
        $this->email->enviar_email_confirmacao_compra($_SESSION['email_cliente'], $dados_compra);

        /** Prepara os dados da compra para gravar na base de dados */
        $data = new DateTime();
        $dados_compra['id_cliente'] = $_SESSION['id_cliente'];
        $dados_compra['data_compra'] = $data->format('Y-m-d H:i:s');
        foreach ($results as $key => $value) {
            $dados_compra['dados_produto'][$key]['id'] = $value->id;
            $dados_compra['dados_produto'][$key]['quantidade'] = $_SESSION['carrinho'][$value->id];
            $dados_compra['dados_produto'][$key]['valor_unitario'] = $value->preco;
        }

        /** Grava os dados da compra na base de dados*/
        $r = $this->purchasing->saveDatasPurchasing($dados_compra);

        $idPur = $this->purchasing->getPurchasingByCode($dados_compra['dados_pagamento']['codigo_compra']);
        $res = $this->purchasingStatus->saveDatasPurchasingStatus($data->format('Y-m-d H:i:s'), $idPur[0]->id, 1);
        $statusPur = $this->purchasingStatus->getPurchasingStatusWithStatus($idPur[0]->id, 1);

        /** Envia o e-mail informando o Status do Pedido */
        $this->email->sendEmailStatusPurchasing($_SESSION['email_cliente'], $dados_compra, $statusPur);

        /** Grava os dados sobre o Pagamento Pendente, aguardando ou não a aprovação */
        $this->paymentStatus->saveDataPaymentStatus(2, $idPur[0]->id, $data->format('Y-m-d H:i:s'));

        /** Informa ao Sistema se o pagamento foi Aprovado ou Negado */
        $num = rand(3, 7);
        if ($num == 3 || $num == 4 || $num == 5) {
            $idStatus = 3;
        } else {
            $idStatus = 7;
        }
        $this->paymentStatus->saveDataPaymentStatus($idStatus, $idPur[0]->id, $data->format('Y-m-d H:i:s'));

        /** Busca a se o pagamento foi Aprovado ou Negado */
        $statusPay = $this->paymentStatus->getPaymentStatusWithStatus($idPur[0]->id);

        /** Envia o e-mail informando se o Pagamento foi Aprovado ou não */
        $this->email->sendEmailStatusPayment($_SESSION['email_cliente'], $dados_compra, $statusPay);

        /** Se o pagamento tiver sido Negado, cancelar a compra */
        if ($statusPay[0]->status_id == 7) {
            /** cancelar a compra */
            $this->purchasing->updatePurchaseStatus($idPur[0]->id, 'canceled');
            $this->purchasingStatus->saveDatasPurchasingStatus($data->format('Y-m-d H:i:s'), $idPur[0]->id, 7);
            $this->esvaziarCarrinho();
        } else {
            /** Atualiza o status da compra para Confirmada */
            $this->purchasing->updatePurchaseStatus($idPur[0]->id, 'confirmed');
            $this->esvaziarCarrinho();

            /** Atualiza na tabela dos produtos para a quantidade atual após a venda confirmada */
            $idsProd = [];
            foreach ($dados_compra['dados_produto'] as $value) {
                $idsProd[] = $value['id'];
            }
            $idsProdIn = implode(",", $idsProd);
            $resProds = $this->produto->buscar_produtos_por_id($idsProdIn);
            foreach ($resProds as $key => $value) {
                foreach ($dados_compra['dados_produto'] as $k => $v) {
                    if ($value->id == $v['id']) {
                        $idsProdP[$k]['id'] = $v['id'];
                        $idsProdP[$k]['quantidade'] = $value->qtd_estoque - $v['quantidade'];
                    }
                }
            }
            $this->produto->updateProductsAfterPurchase($idsProdP);

            /** Preparando pedido */
            $this->purchasingStatus->saveDatasPurchasingStatus($data->format('Y-m-d H:i:s'), $idPur[0]->id, 4);

            /** Envia o e-mail informando que o pedido está sendo preparado para o envio */
            $statusPurSta4 = $this->purchasingStatus->getPurchasingStatusWithStatus($idPur[0]->id, 4);
            $this->email->sendEmailStatusPurchasing($_SESSION['email_cliente'], $dados_compra, $statusPurSta4);

            /** Enviar pedido */
            $this->purchasingStatus->saveDatasPurchasingStatus($data->format('Y-m-d H:i:s'), $idPur[0]->id, 5);

            /** Envia o e-mail informando que o pedido foi enviado */
            $statusPurSta5 = $this->purchasingStatus->getPurchasingStatusWithStatus($idPur[0]->id, 5);
            $this->email->sendEmailStatusPurchasing($_SESSION['email_cliente'], $dados_compra, $statusPurSta5);

            /** Pedido entregue */
        }

        $dados = ['codigo_compra' => $dados_compra['dados_pagamento']['codigo_compra'],
        'total_compra' => $dados_compra['dados_pagamento']['total']];

        Functions::Layout([
            'layouts/html_header',
            'layouts/header',
            'compra_confirmada',
            'layouts/footer',
            'layouts/html_footer',
        ], $dados);
    }

    public function outro_endereco()
    {
        $dados_post = json_decode(file_get_contents('php://input'), true);

        $_SESSION['end_alt'] = [
            'endereco_alternativo' => $dados_post['endereco_alternativo'],
            'cidade' => $dados_post['cidade']
        ];
    }
}