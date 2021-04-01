<?php

namespace core\controllers;

use core\classes\Functions;
use core\classes\SendEmail;
use core\classes\Validations;
use core\models\Users;
use core\models\Address;
use core\models\PublicPlace;

class User
{
    private $user;
    private $address;
    private $public_place;
    private $validations;

    public function __construct()
    {
        $this->user = new Users();
        $this->email = new SendEmail();
        $this->address = new Address();
        $this->public_place = new PublicPlace();
        $this->validations = new Validations();
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
        } elseif (empty($email)) {
            $_SESSION['erro'] = 'Informe o seu E-mail!';
            $this->novo_cliente($name, $email, null);
            return;
        } elseif ($password !== $password) {
            $_SESSION['erro'] = 'As senhas não são iguais!';
            $this->novo_cliente($name, $email, null);
            return;
        } elseif (empty($_POST['password']) || empty($_POST['password_repeat'])) {
            $_SESSION['erro'] = 'Informe a sua Senha!';
            $this->novo_cliente($name, $email, null);
            return;
        } elseif (count($this->user->checkEmail($email)) != 0) {
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
        } elseif ($this->user->verifyContaActived($email)) {
            $_SESSION['erro'] = 'A sua conta ainda não foi ativada!';
            $this->login($email, $password);
            return;
        } elseif (!filter_var($email, FILTER_SANITIZE_EMAIL)) {
            $_SESSION['erro'] = 'O E-mail informado não é válido!';
            $this->login($email, $password);
            return;
        } elseif (count($this->user->checkEmail($email)) == 0) {
            $_SESSION['erro'] = 'O E-mail informado não está cadastrado!';
            $this->login($email, null);
            return;
        } elseif (empty($password)) {
            $_SESSION['erro'] = 'Informe a sua Senha!';
            $this->login($email, $password);
            return;
        } else {
            $res = $this->user->checkPasswordAndEmail($email, $password);
            $addres = $this->address->getAddressUserById($res[0]->id);

            if (is_bool($res)) {
                $_SESSION['erro'] = 'Senha Inválida!';
                $this->login($email, $password);
                return;
            } else {
                $_SESSION['id_cliente'] = $res[0]->id;
                $_SESSION['nome_cliente'] = $res[0]->name;
                $_SESSION['email_cliente'] = $res[0]->email;
                $_SESSION['csrf_token'] = mb_convert_case(Functions::createHash(60), MB_CASE_TITLE, 'UTF-8');

                if (isset($_SESSION['tmp_carrinho'])) {
                    unset($_SESSION['tmp_carrinho']);
                    Functions::redirect('finalizar_compra_resumo');
                } elseif (!$addres) {
                    Functions::redirect('endereco');
                } else {
                    Functions::redirect();
                }
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

    public function index()
    {
        if (!Functions::clienteLogado()) {
            Functions::redirect();
            return;
        }

        Functions::Layout([
            'layouts/html_header',
            'layouts/header',
            'perfil_navegacao',
            'layouts/footer',
            'layouts/html_footer',
        ]);
    }

    public function dadosPessoais()
    {
        if (!Functions::clienteLogado()) {
            Functions::redirect();
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] != 'GET') {
            Functions::redirect();
            return;
        }

        $dados['user'] = $this->user->getAddressUserById($_SESSION['id_cliente']);
        $dados['logradouros'] = $this->public_place->getAllPublicPlace();

        Functions::Layout([
            'layouts/html_header',
            'layouts/header',
            'dados_pessoais',
            'layouts/footer',
            'layouts/html_footer',
        ], compact('dados'));
    }

    public function updateUser()
    {
        $token = apache_request_headers()['Authorization'];
        if ($_SERVER['REQUEST_METHOD'] != 'POST' || $_SESSION['csrf_token'] != $token) {
            return response_json(['error' => "Não Encontrado"], 404);
        }

        $dados = json_decode(file_get_contents('php://input'), true);

        $validations = $this->validations->validateUpdateUser($dados);

        if ($validations == null) {
            $res = $this->address->updateAddress($dados, $_SESSION['id_cliente']);
            if ($res == true) {
                echo json_encode(['success' => 'Endereço Atualizado com Sucesso!'], http_response_code(201));
                return;
            }
        }

        return response_json(['error' => $validations['msg-error']], $validations['status-code']);
    }
}