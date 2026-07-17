<?php 
include('../conexi.php');
$link = Conectarse();
header("Content-Type: application/json; charset=UTF-8");

$lista = array();
$respuesta = array();
$objeto = json_decode($_POST["datos"],false);

$tabla = $objeto->tabla;
$campo = $objeto->campo;
$valor = $objeto->valor;

$query = "SELECT * FROM $tabla WHERE $campo BETWEEN CURDATE() AND '$valor' ";
$execute = mysqli_query($link,$query);

if($execute){
    $respuesta['num_rows'] =  mysqli_num_rows($execute);
    while($row = mysqli_fetch_assoc($execute)){
        $lista[] = $row;
    }
    $respuesta['estatus'] = 'success';
    $respuesta['mensaje'] = $lista;
    //$respuesta['consulta'] = $query;
}else{
    $respuesta['estatus'] = 'error';
    $respuesta['mensaje'] = 'Error en '.$query.mysqli_error($link);
}


echo json_encode($respuesta);
?>