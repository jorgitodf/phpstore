<?php

declare(strict_types=1);

namespace core\models;

use core\classes\Database;
use Exception;
use PDOException;

class PaymentStatus
{
    private $bd;
    private $table = 'payment_status';

    public function __construct()
    {
        $this->bd = new Database();
    }

    public function saveDataPaymentStatus(int $statusId, int $purchasingId, string $data_status)
    {
        $con = $this->bd->getConnection();

        $con->beginTransaction();

        try {
            $res = $this->bd->insert(
                "INSERT INTO {$this->table} VALUES (
                0, :status_id, :purchasing_id, :data_status)",
                [
                    ':status_id' => $statusId,
                    ':purchasing_id' => $purchasingId,
                    ':data_status' => $data_status
                ]
            );

            if (!is_string($res)) {
                $con->commit();
                return true;
            } else {
                $con->rollBack();
                throw new Exception($res);
                return true;
            }
        } catch (PDOException $e) {
            echo "Exception saveDataPaymentStatus: " . $e->getMessage();
            return false;
        }
    }

    public function getPaymentStatusWithStatus(int $idPurchasing)
    {
        try {
            $res = $this->bd->select(
                "SELECT date_format(ps.data_status, '%d/%m/%Y às %H:%i:%s') AS data_status, s.nome_status, 
                    s.mensagem_status, ps.status_id
                 FROM {$this->table} ps JOIN `status` s ON (s.id = ps.status_id)
            WHERE ps.purchasing_id = :purchasing_id ORDER BY ps.id DESC LIMIT 1",
                [':purchasing_id' => $idPurchasing]
            );
            return $res;
        } catch (PDOException $e) {
            echo "Exception getPaymentStatusWithStatus: " . $e->getMessage();
            return false;
        }
    }
}
