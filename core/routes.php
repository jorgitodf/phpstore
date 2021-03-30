<?php

$routes = [
    'home' => 'home@index',
    'loja' => 'loja@loja',
    'carrinho' => 'carrinho@carrinho',
    'adicionar_carrinho' => 'carrinho@adicionar_carrinho',
    'limpar_carrinho' => 'carrinho@limpar_carrinho',
    'remover_produto_carrinho' => 'carrinho@remover_produto_carrinho',
    'finalizar_compra_resumo' => 'carrinho@finalizar_compra_resumo',
    'finalizar_compra' => 'carrinho@finalizar_compra',
    'confirmar_compra' => 'carrinho@confirmar_compra',
    'outro_endereco' => 'carrinho@outro_endereco',
    'novo_cliente' => 'user@novo_cliente',
    'criar_conta' => 'user@criar_conta',
    'confirmar_email' => 'user@confirmar_email',
    'login' => 'user@login',
    'logar' => 'user@logar',
    'logout' => 'user@logout',
    'produtos' => 'produto@buscar_produtos_disponiveis',
    'endereco' => 'endereco@endereco',
    'criar-endereco' => 'endereco@criarEndereco',
    'minha-conta' => 'user@index',
    'historico-compras' => 'compra@index',
    'dados-pessoais' => 'user@dadosPessoais'
];

$action = 'home';

if (isset($_GET['r'])) {
    if (!key_exists($_GET['r'], $routes)) {
        $action = 'home';
    } else {
        $action = $_GET['r'];
    }
}

$partes = explode('@', $routes[$action]);

$controller = ucfirst($partes[0]);
$controller = 'core\\controllers\\' . $controller;
$method = $partes[1];

$ctr = new $controller();
$ctr->$method();
