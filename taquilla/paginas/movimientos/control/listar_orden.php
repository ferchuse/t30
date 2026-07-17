<?php 
    include('../../../conexi.php');
    $link = Conectarse();
    $lista = array();
    $respuesta = array();
    $tabla = $_POST['tabla'];
    
    if(isset($_POST['subconsulta']) && isset($_POST['group'])){
        $group = $_POST['group'];
        $subconsulta = $_POST['subconsulta'];
        $campo = $_POST['campo'];
        $id_campo = $_POST['id_campo'];
        $consulta = "SELECT * FROM $tabla $subconsulta WHERE $campo=$id_campo GROUP BY $group";
    }

    $query = mysqli_query($link,$consulta);
    if($query){
        $respuesta['num_rows'] = mysqli_num_rows($query);
            while($row = mysqli_fetch_assoc($query)){
                $lista[] = $row;
            }
            $respuesta['estatus'] = 'success';
            $respuesta['mensaje'] = $lista;
            $respuesta['query'] = $consulta;
    }else {
        $respuesta["estatus"] = "error";
        $respuesta["mensaje"] = "Error en ".$consulta.mysqli_error($link);
    }

    echo json_encode($respuesta);
?>