<?php 
	
	include('../../../conexi.php');
	include('../../../funciones/generar_select.php');
	include('../../../funciones/dame_permiso.php');
	$link = Conectarse();
	$fila = array();
	$efectivo = 0;
	$tarjeta = 0;
	$pasajeros = 0;
	$transferencia = 0;
	$total_gastos = 0;
	$total_neto = 0;
	$importe_total  = 0;
	$boletos_vendidos = 0;
	$fila_gasto  = 0;
	$respuesta = array();
	
	
	
	
	$consulta_boletos = "SELECT * FROM boletos 
	
	LEFT JOIN usuarios ON boletos.id_usuarios = usuarios.id_usuarios
	LEFT JOIN unidades USING (num_eco)
	LEFT JOIN empresas USING (id_empresas)
	LEFT JOIN conductores USING (id_conductores)
	LEFT JOIN cat_terminales USING (id_terminal)
	
	LEFT JOIN (
	SELECT id_boletos, SUM(importe) AS total_gastos
	FROM gastos_corrida
	";
	if($_GET["id_usuarios"] != ""){
		$consulta_boletos.=" WHERE gastos_corrida.id_usuarios = '{$_GET["id_usuarios"]}' ";
	}
	$consulta_boletos.="
	GROUP BY id_boletos
	)  as t_gastos USING (id_boletos)
	WHERE 1
	";
	
	
	
	
	if($_GET["id_boletos"] != ""){
		$consulta_boletos.=" AND id_boletos = '{$_GET["id_boletos"]}' ";
	}
	else{
		$consulta_boletos.="
		AND 
		fecha_boletos BETWEEN '{$_GET["fecha_inicial"]}'
		AND '{$_GET["fecha_final"]}'
		";
	}
	
	
	
	if($_GET["num_eco"] != ""){
		$consulta_boletos.=" AND num_eco = '{$_GET["num_eco"]}' ";
	}
	if($_GET["id_usuarios"] != ""){
		$consulta_boletos.=" AND boletos.id_usuarios = '{$_GET["id_usuarios"]}' ";
	}
	
	if($_GET["id_empresas"] != ""){
		$consulta_boletos.=" AND unidades.id_empresas = '{$_GET["id_empresas"]}' ";
	}
	
	if($_GET["estatus"] != ''){
		$consulta_boletos.= " AND estatus_boletos = '{$_GET["estatus"]}' ";
	}
	if($_GET["facturar"] != ''){
		$consulta_boletos.= " AND facturar = '{$_GET["facturar"]}' ";
	}
	if($_GET["forma_pago"] != ''){
		$consulta_boletos.= " AND forma_pago = '{$_GET["forma_pago"]}' ";
	}
	if($_GET["id_conductores"] != ''){
		$consulta_boletos.= " AND id_conductores = '{$_GET["id_conductores"]}' ";
	}
	if($_GET["taquilla"] != ''){
		$consulta_boletos.= " AND taquilla = '{$_GET["taquilla"]}' ";
	}
	if($_GET["id_terminal"] != ''){
		$consulta_boletos.= " AND id_terminal = '{$_GET["id_terminal"]}' ";
	}
	
	$consulta_boletos.=" ORDER BY boletos.id_boletos";
	
	
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
				<th >Estatus</th>
				<th>Folio</th>
				<th>Fecha de Venta</th>
				<th>Usuario </th>
				<th>Unidad</th>
				<th>Operador</th>
				<th>Destino</th>
				<th>Nombre</th>
				<th>Pasajeros</th>
				<th>Importe Total</th>
				<th>Efectivo</th>
				<th>Tarjeta</th>
				<th>Transferencia</th>
				<th class="d-none">Ingreso</th>
				<th class="d-none">Combustible</th>
				<th class="d-none">Casetas</th>
				<th class="d-none">Comisión</th>
				<th>Total Gastos</th>
				<th>Ingreso Neto</th>
				<th>Facturar</th>
				<th>F. de Pago</th>
				
			</tr>
		</thead>
		<tbody>
			<?php 
				$totales["comision"] = 0;
				$totales["combustible"] = 0;
				$totales["casetas"] = 0;
				
				while($row = mysqli_fetch_assoc($result_boletos)){	
					$fila = $row ;
					$historial = array() ;
					$anticipos = array(
					"recol_efectivo" => 0,
					"recol_tarjeta" => 0,
					"recol_transferencia" => 0
					) ;
					
					if($fila["estatus_boletos"] == 'Activo' ){
						$total_neto+= $fila["total"] - $fila["total_gastos"];
						$total_gastos+= $fila["total_gastos"];
						$boletos_vendidos++;
						$pasajeros+= $fila["pasajeros"];
						
					}
					
					$consulta_gastos ="SELECT *, SUM(importe) as importe_gasto FROM gastos_corrida WHERE id_boletos = '{$fila["id_boletos"]}' ";
					
					if($_GET["id_usuarios"] != ""){
						
						$consulta_gastos .= " AND id_usuarios = {$_COOKIE["id_usuarios"]} ";
					}
					$consulta_gastos .= " GROUP BY id_boletos";
					
					
					$result_gastos = mysqli_query($link,$consulta_gastos) or die(mysqli_error($link));
					
					while($row = mysqli_fetch_assoc($result_gastos)){
						$fila_gasto = $row ;
					}
					
					
					$consulta_historial ="SELECT * FROM boletos_historial WHERE id_boletos = '{$fila["id_boletos"]}' ";
					
					$result_historial = mysqli_query($link,$consulta_historial) or die(mysqli_error($link));
					
					while($row = mysqli_fetch_assoc($result_historial)){
						$historial[] = $row ;
					}
					
					
					$consulta_anticipos ="SELECT 
					id_recoleccion,
					SUM(CASE WHEN forma_pago = 'Efectivo' THEN anticipo ELSE 0 END) AS recol_efectivo,
					SUM(CASE WHEN forma_pago = 'Tarjeta' THEN anticipo ELSE 0 END) AS recol_tarjeta,
					SUM(CASE WHEN forma_pago = 'Transferencia' THEN anticipo ELSE 0 END) AS recol_transferencia
					FROM 
					recolecciones
					WHERE 
					id_boletos = '{$fila["id_boletos"]}' ";
					
					$result_anticipos = mysqli_query($link,$consulta_anticipos) or die(mysqli_error($link));
					
					while($row = mysqli_fetch_assoc($result_anticipos)){
						$anticipos = $row ;
					}
					
					// print_r($anticipos);
					
				?>
				<tr class="focusable">
					<td class="d-print-none">
						<div class="btn-group">
							<?php 
								if($fila["estatus_boletos"] == 'Activo' ){
									if($fila["id_facturas"] == '' ){
									?>
									<a target="" href="../../../facturacion/nueva_factura.php?id_emisores=<?php echo $fila["id_emisores"]?>&folio=<?php echo $fila["id_boletos"]?>&total=<?php echo $fila["total"]?>&fecha=<?php echo date("Y-m-d", strtotime($fila["fecha_boletos"]))?>" class="btn btn-sm btn-primary btn_facturar" title="Facturar"  >
										<i class="fas fa-qrcode"></i>
									</a>
									<?php 
									}
								?>
								<a href="impresion/imprimir_boleto_digital.php?folio=<?php echo $fila["id_boletos"]?>" class="btn btn-sm btn-default" title="Boleto Digital"  >
									<i class="fas fa-ticket-alt"></i>
								</a>
								
								<button class="btn btn-sm btn-warning btn_gasto" title="Agregar Gasto"     data-id_registro='<?php echo $fila["id_boletos"]?>' data-recibe="<?php echo $fila["nombre_conductores"]?>">
									-<i class="fas fa-dollar-sign"></i>
								</button>	
								
								<?php
									if(in_array(dame_permiso("boletos_vendidos.php", $link), array('Administrador'))){
									?>
									<button class="btn btn-sm btn-info btn_imprimir" title="Reimpresión"     data-id_registro='<?php echo $fila["id_boletos"]?>'>
										<i class="fas fa-print"></i>
									</button>
									<button class="btn btn-sm btn-dark btn_imprimir" title="Operador"     data-id_registro='<?php echo $fila["id_boletos"]?>' data-tipo_ticket="operador">
										<i class="fas fa-print"></i>
									</button>
									<button class="btn btn-sm btn-danger cancelar" title="Cancelar"     data-id_registro='<?php echo $fila["id_boletos"]?>'>
										<i class="fas fa-times"></i>
									</button>
									<button class="btn btn-sm btn-secondary btn_editar" title="Editar" data-id_registro='<?php echo $fila["id_boletos"]?>'>
										<i class="fas fa-edit"></i>
									</button>
									<?php
									}
								?>
								<?php
									if(in_array(dame_permiso("boletos_vendidos.php", $link), array('Supervisor'))){
									?>
									
									<button class="btn btn-sm btn-info btn_imprimir" title="Reimpresión"     data-id_registro='<?php echo $fila["id_boletos"]?>'>
										<i class="fas fa-print"></i>
									</button>
									<button class="btn btn-sm btn-secondary btn_editar" title="Editar" data-id_registro='<?php echo $fila["id_boletos"]?>'>
										<i class="fas fa-edit"></i>
									</button>	
									
									
									
									<?php
									}
									
									
									if(in_array(dame_permiso("boletos_vendidos.php", $link), array("Escritura"))){
									?>
									
									
									<button class="btn btn-sm btn-secondary btn_editar" title="Editar" data-id_registro='<?php echo $fila["id_boletos"]?>'>
										<i class="fas fa-edit"></i>
									</button>	
									
									<?php
										
									}
									
								} 
								
								
							?>
							
							<?php
								if($fila["hora_salida"] == ""){
								?>
								<button class="btn btn-sm btn-success btn_salida" data-campo="hora_salida" title="Marcar Salida"     data-folio='<?php echo $fila["id_boletos"]?>'>
									<i class="fas fa-plane-departure"></i>
								</button>
								
								
								<?php
								}elseif($fila["hora_llegada"] == ""){ ?>
								<button class="btn btn-sm btn-secondary btn_salida" data-campo="hora_llegada" title="Marcar Llegada"     data-folio='<?php echo $fila["id_boletos"]?>'>
									<i class="fas fa-plane-arrival"></i>
								</button>
								
								<?php
								}
							?>
							
							<?php
								if(count($historial) >  0){
								?>
								<button class="btn btn-sm btn-info btn_historial"  title="Historial de Cambios"     data-folio='<?php echo $fila["id_boletos"]?>'>
									<i class="fas fa-clock"></i>
								</button>
								
								
								<?php
								}
							?>
						</div>
						<br>
						<?php 
							if($fila["estatus_boletos"] == 'Activo' ){
								
								echo "<span class='badge badge-success'>".$fila["estatus_boletos"]."</span>"; 
							} 
							elseif($fila["estatus_boletos"] == "Cancelación Pendiente"){
								echo "<span class='badge badge-warning'>".$fila["estatus_boletos"]."</span>"; 
							}
							elseif($fila["estatus_boletos"] == 'Cancelado'){
								
								echo "<span class='badge badge-danger'>".$fila["estatus_boletos"]."</span>";
								echo "<small >".$fila["datos_cancelacion"]."</small>";
							}
						?>
					</td>
					<td>
						
						<?php
							echo $fila["id_boletos"]."<br>";
							
							if( $fila["taquilla"] == "NACIONAL"){
								
								echo "<div class='badge badge-success'>NAC</div>";
							}
							else{
								echo "<div class='badge badge-warning'>INT</div>";
							}
							// print_r($anticipos);
							if( $anticipos["id_recoleccion"]){
								
								echo "<br><div class='badge badge-info'>R-{$anticipos["id_recoleccion"]}</div>";
							}
							
						?>
						
					</td>
					<td><?php echo date("d/m/Y H:i:s", strtotime($fila["fecha_boletos"]))?></td>
					<td><?php echo $fila["nombre_usuarios"]?></td>
					
					<td class="text-right"><?php echo $fila["num_eco"]?></td>
					
					<td><?php echo $fila["nombre_conductores"]?></td>
					<td>
						<?php
							//Si el destino es AIFA mostrar origen y Destino
							if($fila["destino"] == "AIFA"){
								
								echo $fila["origen"] ." -> <br>";
								echo $fila["destino"];
							}
							else{
								echo $fila["destino"];
								
							}
							
						?>					
					</td> 
					<td><?php echo $fila["nombre_pasajero"]?></td> 
					<td class="text-right"><?php echo $fila["pasajeros"]?></td>
					
					
					<?php
						if($fila["estatus_boletos"] == "Activo"){
							
							$importe_total+= $fila["total"] ;
							$efectivo+= $fila["efectivo"] + + $anticipos["recol_efectivo"];
							$tarjeta+= $fila["tarjeta"] + + $anticipos["recol_tarjeta"];
							$transferencia+= $fila["transferencia"] +  + $anticipos["recol_transferencia"];
						?>
						<td class="text-right">$<?php echo number_format($fila["total"],2);?></td>
						<td class="text-right">$<?php echo number_format($fila["efectivo"] + $anticipos["recol_efectivo"])?></td>
						<td class="text-right">$<?php echo number_format($fila["tarjeta"] + $anticipos["recol_tarjeta"])?></td>
						<td class="text-right">$<?php echo number_format($fila["transferencia"] + $anticipos["recol_transferencia"])?></td>
						<td class="text-right">$<?php echo number_format($fila["total_gastos"])?></td> 
						<td class="text-right">$<?php echo number_format($fila["total"] - $fila["total_gastos"] )?></td> 
						<?php
							
						}
						else{ 
							echo "<td></td>";
							echo "<td></td>";
							echo "<td></td>";
							echo "<td></td>";
							echo "<td></td>";
							echo "<td></td>";
							
						}
					?>
					
					<td class="text-right d-none">$<?php echo number_format($fila["total"])?></td> 
					
					<td class="text-right d-none">
						<?php
							echo "<pre hidden>".$consulta_gastos."</pre>";
							if($fila_gasto["id_cat_gastos"] == "7"){
								echo "$".number_format($fila_gasto["importe_gasto"]);
								$totales["combustible"]+= $fila_gasto["importe_gasto"];
							}
						?>
					</td> 
					<td class="text-right d-none">
						<?php
							if($fila_gasto["id_cat_gastos"] == "8" || $fila_gasto["id_cat_gastos"] == "13"){
								echo "$".number_format($fila_gasto["importe_gasto"]);
								$totales["casetas"]+= $fila_gasto["importe_gasto"];
							}
						?>
					</td>
					<td class="text-right d-none">
						<?php
							if($fila_gasto["id_cat_gastos"] == "11" ){
								echo "$".number_format($fila_gasto["importe_gasto"]);
								$totales["comision"]+= $fila_gasto["importe_gasto"];
							}
						?>
					</td> 
					
					
					
					<td class="text-center"><?php 
						
						if($fila["facturar"] == "SI" ){
							echo "<span class='badge badge-success'>SI</span>" ;
						}
						else{
							echo "<span class='badge badge-danger'>NO</span>" ;
							
						}
						
						
					?>
					</td> 
					
					<td class="text-center">
						<?php 
							echo $fila["forma_pago"] ."<br>";
							echo $fila["terminal"] ;
						?>
					</td> 
					
					
				</tr>
				
				<?php
					
					
					
				}
			?>
			
			<tr class="text-right bold">
				<td > <?php echo mysqli_num_rows($result_boletos)?> Boletos </td>
				<td colspan="7"> TOTALES</td>
				<td><?php echo ($pasajeros);?></td>
				<td>$<?php echo number_format($importe_total);?></td>
				<td>$<?php echo number_format($efectivo);?></td>
				<td>$<?php echo number_format($tarjeta);?></td>
				<td>$<?php echo number_format($transferencia);?></td>
				<td class="d-none">$<?php echo number_format($totales["combustible"]);?></td>
				<td class="d-none">$<?php echo number_format($totales["casetas"]);?></td>
				<td class="d-none">$<?php echo number_format($totales["comision"]);?></td>
				<td>$<?php echo number_format($total_gastos);?></td>
				<td>$<?php echo number_format($total_neto);?></td>
				
				
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