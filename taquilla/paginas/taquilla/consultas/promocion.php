<?php
function consultarCupon($link, $codigo, $subtotal, $bloquear = false, $idCliente = 0) {
    $q = mysqli_prepare($link, 'SELECT *, NOW() AS ahora FROM promociones WHERE codigo_promocion = ?' . ($bloquear ? ' FOR UPDATE' : ''));
    mysqli_stmt_bind_param($q, 's', $codigo);
    mysqli_stmt_execute($q);
    $p = mysqli_stmt_get_result($q)->fetch_assoc();
    mysqli_stmt_close($q);
    if (!$p) { throw new Exception('El cupón no existe.'); }
    if (!empty($p['primera_compra'])) {
        if (!$idCliente || !clientePrimeraCompra($link, $idCliente)) { throw new Exception('Esta promoción es exclusiva para la primera compra del cliente.'); }
    }
    if (!(int)$p['activo']) { throw new Exception('El cupón está inactivo.'); }
    if (($p['fecha_inicio'] && $p['ahora'] < $p['fecha_inicio']) || ($p['fecha_fin'] && $p['ahora'] > $p['fecha_fin'])) { throw new Exception('El cupón no está vigente.'); }
    if ($p['uso_limitado'] && $p['usos_realizados'] >= $p['limite_usos']) { throw new Exception('El cupón agotó sus usos.'); }
    $p['importe'] = round(min($subtotal, max(0, $p['tipo_descuento'] === 'Porcentaje' ? $subtotal * (float)$p['valor_descuento']/100 : (float)$p['valor_descuento'])), 2);
    return $p;
}
function clientePrimeraCompra($link, $idCliente) {
    $id = (int)$idCliente;
    $q = mysqli_query($link, "SELECT id_boletos FROM boletos WHERE id_cliente_frecuente={$id} AND estatus_boletos='Activo' LIMIT 1");
    if (!$q) { throw new Exception('No fue posible consultar las compras del cliente.'); }
    if (mysqli_num_rows($q)) { return false; }
    $q = mysqli_query($link, "SELECT id_uso FROM promociones_usos WHERE id_cliente_frecuente={$id} LIMIT 1");
    if (!$q) { throw new Exception('No fue posible consultar el historial de promociones.'); }
    return mysqli_num_rows($q) === 0;
}
function promocionPrimeraCompra($link, $idCliente, $subtotal, $bloquear = false) {
    if (!$idCliente || $subtotal <= 0 || !clientePrimeraCompra($link, $idCliente)) { return null; }
    $q = mysqli_query($link, "SELECT codigo_promocion FROM promociones WHERE primera_compra=1 AND activo=1 AND (fecha_inicio IS NULL OR fecha_inicio<=NOW()) AND (fecha_fin IS NULL OR fecha_fin>=NOW()) AND (uso_limitado=0 OR usos_realizados<limite_usos) ORDER BY id_promocion");
    if (!$q) { throw new Exception('No fue posible consultar promociones.'); }
    while ($r=mysqli_fetch_assoc($q)) {
        try { return consultarCupon($link,$r['codigo_promocion'],$subtotal,$bloquear,$idCliente); }
        catch (Exception $e) { if ($bloquear) { throw $e; } }
    }
    return null;
}
function registrarUsoPrimeraCompra($link, $p, $cliente, $boleto) {
    if (empty($p['primera_compra'])) { return; }
    $q=mysqli_prepare($link,'INSERT INTO promociones_usos (id_promocion,id_cliente_frecuente,id_boletos,fecha_uso) VALUES (?,?,?,NOW())');
    mysqli_stmt_bind_param($q,'iii',$p['id_promocion'],$cliente,$boleto);
    if (!mysqli_stmt_execute($q)) { throw new Exception('La promoción ya fue utilizada por el cliente.'); }
    mysqli_stmt_close($q);
}
