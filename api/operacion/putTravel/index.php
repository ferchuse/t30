<?php
declare(strict_types=1);

/** POST /api/operacion/putTravel/ */
header('Content-Type: application/json; charset=utf-8');

function responder(int $codigo, array $cuerpo): void
{
    http_response_code($codigo);
    echo json_encode($cuerpo, JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION);
    exit;
}

function destinoPorId(mysqli $link, int $idDestino): ?array
{
    $consulta = mysqli_prepare($link, "SELECT id_precio, destino, cp_destino FROM destinos WHERE id_precio = ? AND destino IS NOT NULL AND TRIM(destino) <> '' LIMIT 1");
    mysqli_stmt_bind_param($consulta, 'i', $idDestino);
    mysqli_stmt_execute($consulta);
    $destino = mysqli_stmt_get_result($consulta)->fetch_assoc() ?: null;
    mysqli_stmt_close($consulta);

    return $destino;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responder(405, ['status' => 'Error', 'mensaje' => 'El método permitido es POST.']);
}

$token = trim((string) ($_POST['token'] ?? ''));
$telefono = trim((string) ($_POST['telefono'] ?? ''));
$fecha = trim((string) ($_POST['fecha'] ?? ''));
$origenId = filter_var($_POST['origen'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$destinoId = filter_var($_POST['destino'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$pasajeros = filter_var($_POST['pasajeros'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$montoTexto = trim((string) ($_POST['monto'] ?? ''));
$status = trim((string) ($_POST['status'] ?? ''));

if ($token === '' || $telefono === '' || $fecha === '' || $origenId === false || $destinoId === false || $pasajeros === false || $montoTexto === '' || $status === '') {
    responder(400, ['status' => 'Error', 'mensaje' => 'token, telefono, fecha, origen, destino, pasajeros, monto y status son obligatorios.']);
}
if ($status !== '1') {
    responder(400, ['status' => 'Error', 'mensaje' => 'Por el momento putTravel solo admite status=1 para crear viajes.']);
}
if (!preg_match('/^\d+(?:\.\d{1,2})?$/', $montoTexto) || (float) $montoTexto < 0) {
    responder(400, ['status' => 'Error', 'mensaje' => 'monto debe ser un importe positivo con hasta dos decimales.']);
}

$fechaViaje = DateTime::createFromFormat('!Y-m-d H:i', $fecha);
if (!$fechaViaje || $fechaViaje->format('Y-m-d H:i') !== $fecha) {
    responder(400, ['status' => 'Error', 'mensaje' => 'fecha debe usar el formato yyyy-mm-dd HH:mm.']);
}

require_once dirname(__DIR__, 3) . '/taquilla/conexi.php';

$consultaToken = mysqli_prepare($link, "SELECT api_key FROM api_keys WHERE api_key = ? AND activo = 'SI' LIMIT 1");
mysqli_stmt_bind_param($consultaToken, 's', $token);
mysqli_stmt_execute($consultaToken);
$tokenValido = mysqli_stmt_get_result($consultaToken)->fetch_assoc();
mysqli_stmt_close($consultaToken);
if (!$tokenValido || !hash_equals((string) $tokenValido['api_key'], $token)) {
    responder(401, ['status' => 'Error', 'mensaje' => 'Token no autorizado.']);
}

$clienteConsulta = mysqli_prepare($link, "SELECT id_cliente_frecuente, nombre_cliente, apellidop_cliente, apellidom_cliente FROM clientes_frecuentes WHERE telefono_cliente = ? AND estatus_cliente = 'Activo' LIMIT 1");
mysqli_stmt_bind_param($clienteConsulta, 's', $telefono);
mysqli_stmt_execute($clienteConsulta);
$cliente = mysqli_stmt_get_result($clienteConsulta)->fetch_assoc();
mysqli_stmt_close($clienteConsulta);
if (!$cliente) {
    responder(404, ['status' => 'Error', 'mensaje' => 'No existe un cliente frecuente activo para el teléfono indicado.']);
}

$origen = destinoPorId($link, (int) $origenId);
$destino = destinoPorId($link, (int) $destinoId);
if (!$origen || !$destino) {
    responder(404, ['status' => 'Error', 'mensaje' => 'La clave de origen o destino no existe en el catálogo de destinos.']);
}

$nombrePasajero = trim(
    (string) $cliente['nombre_cliente'] . ' '
    . (string) $cliente['apellidop_cliente'] . ' '
    . (string) $cliente['apellidom_cliente']
);
$idCliente = (int) $cliente['id_cliente_frecuente'];
$fechaRecoleccion = $fechaViaje->format('Y-m-d H:i:s');
$descripcionOrigen = (string) $origen['destino'];
$descripcionDestino = (string) $destino['destino'];
$codigoPostalDestino = (string) ($destino['cp_destino'] ?? '');
$monto = round((float) $montoTexto, 2);
// En el módulo existente, destino identifica el lugar de recogida.
// Conservamos por separado ambos extremos del viaje de la app.
$insertar = mysqli_prepare($link, "INSERT INTO recolecciones
    (id_cliente_frecuente,fecha_recoleccion,fecha_captura,origen,destino,destino_viaje,nombre_pasajero,pasajeros,celular,total,anticipo,restante,estatus_recoleccion,taquilla,id_terminal)
    VALUES (?, ?, NOW(), ?, ?, ?, ?, ?, ?, ?, 0, ?, 'PENDIENTE', 'APP', 0)");
if (!$insertar) { responder(500, array('status'=>'Error','mensaje'=>'No fue posible preparar la reserva.')); }
mysqli_stmt_bind_param($insertar, 'isssssisdd', $idCliente, $fechaRecoleccion, $descripcionOrigen, $descripcionOrigen, $descripcionDestino, $nombrePasajero, $pasajeros, $telefono, $monto, $monto);
if (!mysqli_stmt_execute($insertar)) {
    mysqli_stmt_close($insertar);
    responder(500, array('status'=>'Error','mensaje'=>'No fue posible crear la reserva.'));
}
$idRecoleccion=(int)mysqli_insert_id($link);
mysqli_stmt_close($insertar);
responder(200, array('status'=>'Success','id_recoleccion'=>$idRecoleccion,'total'=>$monto,'anticipo'=>0.0,'restante'=>$monto));
