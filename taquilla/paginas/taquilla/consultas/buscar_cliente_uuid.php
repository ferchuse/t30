<?php
header('Content-Type: application/json; charset=utf-8');
include("../../../conexi.php");
$link = Conectarse();

if (!isset($_COOKIE['id_usuarios'])) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'mensaje' => 'La sesión ha caducado.']);
    exit;
}

$uuid = trim(isset($_POST['uuid']) ? $_POST['uuid'] : '');
if (!preg_match('/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/', $uuid)) {
    echo json_encode(['ok' => false, 'mensaje' => 'El código QR no contiene un UUID válido.']);
    exit;
}

$consulta = mysqli_prepare($link, "SELECT id_cliente_frecuente, uuid_cliente, nombre_cliente, apellidop_cliente, apellidom_cliente, telefono_cliente, clave_pais_cliente, puntos_cliente FROM clientes_frecuentes WHERE uuid_cliente = ? AND estatus_cliente = 'Activo' LIMIT 1");
mysqli_stmt_bind_param($consulta, 's', $uuid);
mysqli_stmt_execute($consulta);
$resultado = mysqli_stmt_get_result($consulta);
$cliente = mysqli_fetch_assoc($resultado);
mysqli_stmt_close($consulta);

if (!$cliente) {
    echo json_encode(['ok' => false, 'mensaje' => 'Cliente no encontrado o inactivo.']);
    exit;
}

$nombre = trim($cliente['nombre_cliente'] . ' ' . $cliente['apellidop_cliente'] . ' ' . $cliente['apellidom_cliente']);
$lealtad = mysqli_query($link, "SELECT activo, monto_por_punto, valor_punto, canje_minimo FROM configuracion_lealtad WHERE id_configuracion = 1 LIMIT 1");
$configuracion = $lealtad ? mysqli_fetch_assoc($lealtad) : null;
echo json_encode(['ok' => true, 'cliente' => [
    'id' => (int) $cliente['id_cliente_frecuente'],
    'uuid' => $cliente['uuid_cliente'],
    'nombre' => $nombre,
    'telefono' => trim($cliente['clave_pais_cliente'] . ' ' . $cliente['telefono_cliente']),
    'puntos' => (int) $cliente['puntos_cliente'],
], 'lealtad' => [
    'activa' => $configuracion ? (bool) $configuracion['activo'] : false,
    'monto_por_punto' => $configuracion ? (float) $configuracion['monto_por_punto'] : 0.0,
    'valor_punto' => $configuracion ? (float) $configuracion['valor_punto'] : 0.0,
    'canje_minimo' => $configuracion ? (int) $configuracion['canje_minimo'] : 0,
]]);
