<?php
header('Content-Type: application/json; charset=utf-8');
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_COOKIE['id_usuarios'])) { http_response_code(401); echo json_encode(array('ok'=>false,'mensaje'=>'Sesión no válida.')); exit; }
require_once __DIR__ . '/../../../conexi.php';
require_once __DIR__ . '/promocion.php';
try {
    $codigo = isset($_POST['codigo']) && is_string($_POST['codigo']) ? strtoupper(trim($_POST['codigo'])) : '';
    $subtotal = isset($_POST['subtotal']) && is_numeric($_POST['subtotal']) ? (float)$_POST['subtotal'] : 0;
    if ($subtotal <= 0) { throw new Exception('Selecciona primero el destino y su tarifa.'); }
    $idCliente=isset($_POST['cliente']) ? (int)$_POST['cliente'] : 0;
    $p = consultarCupon($link, $codigo, $subtotal, false, $idCliente);
    echo json_encode(array('ok'=>true,'codigo'=>$p['codigo_promocion'],'tipo'=>$p['tipo_descuento'],'valor'=>(float)$p['valor_descuento'],'importe'=>$p['importe']));
} catch (Exception $e) { echo json_encode(array('ok'=>false,'mensaje'=>$e->getMessage())); }
