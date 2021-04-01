<?php

declare(strict_types=1);

namespace core\classes;

use core\models\PublicPlace;
use core\models\Users;

class Validations
{
    private $erros = [];

    public function validateAddres(array $data)
    {
        $idsPp = new PublicPlace();
        $res = $idsPp->getAllPublicPlaceById();

        if (empty($data[0]['public_place_id'])) {
            $this->erros['msg-error'] = "Informe o Logradouro!";
            $this->erros['status-code'] = 403;
        } elseif (!is_numeric($data[0]['public_place_id'])) {
            $this->erros['msg-error'] = "Não encontrado!";
            $this->erros['status-code'] = 404;
        } elseif (!in_array($data[0]['public_place_id'], $res)) {
            $this->erros['msg-error'] = "Não encontrado!";
            $this->erros['status-code'] = 404;
        } elseif (empty($data[1]['tipo_endereco'])) {
            $this->erros['msg-error'] = "Informe o Tipo de Endereço!";
            $this->erros['status-code'] = 403;
        } elseif (!is_numeric($data[1]['tipo_endereco'])) {
            $this->erros['msg-error'] = "Não encontrado!";
            $this->erros['status-code'] = 404;
        } elseif (empty($data[0]['complemento'])) {
            $this->erros['msg-error'] = "Informe o Complemento do Endereço!";
            $this->erros['status-code'] = 403;
        } elseif (empty($data[0]['numero'])) {
            $this->erros['msg-error'] = "Informe o Número!";
            $this->erros['status-code'] = 403;
        } elseif (empty($data[0]['bairro'])) {
            $this->erros['msg-error'] = "Informe o Bairro!";
            $this->erros['status-code'] = 403;
        } elseif (empty($data[0]['cep'])) {
            $this->erros['msg-error'] = "Informe o CEP!";
            $this->erros['status-code'] = 403;
        } elseif (!is_numeric($this->formataCep($data[0]['cep']))) {
            $this->erros['msg-error'] = "O CEP informado é inválido!";
            $this->erros['status-code'] = 403;
        } elseif (empty($data[0]['uf'])) {
            $this->erros['msg-error'] = "Informe a UF!";
            $this->erros['status-code'] = 403;
        } else {
            return null;
        }

        return $this->erros;
    }

    public function validateUpdateUser(array $data)
    {
        $idsPp = new PublicPlace();
        $res = $idsPp->getAllPublicPlaceById();

        $u = new Users();
        $email = $u->verifyEmail($data[0]['email']);

        if (empty($data[0]['nome'])) {
            $this->erros['msg-error'] = "Preencha o seu Nome Completo!";
            $this->erros['status-code'] = 403;
        } elseif (!preg_replace('/[A-Za-z0-9\-\_]/', '', $data[0]['nome'])) {
            $this->erros['msg-error'] = "O Nome não pode conter números!";
            $this->erros['status-code'] = 403;
        } elseif (empty($data[0]['email'])) {
            $this->erros['msg-error'] = "Informe o seu E-mail!";
            $this->erros['status-code'] = 403;
        } elseif (!filter_var($data[0]['email'], FILTER_VALIDATE_EMAIL)) {
            $this->erros['msg-error'] = "O E-mail é inválido!";
            $this->erros['status-code'] = 403;
        } elseif ($email == true && $email != $data[0]['email']) {
            $this->erros['msg-error'] = "O E-mail informado já está cadastrado!";
            $this->erros['status-code'] = 403;
        } elseif (empty($data[0]['public_place_id'])) {
            $this->erros['msg-error'] = "Informe o Logradouro!";
            $this->erros['status-code'] = 403;
        } elseif (!in_array($data[0]['public_place_id'], $res)) {
            $this->erros['msg-error'] = "Não encontrado!";
            $this->erros['status-code'] = 404;
        } elseif (!is_numeric($data[0]['public_place_id'])) {
            $this->erros['msg-error'] = "Não encontrado!";
            $this->erros['status-code'] = 404;
        } elseif (empty($data[0]['complemento'])) {
            $this->erros['msg-error'] = "Informe o Complemento do Endereço!";
            $this->erros['status-code'] = 403;
        } elseif (empty($data[0]['numero'])) {
            $this->erros['msg-error'] = "Informe o Número!";
            $this->erros['status-code'] = 403;
        } elseif (empty($data[0]['bairro'])) {
            $this->erros['msg-error'] = "Informe o Bairro!";
            $this->erros['status-code'] = 403;
        } elseif (empty($data[0]['cep'])) {
            $this->erros['msg-error'] = "Informe o CEP!";
            $this->erros['status-code'] = 403;
        } elseif (!is_numeric($this->formataCep($data[0]['cep']))) {
            $this->erros['msg-error'] = "O CEP informado é inválido!";
            $this->erros['status-code'] = 403;
        } elseif (empty($data[0]['uf'])) {
            $this->erros['msg-error'] = "Informe a UF!";
            $this->erros['status-code'] = 403;
        } elseif (empty($data[1]['tipo_endereco'])) {
            $this->erros['msg-error'] = "Informe o Tipo de Endereço!";
            $this->erros['status-code'] = 403;
        } elseif (!is_numeric($data[1]['tipo_endereco'])) {
            $this->erros['msg-error'] = "Não encontrado!";
            $this->erros['status-code'] = 404;
        } else {
            return null;
        }

        return $this->erros;
    }

    private function formataCep($cep)
    {
        return preg_replace("/[^0-9]/", "", $cep);
    }
}