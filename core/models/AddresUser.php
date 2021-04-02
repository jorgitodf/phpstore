<?php

declare(strict_types=1);

namespace core\models;

use core\classes\Database;
use core\classes\Functions;

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

    public function updateAddressUser(array $data, int $idUser)
    {
        $r = $this->getAddressUser($idUser, (int)$data[1]['tipo_endereco']);

        if ($data[1]['tipo_endereco'] == 1) {
            $tipo_endereco = "Residencial";
        } else {
            $tipo_endereco = "Trabalho";
        }

        $parametros = [
            ':tipo_endereco' => $tipo_endereco,
            ':address_id' => $r[0]->address_id,
            ':users_id' => $idUser,
            ':id' => $r[0]->id
        ];

        $res = $this->bd->update("UPDATE {$this->table} SET tipo_endereco = :tipo_endereco, address_id = :address_id,
            users_id = :users_id WHERE id = :id", $parametros);

        if ($res == true && $res > 0) {
            return $r[0]->address_id;
        } else {
            return false;
        }
    }

    public function getAddressUser(int $idUser, int $idTpEnd)
    {
        $res = $this->bd->select(
            "SELECT id, address_id FROM {$this->table} WHERE users_id = :users_id",
            [':users_id' => $idUser]
        );

        if (count($res) > 0) {
            return $res;
        } else {
            return false;
        }
    }
}