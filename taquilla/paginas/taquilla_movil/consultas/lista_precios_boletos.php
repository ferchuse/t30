<?php 
	session_start();
	if(count($_COOKIE) == 0){
		die("<div class='alert alert-danger'>Tu Sesión ha caducado, recarga la página.</div>");
	}
	include('../../../conexi.php');
	include('../../../funciones/generar_select.php');
	include('../../../funciones/console_log.php');
	$link = Conectarse();
	$filas = array();
	$respuesta = array();
	
	
	
	$consulta = "SELECT * FROM sencillos_precios 
	
	ORDER BY destino
	";
	
	
	$result = mysqli_query($link,$consulta);
	if($result){
		
		if( mysqli_num_rows($result) == 0){
			die("<div class='alert alert-danger'>No hay registros</div>");
			
		}
		
		
		
	?>  
	<table class="table table-bordered table-condensed">
		<thead>
			<tr>
				
				
				<th>Destino</th>
				<th>Precio</th>
				<th>Tipo Viaje</th>
				<th>Estatus</th>
				
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody>
			<?php 
				
				while($fila = mysqli_fetch_assoc($result)){
					
					$filas = $fila ;
					
				?>
				<tr>
					
					
					<td><?php echo $filas["destino"]?></td>
					<td class="text-right">$<?php echo $filas["precio"]?></td>
					<td class="text"><?php echo $filas["tipo_viaje"]?></td>
					
					<td>
						
						<?php 
							if($fila["estatus_precio"] == "Activo"){
								
								echo "<span class='badge badge-success'>".$fila["estatus_precio"]."</span>";
								
							}
							else{
								echo "<span class='badge badge-danger'>".$fila["estatus_precio"]."</span>";
								
							}
							
							
						?>
						
						
					</td>
					<td>
						<button class="btn btn-warning editar " title="Editar" data-id_registro='<?php echo $filas["id_precio"]?>'>
							<i class="fas fa-edit"></i>
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
		echo "<pre>Error en ".$consulta.mysqli_Error($link)."</pre>";
		
	}
	
	
?>