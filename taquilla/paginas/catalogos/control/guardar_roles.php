<?php 
	include('../../../conexi.php');
	$link = Conectarse();
	
	$respuesta = array();
	
	//inserta rol
	if($_POST['id_roles'] == ''){
		
		$insert_rol ="INSERT INTO roles SET 
		nombre_roles = '{$_POST['nombre_roles']}' , 
		numero_roles ='{$_POST['numero_roles']}'";	
		
		$result_rol = 	mysqli_query($link,$insert_rol);
		
		if($result_rol){
			$respuesta["estatus"] = "success";
			$respuesta["mensaje"] = "Agregado";
			$respuesta["insert_rol"] = $insert_rol;
			
			$respuesta["id_rol"] = mysqli_insert_id($link);
			
		}
		else{
			
			$respuesta["estatus"] = "error";
			$respuesta["mensaje"] = "Error en $insert_rol ".mysqli_error($link);		
		}
		
	}
	else{
		
		//Update
		
		$update_rol ="UPDATE roles SET 
		nombre_roles = '{$_POST['nombre_roles']}' , 
		numero_roles ='{$_POST['numero_roles']}'
		WHERE id_roles ='{$_POST['id_roles']}'";	
		
		$result_rol = 	mysqli_query($link,$update_rol);
		
		
		
		if($result_rol){
			$respuesta["estatus"] = "success";
			$respuesta["mensaje"] = "Agregado";
			$respuesta['id_rol'] = $_POST['id_roles'];
			
			$delete_vueltas = "DELETE FROM roles_destinos WHERE id_roles= '{$_POST['id_roles']}'";
			
			$respuesta["result_delete_vueltas"] = mysqli_query($link,$delete_vueltas);
			
			
		}
		else{
			
			$respuesta["estatus"] = "error";
			$respuesta["mensaje"] = "Error en $insert_rol ".mysqli_error($link);		
		}
		
		
		
	}
	
	
	
	
	//inserta los destinos de cada vuelta 
	foreach($_POST['id_origen'] as $i=> $id_origen){
		//borrar destinos anteriores 
		
		$insert_rol_destino ="INSERT INTO roles_destinos SET 
		id_roles = {$respuesta['id_rol']} , 
		origen = {$_POST['id_origen'][$i]} ,
		destino = {$_POST['id_destino'][$i]} 
		";	
		
		if(	mysqli_query($link,$insert_rol_destino)){
			$respuesta["vueltas"]['estatus'] = "success";
			
		}
		else{
			
			$respuesta["vueltas"]['estatus'] = "error".mysqli_error($link);
		}
		
		$respuesta["vueltas"][] = $insert_rol_destino;
	}
	
	
	
	
	echo json_encode($respuesta);
	
?>