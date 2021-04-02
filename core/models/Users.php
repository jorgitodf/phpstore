<?php

namespace core\models;

use core\classes\Database;
use core\classes\Functions;
use core\models\AddresUser;

class Users
{
    private $bd;
    private $au;

    public function __construct()
    {
        $this->bd = new Database();
        $this->au = new AddresUser();
    }


    public function checkEmail(string $email)
    {
        $parametros = [
            ':email' => $email
        ];

        return $this->bd->select("SELECT email FROM users WHERE email = :email", $parametros);
    }

    public function createUser($name, $email, $password, $ip, $token)
    {

        $parametros = [
            ':name' => $name,
            ':email' => $email,
            ':password' => $password,
            ':token' => $token,
            ':ativo' => 0,
            ':created_at' => date('Y-m-d H:i:s'),
            ':updated_at' => date('Y-m-d H:i:s'),
            ':last_login_ip' => $ip
        ];

        return $this->bd->insert("INSERT INTO users VALUES (
            0, :name, :email, :password, :token, :ativo, :created_at, :updated_at, :last_login_ip)", $parametros);
    }

    public function validarEmail(string $token)
    {
        $parametros = [
            ':token' => $token
        ];

        $res = $this->bd->select("SELECT id, name, email FROM users WHERE token = :token", $parametros);

        if (count($res) != 1) {
            return false;
        }

        $parametros = [
            ':id' => $res[0]->id
        ];

        $this->bd->update("UPDATE users SET ativo = 1, token = NULL, updated_at = NOW() WHERE id = :id", $parametros);

        return true;
    }

    public function checkPasswordAndEmail(string $email, string $password)
    {
        $parametros = [
            ':email' => $email
        ];

        $res = $this->bd->select("SELECT id, name, email, password FROM users WHERE email = :email AND ativo = 1", $parametros);

        if (count($res) > 0) {
            if (!password_verify($password, $res[0]->password)) {
                return false;
            }
            return $res;
        } else {
            return false;
        }
    }

    public function buscar_dados_cliente(int $id_cliente)
    {
        $parametros = [
            ':id' => $id_cliente
        ];

        $res = $this->bd->select("SELECT id, name, email FROM users WHERE id = :id AND ativo = 1", $parametros);

        if (count($res) > 0) {
            return $res[0];
        } else {
            return false;
        }
    }

    public function verifyContaActived(string $email)
    {
        $parametros = [
            ':email' => $email
        ];

        $res = $this->bd->select("SELECT token FROM users WHERE email = :email", $parametros);

        if (count($res) > 0 && $res[0]->token != null) {
            return true;
        } else {
            return false;
        }
    }

    public function getAddressUserById(int $id_cliente)
    {
        $parametros = [
            ':id_cliente' => $id_cliente
        ];

        $res = $this->bd->select("SELECT u.name, u.email, pp.id AS id_logradouro, pp.logradouro, a.complemento, a.numero, 
                    a.bairro, a.cep, a.uf, au.tipo_endereco
            FROM addres_user au
            JOIN users u ON (u.id = au.users_id)
            JOIN address a ON (a.id = au.address_id)
            JOIN public_place pp ON (pp.id = a.public_place_id)
            WHERE u.id = :id_cliente", $parametros);

        if (count($res) > 0) {
            return $res[0];
        } else {
            return false;
        }
    }

    public function verifyEmail(string $email)
    {
        $parametros = [
            ':email' => strtolower($email) 
        ];

        $res = $this->bd->select("SELECT email FROM users WHERE email = :email", $parametros);

        if (count($res) > 0) {
            return true;
        } else {
            return false;
        }
    }
}