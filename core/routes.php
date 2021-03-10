<?php

$routes = [
    'home' => 'home@index',
    'loja' => 'loja@loja',
    
    'carrinho' => 'loja@carrinho',
    'novo_cliente' => 'user@novo_cliente',
    'criar_conta' => 'user@criar_conta',
    'confirmar_email' => 'user@confirmar_email',
    'login' => 'user@login',
    'logar' => 'user@logar',
    'logout' => 'user@logout',
    'produtos' => 'produto@buscar_produtos_disponiveis',
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
$controller = 'core\\controllers\\'.$controller;
$method = $partes[1];

$ctr = new $controller();
$ctr->$method();