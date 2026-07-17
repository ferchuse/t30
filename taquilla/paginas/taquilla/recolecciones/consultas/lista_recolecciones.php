<?php 
	
	include('../../../../conexi.php');
	include('../../../../funciones/generar_select.php');
	include('../../../../funciones/dame_permiso.php');
	$link = Conectarse();
	$fila = array();
	$efectivo = 0;
	$tarjeta = 0;
	$anticipo = 0;
	$restante = 0;
	$pasajeros = 0;
	$boletos_vendidos = 0;
	$transferencia = 0;
	$importe_total = 0;
	$total_gastos = 0;
	$respuesta = array();
	
	
	
	
	$consulta_boletos = "SELECT * FROM recolecciones 
	
	LEFT JOIN usuarios USING(id_usuarios)
	LEFT JOIN unidades USING (num_eco)
	LEFT JOIN empresas USING (id_empresas)
	LEFT JOIN conductores USING (id_conductores)
	
	
	WHERE 
	1
	";
	
	// if($_GET["num_eco"] != ""){
	// $consulta_boletos.=" AND num_eco = '{$_GET["num_eco"]}' ";
	// }
	// if($_GET["id_usuarios"] != ""){
	// $consulta_boletos.=" AND boletos.id_usuarios = '{$_GET["id_usuarios"]}' ";
	// }
	
	if($_GET["fecha_inicial"] != ""){
		$consulta_boletos.="AND DATE(fecha_recoleccion) BETWEEN '{$_GET["fecha_inicial"]}'
		AND '{$_GET["fecha_final"]}' ";
	}
	
	
	
	if($_GET["estatus"] != ''){
		$consulta_boletos.= " AND estatus_recoleccion = '{$_GET["estatus"]}' ";
	}
	
	
	if($_GET["tipo_recoleccion"] != ''){
		$consulta_boletos.= " AND tipo_recoleccion = '{$_GET["tipo_recoleccion"]}' ";
	}
	
	
	
	$consulta_boletos.=" ORDER BY fecha_recoleccion";
	
	
	$result_boletos = mysqli_query($link,$consulta_boletos);
	if($result_boletos){
		
		if( mysqli_num_rows($result_boletos) == 0){
			die("<div class='alert alert-danger'>No hay registros</div>");
			
		}
		
	?>  
	<pre hidden >
		<?php echo $consulta_boletos;?>
	</pre>
	
	<table class="table table-bordered table-sm">
		<thead>
			<tr>
				<th>Acciones</th>
				<th>Folio</th>
				<th>Fecha de Recoleccción</th>
				<th>Usuario </th>
				<th>Unidad</th>
				<th>Operador</th>
				<th>Lugar</th>
				<th>Nombre / Celular</th>
				<th>Pasajeros</th>
				<th>F. de Pago</th>
				<th>Importe Total</th>
				<th>Anticipo</th>
				<th>Restante</th>
				
				
				
			</tr>
		</thead>
		<tbody>
			<?php 
				
				
				while($row = mysqli_fetch_assoc($result_boletos)){	
					$fila = $row ;
					if($fila["estatus_recoleccion"] == 'PENDIENTE' ){
						$importe_total+= $fila["total"] ;
						$anticipo+= $fila["anticipo"] ;
						$restante+= $fila["restante"];
						
						$boletos_vendidos++;
						$pasajeros+= $fila["pasajeros"];
						
						
					}
					
					
				?>
				<tr class="focusable">
					<td class="d-print-none text-center">
						
						
						<?php if($fila["estatus_recoleccion"] == 'PENDIENTE' ){
						?>
						
						
						<?php
							if(in_array(dame_permiso("recolecciones.php", $link) , array('Escritura' , "Supervisor", "Adminsitrador")) ){
							?>
							<button class="btn btn-sm btn-primary btn_asignar" title="Asignar Unidad " 
							data-id_registro='<?php echo $fila["id_recoleccion"]?>'>
								<i class="fas fa-car-side"></i>
							</button>	
							<?php
							}
						?>
						<?php
							if(in_array(dame_permiso("recolecciones.php", $link) , array('Administrador')) ){
							?>
							<button class="btn btn-sm btn-danger btn_cancelar" title="Cancelar"     data-id_registro='<?php echo $fila["id_recoleccion"]?>'>
								<i class="fas fa-times"></i>
							</button>
							<button class="btn btn-sm btn-info btn_imprimir d-none" title="Reimpresión"     data-id_registro='<?php echo $fila["id_recoleccion"]?>'>
								<i class="fas fa-print"></i>
							</button>
							<button class="btn btn-sm btn-warning btn_editar" title="Editar" data-id_registro='<?php echo $fila["id_recoleccion"]?>'>
								<i class="fas fa-edit"></i>
							</button>	
							<?php
							}
							
							
							echo "<br><span class='badge badge-warning'>PENDIENTE</span>";
						?>
						
						
						<?php	
						}
						?>
						
						
						
						
						<?php 
							
							if($fila["estatus_recoleccion"] == 'CANCELADA'){
								
								echo "<span class='badge badge-danger'>".$fila["estatus_recoleccion"]."</span><br>";
								echo "<small >".$fila["datos_cancelacion"]."</small>";
							}
							
							
							
							
							if($fila["estatus_recoleccion"] == 'ASIGNADA'){ 
								if(in_array(dame_permiso("recolecciones.php", $link) , array('Escritura' , "Supervisor", "Adminsitrador")) ){
								?>
								
								<button class="btn btn-sm btn-success btn_liquidar" title="Liquidar " 
								data-id_registro='<?php echo $fila["id_recoleccion"]?>'
								data-restante='<?php echo $fila["restante"]?>'
								>
									<i class="fas fa-dollar-sign"></i> 
								</button>	
								
								<?php	
								}
								echo "<br><span class='badge badge-primary'>".$fila["estatus_recoleccion"]."</span>";
								
							}
							
							if($fila["estatus_recoleccion"] == 'FINALIZADA'){
								
								echo "<br><span class='badge badge-success'>".$fila["estatus_recoleccion"]."</span>";
								echo "<small >".$fila["datos_cancelacion"]."</small>";
							}
						?>
						
						<?php 
							if($fila["tipo_recoleccion"] == 'RESERVACIÓN' ){
								echo "<br><span class='badge badge-success'>RESERVACIÓN</span>";
							}
						?>
						
					</td>
					<td>
						<?php echo $fila["id_recoleccion"]; ?>
					</td>
					<td><?php echo date("d/m/Y H:i:s", strtotime($fila["fecha_recoleccion"]))?></td>
					<td><?php echo $fila["nombre_usuarios"]?></td>
					
					<td class="text-right"><?php echo $fila["num_eco"]?></td>
					
					<td><?php echo $fila["nombre_conductores"]?></td>
					<td><?php echo $fila["destino"]?></td> 
					<td><?php echo $fila["nombre_pasajero"]?>
						<br>
						<a href="tel:<?php echo $fila["celular"]?>"><?php echo $fila["celular"]?></a>
						
					</td> 
					<td class="text-right"><?php echo $fila["pasajeros"]?></td>
					<td class="text-right"><?php echo $fila["forma_pago"]?></td>
					<td class="text-right">$<?php echo number_format($fila["total"]);?></td>
					<td class="text-right">$<?php echo number_format($fila["anticipo"]);?></td>
					<td class="text-right">$<?php echo number_format($fila["restante"]);?></td>
					
					
					
					
				</tr>
				
				<?php
					
					
					
				}
			?>
			
			<tr class="text-right">
				<td > <?php echo mysqli_num_rows($result_boletos)?> Registros </td>
				<td colspan="7"> TOTALES</td>
				<td><?php echo ($pasajeros);?></td>
				<td>$<?php echo number_format($importe_total);?></td>
				<td>$<?php echo number_format($anticipo);?></td>
				<td>$<?php echo number_format($restante);?></td>
				
				
			</tr>
		</tbody>
	</table>
	
	<div class="row">
		<div class="col-6">
			
		</div>
	</div>
	
	
	<?php
		
	}
	
	else {
		echo "Error en ".$consulta_boletos.mysqli_Error($link);
		
	}
	
	
?>																	