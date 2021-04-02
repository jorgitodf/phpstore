<?php

declare(strict_types=1);

namespace core\models;

use core\classes\Database;
use core\classes\Functions;
use Exception;

class Purchasing
{
    private $bd;
    private $table = 'purchasing';
    private $pp;
    private $ps;

    public function __construct()
    {
        $this->bd = new Database();
        $this->pp = new PurchaseProduct();
        $this->ps = new PurchasingStatus();
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

    public function getPurchasingByIdUser(int $userId): array
    {
        $pur = $this->bd->select("SELECT id, data_compra, codigo_compra, users_id 
            FROM purchasing WHERE users_id = :user_id", [':user_id' => $userId]);

        foreach ($pur as $key => $value) {
            $pur[$key]->produtos = $this->bd->select("SELECT 
                p.id AS purchasing_id, p.data_compra, p.codigo_compra, prod.nome_produto, 
                prod.imagem, pp.preco_unidade, pp.quantidade
            FROM purchasing p
            JOIN purchase_product pp ON (pp.purchasing_id = p.id)
            JOIN products prod ON (pp.products_id = prod.id)
            WHERE pp.purchasing_id = :purchasing_id", [':purchasing_id' => $value->id]);
        }

        foreach ($pur as $key => $value) {
            foreach ($pur[$key]->produtos as $row) {
                $pur[$key]->total = $this->bd->select("SELECT SUM(preco_unidade) AS total
                FROM purchase_product
                WHERE purchasing_id = :purchasing_id", [':purchasing_id' => $row->purchasing_id])[0]->total;
            }
        }

        foreach ($pur as $key => $value) {
            $r = $this->ps->getPurchasingStatusByidPurchasing((int)$value->id);
            $pur[$key]->status = $r[0]->status;
            $pur[$key]->cor = $r[0]->cor;
        }

        return $pur;
    }

    public function getPurchasingById(int $id): array
    {
        $pur = $this->bd->select("SELECT id, data_compra, codigo_compra, users_id 
            FROM purchasing WHERE id = :id", [':id' => $id]);


        foreach ($pur as $key => $value) {
            $array[] = $this->bd->select("SELECT 
                p.id AS purchasing_id, prod.nome_produto, 
                prod.imagem, pp.preco_unidade, pp.quantidade
            FROM purchasing p
            JOIN purchase_product pp ON (pp.purchasing_id = p.id)
            JOIN products prod ON (pp.products_id = prod.id)
            WHERE pp.purchasing_id = :purchasing_id", [':purchasing_id' => $value->id]);
        }

        foreach ($pur as $key => $value) {
            foreach ($array as $k => $row) {
                if ($value->id == $row[$k]->purchasing_id) {
                    $pur[$key]->produtos = $array[$k];
                }
            }
        }

        $total = 0;

        foreach ($pur as $key => $value) {
            foreach ($pur[$key]->produtos as $row) {
                $total += $row->preco_unidade;
                $pur[$key]->total = $total;
            }
        }

        foreach ($pur as $key => $value) {
            $r = $this->ps->getPurchasingStatusByidPurchasing((int)$value->id);
            $pur[$key]->status = $r[0]->status;
            $pur[$key]->cor = $r[0]->cor;
        }

        return $pur;
    }

    public function getPurchasingByIdWithStatus(int $id)
    {
        $pur = $this->bd->select("SELECT id, data_compra, codigo_compra, users_id 
            FROM purchasing WHERE id = :id", [':id' => $id]);

        if (count($pur) > 0) {
            foreach ($pur as $key => $value) {
                $array[] = $this->bd->select("SELECT 
                    p.id AS purchasing_id, prod.nome_produto, 
                    prod.imagem, pp.preco_unidade, pp.quantidade
                FROM purchasing p
                JOIN purchase_product pp ON (pp.purchasing_id = p.id)
                JOIN products prod ON (pp.products_id = prod.id)
                WHERE pp.purchasing_id = :purchasing_id", [':purchasing_id' => $value->id]);
            }

            foreach ($pur as $key => $value) {
                foreach ($array as $k => $row) {
                    if ($value->id == $row[$k]->purchasing_id) {
                        $pur[$key]->produtos = $array[$k];
                    }
                }
            }

            $total = 0;

            foreach ($pur as $key => $value) {
                foreach ($pur[$key]->produtos as $row) {
                    $total += $row->preco_unidade;
                    $pur[$key]->total = $total;
                }
            }

            $r = $this->ps->getPurchasingStatusByidPurchasing((int)$value->id);
            $pur[0]->status = $r[0]->status;
            $pur[0]->cor = $r[0]->cor;

            $pur[0]->statuspagamento = $this->ps->getStatusPurchasingById((int)$value->id);

            return $pur;
        } else {
            return false;
        }
    }
}