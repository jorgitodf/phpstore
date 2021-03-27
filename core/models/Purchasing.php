<?php

declare(strict_types=1);

namespace core\models;

use core\classes\Database;
use Exception;
use PDOException;

class Purchasing
{
    private $bd;
    private $table = 'purchasing';
    private $pp;

    public function __construct()
    {
        $this->bd = new Database();
        $this->pp = new PurchaseProduct();
    }


    public function saveDatasPurchasing(array $dados_compra)
    {
        $parametrosPurchasing = [
            ':data_compra' => $dados_compra['data_compra'],
            ':codigo_compra' => $dados_compra['dados_pagamento']['codigo_compra'],
            ':created_at' => date('Y-m-d H:i:s'),
            ':updated_at' => date('Y-m-d H:i:s'),
            ':status' => null,
            ':users_id' => $dados_compra['id_cliente']
        ];

        $con = $this->bd->getConnection();

        $con->beginTransaction();

        try {
            $idPurchasing = $this->bd->insert("INSERT INTO {$this->table} VALUES (
                0, :data_compra, :codigo_compra, :created_at, :updated_at, :status, :users_id)", $parametrosPurchasing);

            $res = (int)$this->pp->saveDatasPurchasingProduct($dados_compra['dados_produto'], (int)$idPurchasing);

            if (!is_string($idPurchasing) && !is_string($res)) {
                $con->commit();
                return true;
            } else {
                $con->rollBack();
                throw new Exception($idPurchasing . $res);
                return true;
            }
        } catch (Exception $e) {
            echo "Exception saveDatasPurchasing: " . $e->getMessage();
            return false;
        }
    }

    public function getPurchasingByCode(string $codePurchasing)
    {
        $res = $this->bd->select("SELECT id FROM {$this->table} 
            WHERE codigo_compra = :codigo_compra", [':codigo_compra' => $codePurchasing]);

        if (count($res) > 0) {
            return $res;
        } else {
            return false;
        }
    }

    public function updatePurchaseStatus(int $idPurchasing, string $status)
    {
        $this->bd->update(
            "UPDATE {$this->table} SET status = :status WHERE id = :id",
            [':status' => $status, ':id' => $idPurchasing]
        );

        return true;
    }
}
