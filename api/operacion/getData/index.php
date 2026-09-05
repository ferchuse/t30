<?php
declare(strict_types=1);

/**
 * POST /api/operacion/getData/
 *
 * Devuelve un viaje por boleto. Los pagos se agrupan en el arreglo `pagos`
 * para que un mismo boleto pueda tener efectivo, tarjeta y transferencia.
 */
header('Content-Type: application/json; charset=utf-8');

function responder(int $codigo, array $cuerpo): void
{
    http_response_code($codigo);
    echo json_encode($cuerpo, JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION);
    exit;
}

function pagosDeFila(array $fila): array
{
    $pagos = [];

    // El anticipo de una recolección puede ser de una forma de pago distinta
    // de la liquidación registrada en el boleto.
    if ($fila['anticipo_recoleccion'] !== null && (float) $fila['anticipo_recoleccion'] > 0) {
        $pagos[] = [
            'medio' => $fila['forma_pago_recoleccion'] ?: 'Anticipo',
            'importe' => (float) $fila['anticipo_recoleccion'],
        ];
    }

    foreach ([
        'Efectivo' => 'efectivo',
        'Tarjeta' => 'tarjeta',
        'Transferencia' => 'transferencia',
    ] as $medio => $campo) {
        if ($fila[$campo] !== null && (float) $fila[$campo] > 0) {
            $pagos[] = ['medio' => $medio, 'importe' => (float) $fila[$campo]];
        }
    }

    // Compatibilidad con boletos anteriores a las columnas de pagos divididos.
    if ($pagos === [] && (float) $fila['total'] > 0) {
        $pagos[] = [
            'medio' => $fila['forma_pago'] ?: 'Sin especificar',
            'importe' => (float) $fila['total'],
        ];
    }

    return $pagos;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responder(405, ['status' => 'Error', 'mensaje' => 'El método permitido es POST.']);
}

$token = trim((string) ($_POST['token'] ?? ''));
$inicio = trim((string) ($_POST['inicio'] ?? ''));
$fin = trim((string) ($_POST['fin'] ?? ''));

if ($token === '' || $inicio === '' || $fin === '') {
    responder(400, ['status' => 'Error', 'mensaje' => 'token, inicio y fin son obligatorios.']);
}

$inicioFecha = DateTime::createFromFormat('!Y-m-d H:i:s', $inicio);
$finFecha = DateTime::createFromFormat('!Y-m-d H:i:s', $fin);
if (!$inicioFecha || !$finFecha || $inicioFecha->format('Y-m-d H:i:s') !== $inicio || $finFecha->format('Y-m-d H:i:s') !== $fin || $inicioFecha > $finFecha) {
    responder(400, ['status' => 'Error', 'mensaje' => 'Las fechas deben usar el formato yyyy-mm-dd HH:mm:ss y el inicio no puede ser posterior al fin.']);
}

// conexi.php selecciona config_local.php en localhost y config.php en t30.mx.
require_once dirname(__DIR__, 3) . '/taquilla/conexi.php';

$clave = mysqli_prepare($link, 'SELECT api_key FROM api_keys WHERE api_key = ? AND activo = \'SI\' LIMIT 1');
mysqli_stmt_bind_param($clave, 's', $token);
mysqli_stmt_execute($clave);
$tokenValido = mysqli_stmt_get_result($clave)->fetch_assoc();
mysqli_stmt_close($clave);

if (!$tokenValido || !hash_equals((string) $tokenValido['api_key'], $token)) {
    responder(401, ['status' => 'Error', 'mensaje' => 'Token no autorizado.']);
}

$sql = <<<'SQL'
SELECT
    b.id_boletos AS folioboleto,
    b.fecha_boletos AS fechaviaje,
    b.num_eco AS unidad,
    b.origen,
    b.destino,
    b.pasajeros,
    b.total,
    b.forma_pago,
    b.efectivo,
    b.tarjeta,
    b.transferencia,
    c.nombre_conductores AS operador,
    c.rfc_conductores AS rfcoperador,
    r.anticipo AS anticipo_recoleccion,
    r.forma_pago AS forma_pago_recoleccion
FROM boletos b
LEFT JOIN conductores c ON c.id_conductores = b.id_conductores
LEFT JOIN recolecciones r ON r.id_boletos = CAST(b.id_boletos AS CHAR)
WHERE b.fecha_boletos BETWEEN ? AND ?
  AND b.estatus_boletos = 'Activo'
ORDER BY b.fecha_boletos, b.id_boletos
SQL;

$consulta = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($consulta, 'ss', $inicio, $fin);
mysqli_stmt_execute($consulta);
$resultado = mysqli_stmt_get_result($consulta);
$viajes = [];

while ($fila = mysqli_fetch_assoc($resultado)) {
    $viajes[] = [
        'unidad' => $fila['unidad'] === null ? null : (int) $fila['unidad'],
        'folioboleto' => (int) $fila['folioboleto'],
        'fechaviaje' => $fila['fechaviaje'],
        'operador' => $fila['operador'],
        'rfcoperador' => $fila['rfcoperador'],
        'origen' => $fila['origen'],
        'destino' => $fila['destino'],
        'pasajeros' => $fila['pasajeros'] === null ? 0 : (int) $fila['pasajeros'],
        'importe' => (float) $fila['total'],
        'pagos' => pagosDeFila($fila),
    ];
}
mysqli_stmt_close($consulta);

responder(200, ['status' => 'Success', 'viajes' => $viajes]);
