<?php

namespace core\controllers;

use core\classes\Functions;
use core\models\Products;

class Carrinho
{
    private $produto;

    public function __construct()
    {
        $this->produto = new Products();
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
}