<?php 
	session_start();
	// if(count($_COOKIE) == 0){
	// die("<div class='alert alert-danger'>Tu Sesión ha caducado, recarga la página.</div>");
	// }
	include('../../../conexi.php');
	include('../../../funciones/generar_select.php');
	include('../../../funciones/console_log.php');
	$link = Conectarse();
	$filas = array();
	$respuesta = array();
	
	
	
	
	$consulta = "SELECT * FROM ordenes_trabajo 
	
	LEFT JOIN conductores USING(id_conductores)
	
	WHERE 1 
	";
	
	
	// if($_GET["id_usuarios"] != ""){
	// $consulta.="AND corridas.id_usuarios = '{$_GET["id_usuarios"]}'";
	// }
	// if($_GET["id_empresas"] != ""){
	// $consulta.="AND corridas.id_empresas = '{$_GET["id_empresas"]}'";
	// }	
	if($_GET["num_eco"] != ""){
		$consulta.="AND num_eco = '{$_GET["num_eco"]}'";
	}
	if($_GET["id_conductores"] != ""){
		$consulta.="AND id_conductores = '{$_GET["id_conductores"]}'";
	}
	
	$consulta.="
	ORDER BY id_ordenes DESC "
	;
	
	
	$result = mysqli_query($link,$consulta);
	if($result){
		
		if( mysqli_num_rows($result) == 0){
			die("<div class='alert alert-danger'>No hay registros 	</div> ");
			
		}
		
		
		
	?> 
	<pre hidden>
		<?php echo $consulta?>
	</pre>
	
	
	<table class="table table-bordered" width="100%" cellspacing="0">
		<thead>
			<tr>
				<th class="text-center">Folio</th>
				<th class="text-center">No.Eco</th>
				<th class="text-center">Operador</th>
				<th class="text-center">Dias de trabajo</th>
				<th class="text-center">Estatus</th>
				<th class="text-center d-print-none"></th>
			</tr>
		</thead>
		<tbody id="">
			<?php 
				
				while($fila = mysqli_fetch_assoc($result)){
					
				?>
				<tr>
					
					<td><?php echo $fila["id_ordenes"]?></td>
					<td><?php echo $fila["num_eco"]?></td>
					<td><?php echo $fila["nombre_conductores"]?></td>
					<td><?php echo $fila["dias"]?></td>
					<td><?php echo $fila["estatus_ordenes"]?></td>
					
					<td class=" d-print-none">
						<a type="button" href="impresion/imprimir_orden_trabajo.php?id_registro=<?= $fila["id_ordenes"]?>" class="btn btn-info btn-sm " title="Imprimir" target="_blank">
							<i class="fas fa-print"></i> 
						</a>
						<button hidden data-id_registro="<?= $fila["id_ordenes"]?>" class="btn btn-warning btn_reporte btn-sm " title="Reporte" >
							<i class="fas fa-exclamation-triangle"></i> Levantar Reporte
						</button>
					</td>
				</tr>
				
				<?php
					
				}
			?>
			
		</tbody>
	</table>
	
	
	<?php
		
	}
	
	else {
		echo "Error en ".$consulta.mysqli_Error($link);
		
	}
	
	
?>							