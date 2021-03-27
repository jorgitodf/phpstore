<?php

namespace core\controllers;

use core\classes\Functions;
use core\classes\Validations;
use core\models\Address;
use core\models\AddresUser;
use core\models\PublicPlace;
use DateTime;

class Endereco
{
    private $address;
    private $public_place;
    private $addres_user;
    private $validations;

    public function __construct()
    {
        $this->address = new Address();
        $this->public_place = new PublicPlace();
        $this->public_place = new PublicPlace();
        $this->addres_user = new AddresUser();
        $this->validations = new Validations();
    }

    public function endereco()
    {
        $dados = [];

        $logradouros = $this->public_place->getAllPublicPlace();
        $dados['logradouros'] = $logradouros;

        Functions::Layout([
            'layouts/html_header',
            'layouts/header',
            'endereco',
            'layouts/footer',
            'layouts/html_footer',
        ], $dados);
    }

    public function criarEndereco()
    {
        $dados = json_decode(file_get_contents('php://input'), true);

        $validations = $this->validations->validateAddres($dados);

        if ($validations == null) {
            $res = $this->address->createAddress($dados, $_SESSION['id_cliente']);

            if ($res == true) {
                echo json_encode(['success' => 'Endereço Cadastrado com Sucesso!'], http_response_code(201));
                return;
            }
        }

        echo json_encode(['error' => $validations['msg-error']], http_response_code($validations['status-code']));
    }
}
