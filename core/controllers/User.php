<?php

namespace core\controllers;

use core\classes\Functions;
use core\classes\SendEmail;
use core\models\Users;
use Exception;

class User
{
    private $user;

    public function __construct()
    {
        $this->user = new Users();
        $this->email = new SendEmail();
    }

    //===========================================================================================//
    public function novo_cliente($name = null, $email = null, $password = null)
    {
        if (Functions::clienteLogado()) {
            Functions::redirect();
            return;
        }

        Functions::Layout([
            'layouts/html_header',
            'layouts/header',
            'registro_usuario',
            'layouts/footer',
            'layouts/html_footer',
        ], ['name' => $name, 'email' => $email, 'password' => $password]);
    }

    public function criar_conta()
    {
        $name = mb_convert_case(trim(filter_var($_POST['name'], FILTER_SANITIZE_STRING)), MB_CASE_TITLE, 'UTF-8');
        $email = strtolower(trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)));
        $password = password_hash(trim(filter_var($_POST['password'], FILTER_SANITIZE_STRING)), PASSWORD_BCRYPT);
        $ip = trim(filter_var($_POST['ip'], FILTER_SANITIZE_STRING));

        if (Functions::clienteLogado()) {
            header('Location: /');
            return;
        } 

        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            header('Location: /');
            return;
        }

        
        if (empty($name)) {
            $_SESSION['erro'] = 'Informe o seu Nome Completo!';
            $this->novo_cliente($name, $email, null);
            return;
        } else if (empty($email)) {
            $_SESSION['erro'] = 'Informe o seu E-mail!';
            $this->novo_cliente($name, $email, null);
            return;
        } else if ($password !== $password) {
            $_SESSION['erro'] = 'As senhas não são iguais!';
            $this->novo_cliente($name, $email, null);
            return;
        } else if (empty($_POST['password']) || empty($_POST['password_repeat'])) {
            $_SESSION['erro'] = 'Informe a sua Senha!';
            $this->novo_cliente($name, $email, null);
            return;
        } else if (count($this->user->checkEmail($email)) != 0) {
            $_SESSION['erro'] = 'O E-mail informado já está cadastrado!';
            $this->novo_cliente($name, $email, null);
            return;
        } else {

            $token = mb_convert_case(Functions::createHash(60), MB_CASE_TITLE, 'UTF-8');
   
            $res = $this->user->createUser($name, $email, $password, $ip, $token);

            $response = $this->email->send_email_novo_cliente($email, $name, $token);
 

            if ($res !== false) {

                Functions::Layout([
                    'layouts/html_header',
                    'layouts/header',
                    'registro_usuario_sucesso',
                    'layouts/footer',
                    'layouts/html_footer',
                ]);
                return;

            }

        }
    }

    public function confirmar_email()
    {
        $token = $_GET['token'];

        if (Functions::clienteLogado()) {
            Functions::redirect();
            return;
        }

        if (!isset($token)) {
            Functions::redirect();
            return;
        }

        if (strlen($token) != 60) {
            Functions::redirect();
            return;
        }

        $res = $this->user->validarEmail($token);

        if ($res) {

            Functions::Layout([
                'layouts/html_header',
                'layouts/header',
                'conta_validada_sucesso',
                'layouts/footer',
                'layouts/html_footer',
            ]);
            return;

        } else {
            Functions::redirect();
        }
    }

    public function login($email = null, $password = null)
    {
        if (Functions::clienteLogado()) {
            Functions::redirect();
            return;
        }

        Functions::Layout([
            'layouts/html_header',
            'layouts/header',
            'login',
            'layouts/footer',
            'layouts/html_footer',
        ], ['email' => $email, 'password' => $password]);
    }

    public function logar()
    {
        $email = trim(strtolower($_POST['email']));
        $password = trim(filter_var($_POST['password'], FILTER_SANITIZE_STRING));
        $ip = trim(filter_var($_POST['ip'], FILTER_SANITIZE_STRING));

        if (Functions::clienteLogado()) {
            Functions::redirect();
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            Functions::redirect();
            return;
        }

        if (empty($email)) {
            $_SESSION['erro'] = 'Informe o seu E-mail!';
            $this->login($email, null);
            return;
        } else if (!filter_var($email, FILTER_SANITIZE_EMAIL)) {
            $_SESSION['erro'] = 'O E-mail informado não é válido!';
            $this->login($email, $password);
            return;
        } else if (count($this->user->checkEmail($email)) == 0) {
            $_SESSION['erro'] = 'O E-mail informado não está cadastrado!';
            $this->login($email, null);
            return;  
        } else if (empty($password)) {
            $_SESSION['erro'] = 'Informe a sua Senha!';
            $this->login($email, $password);
            return;
        } else {

            $res = $this->user->checkPasswordAndEmail($email, $password);

            if (is_bool($res)) {
                $_SESSION['erro'] = 'Senha Inválida!';
                $this->login($email, $password);
                return;
            } else {
                $_SESSION['id_cliente'] = $res[0]->id;
                $_SESSION['nome_cliente'] = $res[0]->name;
                $_SESSION['email_cliente'] = $res[0]->email;
                $_SESSION['token_cliente'] = mb_convert_case(Functions::createHash(60), MB_CASE_TITLE, 'UTF-8');
                Functions::redirect();
                return;
            }

        }

    }

    public function logout()
    {
        unset($_SESSION['id_cliente']);
        unset($_SESSION['nome_cliente']);
        unset($_SESSION['email_cliente']);
        unset($_SESSION['token_cliente']);
        Functions::redirect();
        return;
    }
}