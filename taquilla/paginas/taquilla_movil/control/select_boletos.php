<?php
	session_start();
	include("../conexi.php");
	
	$link = Conectarse();
	 
	$tabla = , $llave_primaria, $campo_etiqueta ,$filtro = false, $disabled = false ,$required = false , $id_selected = 0, $data_indice = 0, $name = "", $id = ''
		$consulta = "SELECT * FROM $tabla WHERE $tabla.id_administrador = {$_COOKIE["id_administrador"]}";
		
		if($name == ""){
			$name = $llave_primaria;
		}
		if($id == ""){
			$id = $llave_primaria;
		}
		
		 
		$select = "<select data-indice='$data_indice'";
		
		$select .= $required ? " required " : " ";
		$select .= $disabled ? " disabled " : " ";
		$select.= "class='form-control' name='$name' id='$id' >";
		if($filtro){
			$select .= "<option value=''>Todos</option>";
		} 
		else{
			$select .= "<option value=''>Seleccione...</option>";
		}
		
		$result = mysqli_query($link, $consulta);
		
		while($fila = mysqli_fetch_assoc($result)){
			$select.="<option value='".$fila[$llave_primaria]."'";
			$select.=$fila[$llave_primaria] == $id_selected ? " selected" : "" ;
			$select.=" >".$fila[$campo_etiqueta] ."</option>";
			
		}
		$select.="</select>";
		
	echo $select;
?>

<tr>
	<td class="w-25"><input class="form-control num_asiento" type="number" readonly name="num_asiento[<?php echo {}?>]"  
	value='${num_asiento}'>
	</td>
	<td>
	<div class="form-group">
	<label><input   required class="tipo_boleto"  type="radio" name="tipo_boleto[${num_asiento}]" data-precio="280" value="Adulto"> Adulto $280</label> <br>
	<label ><input id="niño" required  class="tipo_boleto" type="radio" name="tipo_boleto[${num_asiento}]" data-precio="250" value="Niño"> Niño	 $250</label> <br>
	</div>
	
	</td>
	<td><input name="nombre_pasajero[${num_asiento}]" required class="form-control" ></td>
	<td><input name="precio[${num_asiento}]" class="precio form-control" readonly></td>
	
	</tr>