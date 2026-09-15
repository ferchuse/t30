<?php
declare(strict_types=1);

/** POST /api/operacion/getDestinations/ */
header('Content-Type: application/json; charset=utf-8');

function responder(int $codigo, array $cuerpo): void
{
    http_response_code($codigo);
    echo json_encode($cuerpo, JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responder(405, ['status' => 'Error', 'mensaje' => 'El método permitido es POST.']);
}

$token = trim((string) ($_POST['token'] ?? ''));
if ($token === '') {
    responder(400, ['status' => 'Error', 'mensaje' => 'El token es obligatorio.']);
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

$resultado = mysqli_query(
    $link,
    "SELECT id_precio, destino, precio, precio_ejecutiva
     FROM destinos
     WHERE destino IS NOT NULL AND TRIM(destino) <> ''
     ORDER BY destino, id_precio"
);

if (!$resultado) {
    responder(500, ['status' => 'Error', 'mensaje' => 'No fue posible obtener los destinos.']);
}

$destinos = [];
while ($fila = mysqli_fetch_assoc($resultado)) {
    $destinos[] = [
        'iddestino' => (int) $fila['id_precio'],
        'destino' => $fila['destino'],
        'costo1' => $fila['precio'] === null ? 0.0 : (float) $fila['precio'],
        'costo2' => $fila['precio_ejecutiva'] === null ? 0.0 : (float) $fila['precio_ejecutiva'],
    ];
}
mysqli_free_result($resultado);

responder(200, ['status' => 'Success', 'destinos' => $destinos]);
