<?php
declare(strict_types=1);

/**
 * POST /api/operacion/putCustomer/
 * Crea o actualiza un cliente frecuente, identificado de forma única por teléfono.
 */
header('Content-Type: application/json; charset=utf-8');

function responder(int $codigo, array $cuerpo): void
{
    http_response_code($codigo);
    echo json_encode($cuerpo, JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responder(405, ['status' => 'Error', 'mensaje' => 'El método permitido es POST.']);
}

$token = trim((string) ($_POST['token'] ?? ''));
$nombre = trim((string) ($_POST['nombre'] ?? ''));
$apellidoPaterno = trim((string) ($_POST['apellidop'] ?? ''));
$apellidoMaterno = trim((string) ($_POST['apelidom'] ?? ''));
$rfc = strtoupper(trim((string) ($_POST['rfc'] ?? '')));
$correo = trim((string) ($_POST['mail'] ?? ''));
$telefono = trim((string) ($_POST['telefono'] ?? ''));

if ($token === '' || $nombre === '' || $apellidoPaterno === '' || $apellidoMaterno === '' || $correo === '' || $telefono === '') {
    responder(400, ['status' => 'Error', 'mensaje' => 'token, nombre, apellidop, apelidom, mail y telefono son obligatorios.']);
}

if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    responder(400, ['status' => 'Error', 'mensaje' => 'El correo no tiene un formato válido.']);
}

if ($rfc !== '' && !preg_match('/^[A-ZÑ&]{3,4}[0-9]{6}[A-Z0-9]{3}$/', $rfc)) {
    responder(400, ['status' => 'Error', 'mensaje' => 'El RFC no tiene un formato válido.']);
}

require_once dirname(__DIR__, 3) . '/taquilla/conexi.php';

$clave = mysqli_prepare($link, 'SELECT api_key FROM api_keys WHERE api_key = ? AND activo = \'SI\' LIMIT 1');
mysqli_stmt_bind_param($clave, 's', $token);
mysqli_stmt_execute($clave);
$tokenValido = mysqli_stmt_get_result($clave)->fetch_assoc();
mysqli_stmt_close($clave);

if (!$tokenValido || !hash_equals((string) $tokenValido['api_key'], $token)) {
    responder(401, ['status' => 'Error', 'mensaje' => 'Token no autorizado.']);
}

// El teléfono es el identificador funcional indicado para clientes frecuentes.
$buscar = mysqli_prepare($link, 'SELECT id_cliente_frecuente FROM clientes_frecuentes WHERE telefono_cliente = ? LIMIT 1');
mysqli_stmt_bind_param($buscar, 's', $telefono);
mysqli_stmt_execute($buscar);
$cliente = mysqli_stmt_get_result($buscar)->fetch_assoc();
mysqli_stmt_close($buscar);

if ($cliente) {
    $actualizar = mysqli_prepare(
        $link,
        'UPDATE clientes_frecuentes
         SET nombre_cliente = ?, apellidop_cliente = ?, apellidom_cliente = ?, correo_cliente = ?, rfc_cliente = ?
         WHERE id_cliente_frecuente = ?'
    );
    $idCliente = (int) $cliente['id_cliente_frecuente'];
    mysqli_stmt_bind_param($actualizar, 'sssssi', $nombre, $apellidoPaterno, $apellidoMaterno, $correo, $rfc, $idCliente);
    $ejecutado = mysqli_stmt_execute($actualizar);
    $error = mysqli_stmt_error($actualizar);
    mysqli_stmt_close($actualizar);
} else {
    $insertar = mysqli_prepare(
        $link,
        'INSERT INTO clientes_frecuentes
         (nombre_cliente, apellidop_cliente, apellidom_cliente, telefono_cliente, correo_cliente, rfc_cliente)
         VALUES (?, ?, ?, ?, ?, ?)'
    );
    mysqli_stmt_bind_param($insertar, 'ssssss', $nombre, $apellidoPaterno, $apellidoMaterno, $telefono, $correo, $rfc);
    $ejecutado = mysqli_stmt_execute($insertar);
    $error = mysqli_stmt_error($insertar);
    $idCliente = (int) mysqli_insert_id($link);
    mysqli_stmt_close($insertar);
}

if (!$ejecutado) {
    responder(500, ['status' => 'Error', 'mensaje' => 'No fue posible guardar el cliente.', 'detalle' => $error]);
}

responder(200, ['status' => 'Success', 'id' => $idCliente]);
