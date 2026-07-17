<?php 
	session_start();
	include('../../../conexi.php');
	$link = Conectarse();
	
	$consulta = "SELECT * FROM empleados_accesos
	LEFT JOIN empleados USING(id_empleado)
	WHERE DATE(fecha_acceso) BETWEEN '{$_GET["fecha_inicial"]}' AND '{$_GET["fecha_final"]}'
	"; 
	if($_GET["tipo_acceso"] != ""){
		$consulta.= " AND tipo_acceso = '{$_GET["tipo_acceso"]}'"; 
	}
	if($_GET["id_empleado"] != ""){
		$consulta.= " AND id_empleado = '{$_GET["id_empleado"]}'"; 
	}
	
	$consulta.= "
	ORDER BY fecha_acceso
	"; 
	
	$result = mysqli_query($link,$consulta);
	
	if(!$result){
		echo "Error en".$consulta. mysqli_error($link);
		exit();
	}
	
?>
<table class="table table-bordered" id="tabla_registros" width="100%" cellspacing="0">
	<thead>
		<tr class="text-center">
			<th >Fecha </th>
			<th >Empleado </th>
			<th >Tipo </th>
		</tr>
	</thead>
	<tbody >
		<?php
			while($fila = mysqli_fetch_assoc($result)){
				
				
			?>
			
			<tr>
				<td><?php echo date("d/m/Y H:i", strtotime($fila["fecha_acceso"]));?></td>
				<td><?php echo $fila["nombre_empleado"];?></td>					
				<td><?php echo $fila["tipo_acceso"];?></td>					
			</tr>
			
			<?php 	
			}
		?>
	</tbody>
</table>
