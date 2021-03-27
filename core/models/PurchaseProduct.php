<?php

declare(strict_types=1);

namespace core\models;

use core\classes\Database;
use PDOException;

class PurchaseProduct
{
    private $bd;
    private $table = 'purchase_product';

    public function __construct()
    {
        $this->bd = new Database();
    }

    public function saveDatasPurchasingProduct(array $data, int $idPurchasing)
    {
        try {
            foreach ($data as $value) {
                $this->bd->insert(
                    "INSERT INTO {$this->table} VALUES (
                    :preco_unidade, :quantidade, :created_at, :products_id, :purchasing_id)",
                    [
                        ':preco_unidade' => $value['valor_unitario'],
                        ':quantidade' => $value['quantidade'],
                        ':created_at' => date('Y-m-d H:i:s'),
                        ':products_id' => $value['id'],
                        ':purchasing_id' => $idPurchasing
                    ]
                );
            }
            return true;
        } catch (PDOException $e) {
            echo "Exception PurchasingProduct: " . $e->getMessage();
            return false;
        }
    }
}
