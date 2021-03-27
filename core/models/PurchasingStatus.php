<?php

declare(strict_types=1);

namespace core\models;

use core\classes\Database;
use PDOException;

class PurchasingStatus
{
    private $bd;
    private $table = 'purchasing_status';

    public function __construct()
    {
        $this->bd = new Database();
    }

    public function saveDatasPurchasingStatus(string $dataStatus, int $idPurchasing, int $idStatus)
    {
        $res = $this->getPurchasingStatusByidPurchasingidStatus($idPurchasing, $idStatus);
        if (count((array)$res) < 1) {
            try {
                $this->bd->insert(
                    "INSERT INTO {$this->table} VALUES (
                    :data_status, :purchasing_id, :status_id)",
                    [
                        ':data_status' => $dataStatus,
                        ':purchasing_id' => $idPurchasing,
                        ':status_id' => $idStatus
                    ]
                );
                return true;
            } catch (PDOException $e) {
                echo "Exception PurchasingStatus: " . $e->getMessage();
                return false;
            }
        }
    }

    public function getPurchasingStatusWithStatus(int $idPurchasing, int $idStatus)
    {
        try {
            $res = $this->bd->select(
                "SELECT date_format(ps.data_status, '%d/%m/%Y às %H:%i:%s') AS data_status, 
                s.nome_status, s.mensagem_status, ps.status_id
                 FROM {$this->table} ps JOIN `status` s ON (s.id = ps.status_id)
            WHERE ps.purchasing_id = :purchasing_id AND ps.status_id = :status_id",
                [':purchasing_id' => $idPurchasing, ':status_id' => $idStatus]
            );
            return $res;
        } catch (PDOException $e) {
            echo "Exception PurchasingStatusWithStatus: " . $e->getMessage();
            return false;
        }
    }

    public function getPurchasingStatusByidPurchasingidStatus(int $idPurchasing, int $idStatus)
    {
        try {
            $res = $this->bd->select(
                "SELECT * FROM {$this->table} WHERE purchasing_id = :purchasing_id AND status_id = :status_id",
                [':purchasing_id' => $idPurchasing, ':status_id' => $idStatus]
            );
            return $res;
        } catch (PDOException $e) {
            echo "Exception PurchasingStatusByidPurchasingidStatus: " . $e->getMessage();
            return false;
        }
    }
}
