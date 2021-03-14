<?php

namespace core\controllers;

use core\classes\Functions;
use core\models\Products;
use core\models\Users;

class Carrinho
{
    private $produto;
    private $cliente;

    public function __construct()
    {
        $this->produto = new Products();
        $this->cliente = new Users();
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
                
                foreach($res as $produto) {

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

            foreach($dados_tmp as $item) {
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

        foreach($carrinho as $produto) {
            $total_produtos += $produto;
        }

        echo $total_produtos;
    }

    public function limpar_carrinho()
    {
        unset($_SESSION['carrinho']);
        $this->carrinho();
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
            foreach($res as $produto) {
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
        foreach($dados_tmp as $item) {
            $total += $item['preco'];
        }
        array_push($dados_tmp, $total);

        $dados = [];
        $dados = [
            'carrinho' => $dados_tmp
        ];

        $dados_cliente = $this->cliente->buscar_dados_cliente($_SESSION['id_cliente']);
        $dados['cliente'] = $dados_cliente;

        Functions::Layout([
            'layouts/html_header',
            'layouts/header',
            'compra_resumo',
            'layouts/footer',
            'layouts/html_footer',
        ], $dados);
    }

    public function escolher_metodo_pagamento()
    {
        Functions::printDados($_SESSION);

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
            foreach($res as $produto) {
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
        foreach($dados_tmp as $item) {
            $total += $item['preco'];
        }
        array_push($dados_tmp, $total);

        $dados = [];
        $dados = [
            'carrinho' => $dados_tmp
        ];

        $dados_cliente = $this->cliente->buscar_dados_cliente($_SESSION['id_cliente']);
        $dados['cliente'] = $dados_cliente;

        Functions::Layout([
            'layouts/html_header',
            'layouts/header',
            'compra_resumo',
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