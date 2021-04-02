<?php

declare(strict_types=1);

namespace core\models;

use core\classes\Database;
use core\classes\Functions;
use core\models\AddresUser;
use Exception;

class Address
{
    private $bd;
    private $addresUser;
    private $table = 'address';

    public function __construct()
    {
        $this->bd = new Database();
        $this->addresUser = new AddresUser();
    }

    public function getAddressUserById(int $idUser)
    {
        $res = $this->bd->select("SELECT 
            pp.logradouro, a.complemento, a.numero, a.bairro, a.uf, au.tipo_endereco
        FROM addres_user au
        JOIN address a ON (a.id = au.address_id)
        JOIN users u ON (u.id = au.users_id)
        JOIN public_place pp ON (pp.id = a.public_place_id)
        WHERE u.id = :id_user", [':id_user' => $idUser]);

        if (count($res) > 0) {
            return $res;
        } else {
            return false;
        }
    }

    public function createAddress(array $data, int $userId)
    {
        $dados['public_place_id'] = trim(filter_var($data[0]['public_place_id'], FILTER_SANITIZE_NUMBER_INT));
        $dados['complemento'] = ucfirst(trim(filter_var($data[0]['complemento'], FILTER_SANITIZE_STRING)));
        $dados['numero'] = trim(filter_var($data[0]['numero'], FILTER_SANITIZE_NUMBER_INT));
        $dados['bairro'] = ucfirst(trim(filter_var($data[0]['bairro'], FILTER_SANITIZE_STRING)));
        $dados['cep'] = trim(filter_var($this->formataCep($data[0]['cep']), FILTER_SANITIZE_NUMBER_INT));
        $dados['uf'] = trim(filter_var($data[0]['uf'], FILTER_SANITIZE_STRING));

        $con = $this->bd->getConnection();

        $con->beginTransaction();

        try {
            $idAddress = $this->bd->create($dados, $this->table);

            $idAddressUser = $this->addresUser->createAddressUser(['tipo_endereco' => $data[1]['tipo_endereco'],
                'address_id' => $idAddress, 'users_id' => $userId]);

            if (!is_string($idAddress) && !is_string($idAddressUser)) {
                $con->commit();
                return true;
            } else {
                $con->rollBack();
                throw new Exception($idAddress . $idAddressUser);
                return false;
            }
        } catch (Exception $e) {
            echo "Exception createAddress: " . $e->getMessage();
            return false;
        }
    }

    public function updateAddress(array $data, $idUser)
    {
        $con = $this->bd->getConnection();

        $con->beginTransaction();

        try {
            /** 1 - atualizar a address_user pelo users_id e pegar o address_id -> updateAddressUser(array $data, int $idUser) OK*/
            $resAU = $this->addresUser->updateAddressUser($data, (int)$idUser);

            /** 2 - atualizar a address pelo address_id */
            $parametros = [
                ':complemento' => ucfirst(trim(filter_var($data[0]['complemento'], FILTER_SANITIZE_STRING))),
                ':numero' => trim(filter_var($data[0]['numero'], FILTER_SANITIZE_NUMBER_INT)),
                ':bairro' => ucfirst(trim(filter_var($data[0]['bairro'], FILTER_SANITIZE_STRING))),
                ':cep' => trim(filter_var($this->formataCep($data[0]['cep']), FILTER_SANITIZE_NUMBER_INT)),
                ':uf' => trim(filter_var($data[0]['uf'], FILTER_SANITIZE_STRING)),
                ':updated_at' => date('Y-m-d H:i:s'),
                ':public_place_id' => trim(filter_var($data[0]['public_place_id'], FILTER_SANITIZE_NUMBER_INT)),
                ':id' => $resAU
            ];
            $res = $this->bd->update("UPDATE {$this->table} SET complemento = :complemento, numero = :numero,
                bairro = :bairro, cep = :cep, uf = :uf, updated_at = :updated_at, public_place_id = :public_place_id
                WHERE id = :id", $parametros);


            /** 3 - atualizar a users pelo users_id */
            $parametrosUser = [
                ':name' => ucfirst(trim(filter_var($data[0]['nome'], FILTER_SANITIZE_STRING))),
                ':email' => strtolower(trim(filter_var($data[0]['email'], FILTER_SANITIZE_EMAIL))),
                ':updated_at' => date('Y-m-d H:i:s'),
                ':id' => $idUser
            ];
            $resUser = $this->bd->update("UPDATE users SET name = :name, email = :email,
                updated_at = :updated_at
                WHERE id = :id", $parametrosUser);

            $_SESSION['nome_cliente'] = $parametrosUser[':name'];
            $_SESSION['email_cliente'] = $parametrosUser[':email'];

            if ((is_numeric($resAU) && $resAU > 0) && (!is_string($res) && $res > 0) && (!is_string($resUser) && $resUser > 0)) {
                $con->commit();
                
                return true;
            } else {
                $con->rollBack();
                Functions::printDados("Rollback");
                throw new Exception($res . $resUser);
                return false;
            }
        } catch (Exception $e) {
            echo "Exception updateAddress: " . $e->getMessage();
            return false;
        }
    }



    private function formataCep($cep)
    {
        return preg_replace("/[^0-9]/", "", $cep);
    }
}