<?php
header('Content-Type: application/json; charset=utf-8');
function responderPromocion($codigo, $mensaje, $id = null) {
    http_response_code($codigo);
    echo json_encode(array('ok' => $codigo === 200, 'mensaje' => $mensaje, 'id_promocion' => $id));
    exit;
}
function campoPromocion($nombre) {
    return isset($_POST[$nombre]) && is_string($_POST[$nombre]) ? trim($_POST[$nombre]) : '';
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { responderPromocion(405, 'Método no permitido.'); }
$usuario = filter_var(isset($_COOKIE['id_usuarios']) ? $_COOKIE['id_usuarios'] : '', FILTER_VALIDATE_INT, array('options' => array('min_range' => 1)));
if (!$usuario) { responderPromocion(401, 'La sesión ha caducado.'); }
require_once __DIR__ . '/../../../conexi.php';
$permiso = mysqli_prepare($link, "SELECT 1 FROM permisos p JOIN paginas g USING(id_paginas) WHERE p.id_usuarios = ? AND g.url_paginas = 'descuentos.php' AND g.categoria_paginas = 'Administración' AND p.permiso IN ('Administrador','Supervisor','Escritura') LIMIT 1");
mysqli_stmt_bind_param($permiso, 'i', $usuario);
mysqli_stmt_execute($permiso);
$autorizado = mysqli_stmt_get_result($permiso)->fetch_assoc();
mysqli_stmt_close($permiso);
if (!$autorizado) { responderPromocion(403, 'No tienes permiso para guardar promociones.'); }
$nombre = campoPromocion('nombre_promocion');
$codigo = strtoupper(campoPromocion('codigo_promocion'));
$tipo = campoPromocion('tipo_descuento');
$primeraCompra = campoPromocion('primera_compra') === '1' ? 1 : 0;
$activo = campoPromocion('activo') === '1' ? 1 : 0;
$valor = campoPromocion('valor_descuento');
if ($nombre === '' || mb_strlen($nombre, 'UTF-8') > 150 || !preg_match('/^[A-Z0-9_-]{1,64}$/D', $codigo)) {
    responderPromocion(400, 'Indica el nombre y un código de hasta 64 letras, números, guiones o guiones bajos.');
}
if (!in_array($tipo, array('Porcentaje','Monto'), true) || !preg_match('/^\d{1,8}(\.\d{1,2})?$/D', $valor) || (float)$valor <= 0 || ($tipo === 'Porcentaje' && (float)$valor > 100)) {
    responderPromocion(400, 'El valor debe ser mayor a cero, con hasta dos decimales. El porcentaje no puede superar 100.');
}
$inicioSql = null;
$finSql = null;
if (campoPromocion('tiene_vigencia') === '1') {
$inicioTexto = campoPromocion('fecha_inicio');
$finTexto = campoPromocion('fecha_fin');
$inicio = DateTime::createFromFormat('!Y-m-d\TH:i', $inicioTexto);
$fin = DateTime::createFromFormat('!Y-m-d\TH:i', $finTexto);
if (!$inicio || !$fin || $inicio->format('Y-m-d\TH:i') !== $inicioTexto || $fin->format('Y-m-d\TH:i') !== $finTexto || $fin <= $inicio) {
    responderPromocion(400, 'La vigencia debe contener fechas válidas y terminar después de su inicio.');
}
$inicioSql = $inicio->format('Y-m-d H:i:s');
$finSql = $fin->format('Y-m-d H:i:s');
}
$limitado = campoPromocion('uso_limitado') === '1' ? 1 : 0;
$limite = $limitado ? filter_var(campoPromocion('limite_usos'), FILTER_VALIDATE_INT, array('options' => array('min_range'=>1, 'max_range'=>2147483647))) : null;
if ($limitado && !$limite) { responderPromocion(400, 'Indica una cantidad de usos entera mayor a cero.'); }
$id = filter_var(campoPromocion('id_promocion') === '' ? '0' : campoPromocion('id_promocion'), FILTER_VALIDATE_INT, array('options'=>array('min_range'=>0)));
if ($id === false) { responderPromocion(400, 'Identificador de promoción inválido.'); }
try {
    mysqli_begin_transaction($link);
    if ($id > 0) {
        $buscar = mysqli_prepare($link, 'SELECT usos_realizados FROM promociones WHERE id_promocion = ? FOR UPDATE');
        mysqli_stmt_bind_param($buscar, 'i', $id);
        mysqli_stmt_execute($buscar);
        $actual = mysqli_stmt_get_result($buscar)->fetch_assoc();
        mysqli_stmt_close($buscar);
        if (!$actual) { mysqli_rollback($link); responderPromocion(404, 'La promoción ya no existe.'); }
        if ($limitado && $limite < (int)$actual['usos_realizados']) { mysqli_rollback($link); responderPromocion(400, 'El límite no puede ser menor a los usos realizados.'); }
        $consulta = mysqli_prepare($link, 'UPDATE promociones SET nombre_promocion=?, codigo_promocion=?, tipo_descuento=?, valor_descuento=?, fecha_inicio=?, fecha_fin=?, uso_limitado=?, limite_usos=?, activo=? WHERE id_promocion=?');
        mysqli_stmt_bind_param($consulta, 'ssssssiiii', $nombre,$codigo,$tipo,$valor,$inicioSql,$finSql,$limitado,$limite,$activo,$id);
    } else {
    $consulta = mysqli_prepare($link, 'INSERT INTO promociones (nombre_promocion,codigo_promocion,tipo_descuento,valor_descuento,fecha_inicio,fecha_fin,uso_limitado,limite_usos,activo,fecha_registro) VALUES (?,?,?,?,?,?,?,?,?,NOW())');
    if (!$consulta) { throw new Exception('No se pudo preparar', mysqli_errno($link)); }
    mysqli_stmt_bind_param($consulta, 'ssssssiii', $nombre,$codigo,$tipo,$valor,$inicioSql,$finSql,$limitado,$limite,$activo);
    }
    if (!mysqli_stmt_execute($consulta)) { throw new Exception('No se pudo guardar', mysqli_stmt_errno($consulta)); }
    if (!$id) { $id = mysqli_insert_id($link); }
    mysqli_stmt_close($consulta);
    $opcion = mysqli_prepare($link, 'UPDATE promociones SET primera_compra=? WHERE id_promocion=?');
    mysqli_stmt_bind_param($opcion, 'ii', $primeraCompra, $id);
    if (!mysqli_stmt_execute($opcion)) { throw new Exception('No fue posible guardar la opción de primera compra.'); }
    mysqli_stmt_close($opcion);
    mysqli_commit($link);
    responderPromocion(200, 'Promoción guardada.', $id);
} catch (Exception $e) {
    mysqli_rollback($link);
    if ((int)$e->getCode() === 1062) { responderPromocion(409, 'Ese código ya existe. Utiliza otro.'); }
    error_log('guardar_promocion: ' . $e->getMessage());
    responderPromocion(500, 'No fue posible guardar la promoción.');
}
