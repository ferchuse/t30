<?php
header('Content-Type: application/json; charset=utf-8');
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_COOKIE['id_usuarios'])) { http_response_code(401); echo json_encode(array('ok'=>false)); exit; }
require_once __DIR__.'/../../../conexi.php';
require_once __DIR__.'/promocion.php';
$id=filter_var(isset($_POST['cliente']) ? $_POST['cliente'] : '',FILTER_VALIDATE_INT,array('options'=>array('min_range'=>1)));
$subtotal=isset($_POST['subtotal']) && is_numeric($_POST['subtotal']) ? max(0,(float)$_POST['subtotal']) : 0;
try {
    $p=$id ? promocionPrimeraCompra($link,$id,$subtotal) : null;
    echo json_encode(array('ok'=>true,'promocion'=>$p ? array('codigo'=>$p['codigo_promocion'],'tipo'=>$p['tipo_descuento'],'valor'=>(float)$p['valor_descuento'],'importe'=>$p['importe'],'automatica'=>true) : null));
} catch (Exception $e) { http_response_code(500); echo json_encode(array('ok'=>false,'mensaje'=>'No fue posible consultar el beneficio.')); }
