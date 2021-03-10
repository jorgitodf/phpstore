<?php

namespace core\classes;

use Exception;
use PDO;
use PDOException;

class Database
{
    private $connect;
    
    private function conectar()
    {
        $this->connect = new PDO(
            'mysql:'.
            'host='.MYSQL_SERVER.';'.
            'dbname='.MYSQL_DATABASE.';'.
            'charset='.MYSQL_CHARSET,MYSQL_USER,MYSQL_PASS,
            array(PDO::ATTR_PERSISTENT => true)
        );   
        
        $this->connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    }

    private function desconectar()
    {
        $this->connect = null;   
    }


    public function select($sql, $parametros = null)
    {
        $sql = trim($sql);

        if (!preg_match("/^SELECT/i", $sql)) {
            throw new Exception('Base de dados - Não é uma instrução SELECT');
        }

        $this->conectar();

        $resultados = null;

        try {

            if (!empty($parametros)) {
                $exec = $this->connect->prepare($sql);
                $exec->execute($parametros);
                $resultados = $exec->fetchAll(PDO::FETCH_CLASS);
            } else {
                $exec = $this->connect->prepare($sql);
                $exec->execute();
                $resultados = $exec->fetchAll(PDO::FETCH_CLASS);
            }
            
        } catch (PDOException $e) {
            return false;
        }

        $this->desconectar();

        return $resultados;
    }

    public function insert($sql, $parametros = null)
    {
        $sql = trim($sql);

        if (!preg_match("/^INSERT/i", $sql)) {
            throw new Exception('Base de dados - Não é uma instrução INSERT');
        }

        $this->conectar();

        try {

            if (!empty($parametros)) {
                $exec = $this->connect->prepare($sql);
                $exec->execute($parametros);
            } else {
                $exec = $this->connect->prepare($sql);
                $exec->execute();
            }
            
        } catch (PDOException $e) {
            return false;
        }

        $this->desconectar();
    }

    public function update($sql, $parametros = null)
    {
        $sql = trim($sql);

        if (!preg_match("/^UPDATE/i", $sql)) {
            throw new Exception('Base de dados - Não é uma instrução UPDATE');
        }

        $this->conectar();

        try {

            if (!empty($parametros)) {
                $exec = $this->connect->prepare($sql);
                $exec->execute($parametros);
            } else {
                $exec = $this->connect->prepare($sql);
                $exec->execute();
            }
            
        } catch (PDOException $e) {
            return false;
        }

        $this->desconectar();
    }

    public function delete($sql, $parametros = null)
    {
        $sql = trim($sql);
        
        if (!preg_match("/^DELETE/i", $sql)) {
            throw new Exception('Base de dados - Não é uma instrução DELETE');
        }

        $this->conectar();

        try {

            if (!empty($parametros)) {
                $exec = $this->connect->prepare($sql);
                $exec->execute($parametros);
            } else {
                $exec = $this->connect->prepare($sql);
                $exec->execute();
            }
            
        } catch (PDOException $e) {
            return false;
        }

        $this->desconectar();
    }

}

