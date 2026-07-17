<?php 
	
	include('../../../conexi.php');
	include('../../../funciones/generar_select.php');
	include('../../../funciones/dame_permiso.php');
	include('../../../funciones/console_log.php');
	$link = Conectarse();
	$filas = array();
	$respuesta = array();
	
	
	
	$consulta = "SELECT * FROM sencillos_boletos 
	LEFT JOIN usuarios USING(id_usuarios)
	WHERE 1
	";
	
	if($_GET["id_boletos"] != ""){
		$consulta.= " AND id_boletos = '{$_GET["id_boletos"]}'";
	}
	
	if($_GET["forma_pago"] != ""){
		$consulta.= " AND forma_pago = '{$_GET["forma_pago"]}'";
	}
	if($_GET["estatus_ponchado"] != ""){
		$consulta.= " AND estatus_ponchado = '{$_GET["estatus_ponchado"]}'";
	}
	if($_GET["folio_recaudacion"] != ""){
		$consulta.= " AND folio_recaudacion {$_GET["folio_recaudacion"]}";
	}
	
	if($_GET["id_usuarios"] != ""){
		$consulta.= " AND id_usuarios = '{$_GET["id_usuarios"]}'";
	}
	if(isset($_GET["fecha_inicial"]) ){
		$consulta.= " AND DATE(fecha_boletos) BETWEEN '{$_GET["fecha_inicial"]}' AND '{$_GET["fecha_final"]}'
	
	";
	}
	
	
	
	
	
	$consulta.= " ORDER BY id_boletos";
	
	$result = mysqli_query($link,$consulta);
	if($result){
		
		if( mysqli_num_rows($result) == 0){
			die("<div class='alert alert-danger'>No hay registros</div>");
			
		}
		
		
		
	?>  
	<table class="table table-bordered table-condensed table-hover table-sm ">
		<thead >
			<tr class="sticky-top">
				
				<th>Folio</th>
				<th>Fecha</th>
				<th>Destino</th>
				<th>Precio</th>
				<th>Forma de Pago</th>
				<th>Usuario</th>
				<th>Estatus</th>
				<th>Ponchado</th>
				<th>Folio Recibo</th>
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody>
			<?php 
				$total = 0;
				while($fila = mysqli_fetch_assoc($result)){
					
					$filas = $fila ;
					
					if($filas["estatus_boletos"] == "Activo"){
						$total+=  $filas["precio"];
					}
					
				?>
				<tr>
					
					
					<td class="text-right"><?php echo $filas["id_boletos"]?></td>
					<td class="text-right"><?php echo date("d/m/Y H:i:s" , strtotime($filas["fecha_boletos"]))?></td>
					<td><?php echo $filas["destino"]?></td>
					<td class="text-right">$<?php echo $filas["precio"]?></td>
					<td><?php echo $filas["forma_pago"]?></td>
					<td><?php echo $filas["nombre_usuarios"]?></td>
					<td><?php echo $filas["estatus_boletos"]?></td>
					<td><?php 
						if($filas["estatus_ponchado"] == "Ponchado" ){
							echo "<span class='badge badge-warning'>".$filas["estatus_ponchado"]."</span><br>";
							
							echo date("d-m-Y H:i:s", strtotime($filas["fecha_ponchado"]))."<br>";
							echo $filas["usuario_ponchado"]."<br>";
							
						}
						elseif($filas["estatus_ponchado"] == "Activo"){
							
							// echo "<span class='badge badge-success'>".$filas["estatus_ponchado"]."</span><br>";
						}
						
						
						
					?>
					
					
					</td>
					<td><?php echo $filas["folio_recaudacion"]?></td>
					<td>
						<?php if($filas["estatus_boletos"] == "Activo" ) {
							if(in_array(dame_permiso("boletos_sencillos.php", $link) , array('Supervisor', "Administrador"))){
								if($filas["folio_recaudacion"] == "" && $filas["estatus_ponchado"] == "Ponchado"){
								?>
								
								<button class="btn btn-sm btn_reset btn-info" title="Reset" data-folio='<?php echo $filas["id_boletos"]?>'>
									<i class="fas fa-redo"></i>
								</button>
								
								<?php 
								}
							?>
							
							<button class="btn btn-sm btn-danger btn_cancelar " title="Cancelar" data-folio='<?php echo $filas["id_boletos"]?>'>
								<i class="fas fa-times"></i>
							</button>
							<?php 
							}
						}
						else {
							
							echo "<span class='badge badge-danger'>Cancelado<br>{$filas["datos_cancelacion"]}</span>";
						}
						?>
						
					</td>
				</tr>
				
				<?php
					
				}
			?>
			
		</tbody>
		<tfoot>
			
			<tr class="text-white bg-secondary">
				
				
				<td></td>
				<td></td>
				<td></td>
				<td class="text-right">$<?php echo number_format($total,2)?></td>
				
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			
			
		</tfoot>
	</table>
	
	<?php
		
	}
	
	else {
		echo "<pre>Error en ".$consulta.mysqli_Error($link)."</pre>";
		
	}
	
	
?>