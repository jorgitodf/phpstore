<?php

namespace core\classes;

use Exception;

class Functions
{
    public static function Layout($estruturas, $dados = null)
    {
        if (!is_array($estruturas)) {
            throw new Exception("Coleção de Estruturas inválida");
        }

        if (!empty($dados) && is_array($dados)) {
            extract($dados);
        }

        foreach ($estruturas as $estrutura) {
            include("../core/views/$estrutura.php");
        }
    }

    public static function clienteLogado()
    {
        return isset($_SESSION['id_cliente']);
    }

    public static function createHash($num_caracteres = null)
    {
        $chars = '041646384956321laksmdkmasldmfasmdfmaslçdkmfalksdmflkasdLAKSMDKMASLDMFASMDFMASLÇDKMFALKSDMFLKASD';
        return substr(str_shuffle($chars), 0, $num_caracteres);
    }


    public static function redirect($rota = '')
    {
        if (!empty($rota)) {
            if (BASE_URL == 'http://localhost/') {
                header("Location: http://localhost/phpstore/public/?r=$rota");
            } else {
                header("Location: " . BASE_URL);
                header("Location: " . BASE_URL . "?r=$rota");
            }
        } else {
            if (BASE_URL == 'http://localhost/') {
                header("Location: http://localhost/phpstore/public/");
            } else {
                header("Location: " . BASE_URL);
                header("Location: " . BASE_URL);
            }
        }
    }

    public static function printDados($dados)
    {
        echo "<pre>";
        print_r($dados);
        die();
    }

    public static function gerarCódigoCompra()
    {
        $codigo = "";
        $chars = "ABCDEFGHIJKLMNOPQRSTUVXYZABCDEFGHIJKLMNOPQRSTUVXYZABCDEFGHIJKLMNOPQRSTUVXYZ";
        $codigo .= substr(str_shuffle($chars), 0, 3);
        $codigo .= rand(100000, 999999);
        return $codigo;
    }
}
