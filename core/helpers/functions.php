<?php

function response_json($array, $status_code)
{
    echo json_encode($array, http_response_code($status_code));
}

function formataDataPtBr($data)
{
    if (!empty($data)) {
        $dt = new \DateTime($data);
        return $dt->format('d/m/Y');
    }
}

function formataDateTimePtBr($data)
{
    if (!empty($data)) {
        $dt = new \DateTime($data);
        return $dt->format('d/m/Y H:i:s');
    }
}

function formatarMoedaPtBr($valor)
{
    if (!empty($valor)) {
        return "R$ " . number_format($valor, 2, ",", ".");
    }
}