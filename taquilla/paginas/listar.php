<?php 
header("Content-Type: application/json; charset=UTF-8");
$objeto = json_decode($_POST["datos"],false);
$tabla = $objeto->tabla;
$campo = $objeto->campo;
$valor = $objeto->valor;

$query = "SELECT * FROM ";
echo json_encode($objeto);
?>