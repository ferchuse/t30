<?php
function clientePuntosTicket($boleto) {
    $nombre = isset($boleto['nombre_pasajero']) ? trim($boleto['nombre_pasajero']) : '';
    $nombre = preg_replace('/[\x00-\x1F\x7F]/', ' ', $nombre);
    $texto = 'Cliente: ' . wordwrap(normalizeChars($nombre !== '' ? $nombre : 'Publico en general'), 32, "\n", true) . "\n";
    if (!empty($boleto['id_cliente_frecuente'])) {
        $puntos = isset($boleto['puntos_otorgados']) ? max(0, (int)$boleto['puntos_otorgados']) : 0;
        $texto .= 'Puntos acumulados por esta venta: ' . $puntos . "\n";
    }
    return $texto;
}
