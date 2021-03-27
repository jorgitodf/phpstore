<?php

declare(strict_types=1);

namespace core\models;

use core\classes\Database;

class AddresUser
{
    private $bd;
    private $table = 'addres_user';

    public function __construct()
    {
        $this->bd = new Database();
    }

    public function createAddressUser(array $data)
    {
        $dados['tipo_endereco'] = trim(filter_var($data['tipo_endereco'], FILTER_SANITIZE_NUMBER_INT))
            == 1 ? "Residencial" : "Trabalho";
        $dados['address_id'] = trim(filter_var($data['address_id'], FILTER_SANITIZE_NUMBER_INT));
        $dados['users_id'] = trim(filter_var($data['users_id'], FILTER_SANITIZE_NUMBER_INT));

        return $this->bd->create($dados, $this->table);
    }
}
