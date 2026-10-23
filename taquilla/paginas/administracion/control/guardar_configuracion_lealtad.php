<?php
header('Content-Type: application/json; charset=utf-8');
include("../../../conexi.php");
$link = Conectarse();

if (!isset($_COOKIE['id_usuarios'])) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'mensaje' => 'La sesión ha caducado.']);
    exit;
}

$activo = isset($_POST['activo']) ? 1 : 0;
$monto = isset($_POST['monto_por_punto']) ? (float) $_POST['monto_por_punto'] : 0;
$valor = isset($_POST['valor_punto']) ? (float) $_POST['valor_punto'] : 0;
$canje = isset($_POST['canje_minimo']) ? (int) $_POST['canje_minimo'] : 0;
if ($monto <= 0 || $valor <= 0 || $canje <= 0) {
    echo json_encode(['ok' => false, 'mensaje' => 'Todos los valores deben ser mayores a cero.']);
    exit;
}

$consulta = mysqli_prepare($link, "UPDATE configuracion_lealtad SET activo = ?, monto_por_punto = ?, valor_punto = ?, canje_minimo = ?, fecha_actualizacion = NOW() WHERE id_configuracion = 1");
mysqli_stmt_bind_param($consulta, 'iddi', $activo, $monto, $valor, $canje);
$ok = mysqli_stmt_execute($consulta);
mysqli_stmt_close($consulta);
echo json_encode(['ok' => $ok, 'mensaje' => $ok ? '' : 'No fue posible guardar la configuración.']);
