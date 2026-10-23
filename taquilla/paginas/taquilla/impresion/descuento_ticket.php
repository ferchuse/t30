<?php
/** Desglose histórico: usa los importes guardados, sin recalcular promociones. */
function descuentoTicket($boleto) {
    $promocion = isset($boleto['descuento_promocion']) ? (float)$boleto['descuento_promocion'] : 0;
    $puntos = isset($boleto['descuento_puntos']) ? (float)$boleto['descuento_puntos'] : 0;
    if ($promocion <= 0 && $puntos <= 0) { return ''; }
    $texto = '';
    if (isset($boleto['subtotal_boleto'])) {
        $texto .= 'Subtotal:             $' . number_format((float)$boleto['subtotal_boleto'], 2) . "\n";
    }
    if ($promocion > 0) {
        $codigo = isset($boleto['codigo_cupon']) ? preg_replace('/[^A-Za-z0-9_-]/', '', $boleto['codigo_cupon']) : '';
        if ($codigo !== '') {
            $texto .= 'Cupon: ' . wordwrap($codigo, 32, "\n", true) . "\n";
        }
        $texto .= 'Descuento promocion: -$' . number_format($promocion, 2) . "\n";
    }
    if ($puntos > 0) {
        $cantidad = isset($boleto['puntos_canjeados']) ? (int)$boleto['puntos_canjeados'] : 0;
        $texto .= 'Puntos utilizados: ' . $cantidad . "\n";
        $texto .= 'Descuento por puntos:-$' . number_format($puntos, 2) . "\n";
    }
    $texto .= 'Descuento total:     -$' . number_format($promocion + $puntos, 2) . "\n";
    return $texto;
}
