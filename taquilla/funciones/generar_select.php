<?php
	
	// include("../conexi.php");
	
	// $link = Conectarse();
	
	function generar_select(
	$link, 
	$tabla,
	$llave_primaria, 
	$campo_etiqueta ,
	$filtro = false, 
	$disabled = false ,
	$required = false , 
	$id_selected = 0, 
	$data_indice = 0, 
	$name = "", 
	$id = '' , 
	$clase = "" ){
		$consulta = "SELECT * FROM $tabla ";
		
		
		if($tabla == "usuarios"){
			
			$consulta.= " WHERE estatus_usuarios = 'Activo' 
			AND empresa_asignada = {$_COOKIE["empresa_asignada"]}";
		}
		if($tabla == "unidades"){
			
			$consulta.= " WHERE  estatus_unidades = 'Activo' 
			AND id_empresas = {$_COOKIE["empresa_asignada"]}";
		}
		if($tabla == "conductores"){
			
			$consulta.= " WHERE  estatus_conductores = 'Activo' ";
		}
		
		$consulta.= " ORDER BY $campo_etiqueta";
		
		if($name == ""){
			$name = $llave_primaria;
		}
		if($id == ""){
			$id = $llave_primaria;
		}
		
		
		$select = "<select data-indice='$data_indice'";
		
		$select .= $required ? " required " : " ";
		$select .= $disabled ? " disabled " : " ";
		$select.= "class='form-control $clase' name='$name' id='$id' >";
		if($filtro){
			$select .= "<option value=''>Todos</option>";
		} 
		else{
			$select .= "<option value=''>Seleccione...</option>";
		}
		
		$result = mysqli_query($link, $consulta);
		
		if(!$result){
			
			die($consulta. mysqli_error($link));
			}
		
		while($fila = mysqli_fetch_assoc($result)){
			$select.="<option value='".$fila[$llave_primaria]."'";
			$select.=$fila[$llave_primaria] == $id_selected ? " selected" : "" ;
			if($tabla == "taquillas"){
				
				$select .= " data-hora_salida='".date("H:i", strtotime($fila["hora_salida"]))."' ";
			}
			if($tabla == "conductores"){
				
				$select.=" >".$fila["nombre_conductores"]."</option>";
				// . " ".$fila["apellido_paterno"] ." " .$fila["apellido_materno"]
				
			}
			else{
				
				$select.=" >".$fila[$campo_etiqueta] ."</option>";
				
			}
			
			
			
			
		}
		$select.="</select> ";
		
		return $select;
	}
	
?>