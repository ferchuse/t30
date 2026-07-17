<?php 
	
	include('../../../conexi.php');
	include('../../../funciones/generar_select.php');
	include('../../../funciones/dame_permiso.php');
	$link = Conectarse();
	$fila = array();
	$respuesta = array();
	$categorias = array();
	$filas =  array();
	
	
	$consulta=" 
	SELECT * FROM boletos_historial 
	LEFT JOIN usuarios USING(id_usuarios)
	WHERE id_boletos = '{$_GET["folio"]}'";
	
	$result = mysqli_query($link,$consulta) or die("Error en $consulta ". mysqli_error($link));
	
	while($row = mysqli_fetch_assoc($result)){
		$filas[] = $row;
	}
	
	
	
?>  


<table class="table table-bordered table-sm">
	<thead>
		<tr>
			<th>Fecha</th>
			<th>Usuario</th>
			<th>Columna</th>
			<th>Valor Antes</th>
			<th>Valor Después</th>
		</tr>
	</thead>
	<tbody>
		<?php 
			foreach($filas AS $i => $fila){ 
				
			
				
			?>
			<tr class="focusable text-right" >
				
				<td class="text-right"><b><?php echo date("d-m-Y H:i:s", strtotime($fila["fecha_historial"]))?></b></td>
				<td class="text-left"><b><?php echo $fila["nombre_usuarios"]?></b></td>
				<td ><?php echo $fila["campo"]?></td>
				<td ><?php echo $fila["valor_anterior"]?></td>
				<td ><?php echo $fila["valor_nuevo"]?></td>
				
			</tr>
			
			<?php
			}
		?>
		
		
	</tbody>
	<tfoot class="bg-dark text-white">
		<tr class="text-right">
			
			<td colspan=""> <?php echo count($filas); ?> Registros</td>
			<td colspan=""> </td>
			<td colspan=""> </td>
			<td colspan=""> </td>
			<td colspan=""> </td>
		
		</tr>
	</tfoot>
</table>

