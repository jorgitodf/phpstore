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
            'mysql:' .
            'host=' . MYSQL_SERVER . ';' .
            'dbname=' . MYSQL_DATABASE . ';' .
            'charset=' . MYSQL_CHARSET,
            MYSQL_USER,
            MYSQL_PASS
        );

        $this->connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

        return $this->connect;
    }

    public function getConnection()
    {
        return $this->conectar();
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

    public function create($data, $table)
    {
        foreach (array_keys($data) as $key => $value) {
            $bind['values'][$key] = $value;
        }
        foreach ($data as $key => $value) {
            $bind['datas'][$key] = $value;
        }

        if (count($bind['datas']) > 3 && count($bind['values']) > 3) {
            $bind['datas']['created_at'] = date('Y-m-d H:i:s');
            $bind['datas']['updated_at'] = date('Y-m-d H:i:s');

            array_push($bind['values'], 'created_at');
            array_push($bind['values'], 'updated_at');
        }

        $fields_to_bind = [];

        foreach ($data as $field => $value) {
            $fields[] = $field;
            $fields_to_bind[] = ':' . $field;
        }

        if (count($fields_to_bind) > 3) {
            array_push($fields_to_bind, ':created_at');
            array_push($fields_to_bind, ':updated_at');
        }

        foreach ($fields_to_bind as $key => $row) {
            $parametros[$row] = "";
        }

        foreach ($bind['datas'] as $key => $value) {
            foreach ($parametros as $k => $row) {
                $parametros[$k] = $value;
            }
        }

        $query = 'INSERT INTO %s (%s) VALUES (%s)';

        $fields = implode(', ', $bind['values']);
        $fields_to_bind = implode(', ', $fields_to_bind);

        $query = sprintf($query, $table, $fields, $fields_to_bind);

        $this->conectar();

        try {
            $exec = $this->connect->prepare($query);

            foreach ($bind['datas'] as $field => $value) {
                $exec->bindValue($field, $value, PDO::PARAM_STR);
            }

            $exec->execute();

            return (int)$this->connect->lastInsertId();
        } catch (PDOException $e) {
            return $e->getMessage();
        }

        $this->desconectar();
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
                return (int)$this->connect->lastInsertId();
            } else {
                $exec = $this->prepare($sql);
                $exec->execute();
                return (int)$exec->connect->lastInsertId();
            }
        } catch (PDOException $e) {
            return $e->getMessage();
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
                return true;
            } else {
                $exec = $this->connect->prepare($sql);
                $exec->execute();
                return true;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
            return $e->getMessage();
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