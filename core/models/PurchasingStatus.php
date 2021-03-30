<?php

declare(strict_types=1);

namespace core\models;

use core\classes\Database;
use core\classes\Functions;
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
                    0, :data_status, :purchasing_id, :status_id)",
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

    public function getPurchasingStatusByidPurchasing(int $idPurchasing)
    {
        try {
            $res = $this->bd->select(
                "SELECT 
                    ps.data_status, ps.status_id,
                    CASE
                        WHEN s.nome_status = 'Enviar pedido' THEN 'Enviado'
                        WHEN s.nome_status = 'Pedido confirmado' THEN 'Confirmado'
                        WHEN s.nome_status = 'Pagamento pendente' THEN 'Pendente'
                        WHEN s.nome_status = 'Pagamento aprovado' THEN 'Aprovado'
                        WHEN s.nome_status = 'Preparando pedido' THEN 'Preparando'
                        WHEN s.nome_status = 'Pedido entregue' THEN 'Entregue'
                        WHEN s.nome_status = 'Pagamento Negado' THEN 'Negado'
                        END AS status,
                        CASE
                            WHEN s.nome_status = 'Enviar pedido' THEN 'badge-success'
                            WHEN s.nome_status = 'Pedido confirmado' THEN 'badge-primary'
                            WHEN s.nome_status = 'Pagamento pendente' THEN 'badge-warning'
                            WHEN s.nome_status = 'Pagamento aprovado' THEN 'badge-info'
                            WHEN s.nome_status = 'Preparando pedido' THEN 'badge-secondary'
                            WHEN s.nome_status = 'Pedido entregue' THEN 'badge-dark'
                            WHEN s.nome_status = 'Pagamento Negado' THEN 'badge-danger'
                        END AS cor                    
                FROM {$this->table} ps
                JOIN `status` s ON (s.id = ps.status_id)
                WHERE ps.purchasing_id = :purchasing_id 
                ORDER BY ps.id DESC LIMIT 1",
                [':purchasing_id' => $idPurchasing]
            );
            return $res;
        } catch (PDOException $e) {
            echo "Exception PurchasingStatusByidPurchasingidStatus: " . $e->getMessage();
            return false;
        }
    }


    public function getStatusPurchasingById(int $idPurchasing)
    {
        try {
            $res = $this->bd->select(
                "SELECT ps.status_id, ps.data_status, s.nome_status, s.mensagem_status
               FROM {$this->table} ps
               JOIN `status` s ON (s.id = ps.status_id)
               WHERE ps.purchasing_id = :purchasing_id",
                [':purchasing_id' => $idPurchasing]
            );
            return $res;
        } catch (PDOException $e) {
            echo "Exception getStatusPurchasingById: " . $e->getMessage();
            return false;
        }
    }
}