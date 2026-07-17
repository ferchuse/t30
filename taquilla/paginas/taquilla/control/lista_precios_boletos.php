<?php 
	session_start();
	
	include('../../../conexi.php');
	include('../../../funciones/generar_select.php');
	include('../../../funciones/console_log.php');
	include('../../../funciones/dame_permiso.php');
	$link = Conectarse();
	$filas = array();
	$respuesta = array();
	
	
	
	$consulta = "SELECT * FROM precios_boletos 
	LEFT JOIN origenes USING(id_origenes) 
	LEFT JOIN (
	SELECT id_origenes AS id_destinos,
	nombre_origenes AS nombre_destinos
	FROM origenes) t_destinos
	USING(id_destinos)
	
	ORDER BY nombre_destinos
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
				<th></th>
				<th>Origen</th>
				<th>Destino</th>
				<th>Tipo de Boleto</th>
				<th>Precio</th>
				<th>Estatus</th>
			</tr>
		</thead>
		<tbody>
			<?php 
				
				while($fila = mysqli_fetch_assoc($result)){
					
					$filas = $fila ;
					
				?>
				<tr>
					<td>
						<?php  if(dame_permiso("precios_boletos.php", $link) != 'Lectura'){ ?>
							
							<button class="btn btn-warning editar " title="Editar" data-id_registro='<?php echo $filas["id_precio"]?>'>
								<i class="fas fa-edit"></i>
							</button>
							<?php
							}
						?>
						
					</td>
					<td><?php echo $filas["nombre_origenes"]?></td>
					<td><?php echo $filas["nombre_destinos"]?></td>
					<td><?php echo $filas["tipo_precio"]?></td>
					<td><?php echo $filas["precio"]?></td>
					<td>
						<?php 
							if($filas["estatus_precio"] == "ACTIVO"){
								
								echo "<span class='badge badge-success'>ACTIVO</span>";
							}
							else{
								echo "<span class='badge badge-danger'>INACTIVO</span>";
							}
						?>
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