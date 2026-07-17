<?php 
	
	include('../../../conexi.php');
	include('../../../funciones/generar_select.php');
	include('../../../funciones/dame_permiso.php');
	include('../../../funciones/console_log.php');
	$link = Conectarse();
	$filas = array();
	$respuesta = array();
	$formas_pago = array("Efectivo", "Transferencia", "Boletaje");
	$destinos = array();
	$boletos = array();
	$unidades = array();
	$tabla = array();
	$totales = array();
	$sumatoria = array();
	$tabla_forma_pago = array("Efectivo" => 0,  "Transferencia" => 0, "Boletaje" => 0);
	$tablas_usuario_totales = array();
	
	$totales_forma_pago = array(
	"Efectivo" => array(
	"importe" => 0 , 
	"total_boletos" => 0 , 
	"destinos" => array()
	),
	"Transferencia" => array(
	"importe" => 0,	
	"total_boletos" => 0 , 
	"destinos" => array()
	), 
	"Boletaje" => array(
	"importe" => 0,	
	"total_boletos" => 0 ,
	"destinos" => array()
	)
	);
	
	$consulta = "SELECT
	*
    FROM sencillos_precios
    WHERE  estatus_precio = 'Activo'
    ORDER BY destino
	";
	
	
	
	$result = mysqli_query($link,$consulta);
	if(!$result){
		die("error en $consulta". mysqli_error($link));
	}
	
	while($row = mysqli_fetch_assoc($result)){
		$destinos[] = $row;
	}
	
	$consulta = "SELECT
	num_eco
    FROM unidades
    WHERE  
	estatus_unidades = 'Activo'
    ORDER BY num_eco
	";
	
	
	
	$result = mysqli_query($link,$consulta);
	if(!$result){
		die("error en $consulta". mysqli_error($link));
	}
	
	while($row = mysqli_fetch_assoc($result)){
		$unidades[] = $row["num_eco"];
	}
	
	
	
	$consulta = "SELECT
	id_usuarios,
	nombre_usuarios,
	num_eco,
	destino,
	forma_pago,
	COUNT(*) AS total_boletos,
	SUM(precio) AS importe,
	precio
    FROM sencillos_boletos
	LEFT JOIN usuarios USING(id_usuarios)
    WHERE DATE(fecha_boletos) BETWEEN '{$_GET["fecha_inicial"]}' AND '{$_GET["fecha_final"]}'
	AND estatus_boletos = 'Activo'
    GROUP BY destino, forma_pago, num_eco, id_usuarios
    ORDER BY destino, forma_pago, num_eco, id_usuarios
	";
	
	// echo $consulta;
	
	$result = mysqli_query($link,$consulta);
	if(!$result){
		die("error en $consulta". mysqli_error($link));
	}
	
	while($row = mysqli_fetch_assoc($result)){
		$boletos[] = $row;
	}
	
	
	
	foreach($totales_forma_pago AS $forma_pago => $array_forma_pago){
		
		// echo $forma_pago;
		foreach($destinos AS $destino){
			// $tablas_usuario_totales[$boleto["nombre_usuarios"]]
			$totales_forma_pago[$forma_pago]["destinos"][$destino["destino"]] = array(
			"boletos" => 0,
			"precio" => $destino["precio"],
			"importe" =>0
			
			);
			
		}
	}
	
	// echo "<h4>totales_forma_pago</h4>";
	// echo "<pre>";
	// print_R($totales_forma_pago );
	// echo "</pre>";
	// exit();
	
	
	foreach($unidades as $num_eco){
		$cols= 0;
		foreach($destinos as $destino){
			
			foreach($formas_pago as $forma_pago){
				$cols++;
				$filas_vacias[$num_eco][$destino["destino"]][$forma_pago] = "";
				
			}
		}
	}
	
	
	
	
	// $filas_usuario =  $filas_vacias;
	foreach($boletos as $boleto){
		if(!isset($tablas_usuario[$boleto["nombre_usuarios"]])){
			$tablas_usuario[$boleto["nombre_usuarios"]] = $filas_vacias;
		}
		
		if(!isset($tabla_forma_pago[$boleto["nombre_usuarios"]][$boleto["forma_pago"]])){
			$tabla_forma_pago[$boleto["nombre_usuarios"]][$boleto["forma_pago"]] =  0;
		}
		
		$tablas_usuario[$boleto["nombre_usuarios"]][$boleto["num_eco"]][$boleto["destino"]][$boleto["forma_pago"]] = $boleto["total_boletos"];
		
		$tabla_forma_pago[$boleto["nombre_usuarios"]][$boleto["forma_pago"]] +=  $boleto["importe"];
		
		
		if(!isset($tablas_usuario_totales[$boleto["nombre_usuarios"]] )){
			$tablas_usuario_totales[$boleto["nombre_usuarios"]] = $totales_forma_pago;
			
		}
		
		$tablas_usuario_totales[$boleto["nombre_usuarios"]][$boleto["forma_pago"]]["importe"] += $boleto["importe"];
		$tablas_usuario_totales[$boleto["nombre_usuarios"]][$boleto["forma_pago"]]["total_boletos"] += $boleto["total_boletos"];
		
		$tablas_usuario_totales[$boleto["nombre_usuarios"]][$boleto["forma_pago"]]["destinos"][$boleto["destino"]]["boletos"]+= $boleto["total_boletos"];
		$tablas_usuario_totales[$boleto["nombre_usuarios"]][$boleto["forma_pago"]]["destinos"][$boleto["destino"]]["importe"]+= $boleto["importe"];
		
	}
	
	/*
		$totales_forma_pago = array(
		"Efectivo" => array(
		"importe" => 0 , 
		"total_boletos" => 0 , 
		"destinos" => array()),
		"Transferencia" => array("importe" => 0,	"total_boletos" => 0 , "destinos" => array()), 
		"Boletaje" => array("importe" => 0),	"total_boletos" => 0 , "destinos" => array());
		
	*/
	
	
	//llena el array de totales en 0 con el total de columnas
	for($index = 0 ; $index < $cols; $index++){
		$totales[$index] = 0;
	}
	
	// echo "<h4>filas</h4>";
	// echo "<pre>";
	// print_R($filas );
	// echo "</pre>";
	
	// echo "<h4>tablas_usuario</h4>";
	// echo "<pre>";
	// print_R($tablas_usuario );
	// echo "</pre>";
	
	
	foreach($tablas_usuario as $usuario => $filas){
	?>  
	<legend><?php echo $usuario?></legend>
	<table class="table table-bordered table-condensed table-hover table-sm">
		<thead>
			<tr>
				
				<th>Unidad</th>
				<th colspan ="<?php echo $cols?>" ></th>
				
				
			</tr>
		</thead>
		<tbody id="tablaboletossencillos">
			<tr>
				<td align="center" >
					
				</td>
				<?php 
					
					foreach($destinos AS $i => $destino){ ?>
					<td align="center" colspan="3" >
						<b><?php echo $destino["destino"];?></b>
					</td>
					
					<?php 
					}
				?>
			</tr>
			<tr>
				<td align="center" >
					
				</td>
				<?php 
					
					foreach($destinos AS $i => $destino){ 
						foreach($formas_pago AS $j => $forma_pago){ 
							
						?>
						<td align="center" >
							<?php echo $forma_pago;?>
						</td>
						
						<?php 
						}
					}
				?>
			</tr>
			<?php 
				// $total = 0;
				// $cant_boletos = 0;
				
				// $columna++;
				foreach($filas AS $num_eco => $arr_destino){
					
					$columna = 0;
					
					
					
					// $cant_boletos++;
					// $total+=  $row["precio"];
					
					
				?>
				<tr>
					<td align="center">
						<?php echo $num_eco; ?>
					</td>
					<?php foreach($arr_destino AS $nombre_destino => $arr_formas_pago){  ?>
						<?php foreach($arr_formas_pago AS $nombre_forma_pago => $cantidad){  
							
							
							$totales[$columna]+= intval($cantidad);
							$columna++;
						?>
						<td align="center">
							
							<?php echo $cantidad; ?>
						</td>
						<?php
						}
						}
					?>
					
				</tr>
				
				
				
				<?php
					
				}
			?>
			
		</tbody>
		<tfoot>
			
			<tr class="text-white bg-secondary">
				
				<td > Totales:</th>
				<?php foreach($totales as $total){ ?>
					<td class="text-center">
						<?php echo $total?>
					</td>
					<?php
					}
				?>
				
				
				
			</tr>
			
			
		</tfoot>
	</table>
	
	
	<div class="row ">
		<div class="col-sm-3">
			<table class="table table-bordered table-condensed table-hover table-sm">
				<thead>
					<tr>
						<th class="text-center" colspan="2" >
							TOTAL VENTA
						</th>
					</tr>
				</thead>
				<tbody >
					
					
					<?php 
						$total = 0;
						// $cant_boletos = 0;
						
						// $columna++;
						foreach($tabla_forma_pago[$usuario] AS $forma_pago => $suma){
							$total += $suma;
						?>
						<tr>
							<td align="left">
								<?php echo $forma_pago; ?>
							</td>
							
							<td align="right">
								$<?php echo number_format($suma); ?>
							</td>
							
						</tr>
						
						<?php
							
						}
					?>
					
				</tbody>
				<tfoot>
					
					<tr class="text-white bg-secondary">
						
						<td > Totales:</td>
						
						<td class="text-right">
							$<?php echo number_format($total); ?>
						</td>
						
						
						
						
					</tr>
					
					
				</tfoot>
			</table>
		</div>
		
		
		
		
		
		<?php 
			// $total = 0;
			
			foreach($tablas_usuario_totales[$usuario] AS $forma_pago => $tabla_destinos_forma_pago){
				// $total += $suma;
			?>
			<div class="col-sm-3">	
				<legend>	<?php echo $forma_pago; ?></legend>
				<table class="table table-bordered table-condensed table-hover table-sm">
					<thead>
						<tr>
							<th class="text-center">Destino</th>
							<th class="text-center">Boletos</th>
							<th class="text-center">Precio</th>
							<th class="text-center">Importe</th>
						</tr>
					</thead>
					<tbody >
						
						
						<?php 
							$total = 0;
							// $cant_boletos = 0;
							
							// $columna++;
							foreach($tabla_destinos_forma_pago["destinos"] AS $destino => $fila_destino){
								// $total += $suma;
							?>
							<tr>
								<td align="left">
									<?php echo $destino; ?>
								</td>
								
								<td align="right"><?php echo number_format($fila_destino["boletos"]); ?></td>
								<td align="right">$<?php echo number_format($fila_destino["precio"]); ?></td>
								<td align="right">$<?php echo number_format($fila_destino["importe"]); ?></td>
								
							</tr>
							
							<?php
								
							}
						?>
						
					</tbody>
					
					<tfoot>
						
						<tr class="text-white bg-secondary">
							
							<td > Totales:</td>
							
							<td class="text-right">
								<?php echo number_format($tabla_destinos_forma_pago["total_boletos"]); ?>
							</td>
							<td > </td>
							<td class="text-right">
								$<?php echo number_format($tabla_destinos_forma_pago["importe"]); ?>
							</td>
						</tr>
						
						
					</tfoot>
				</table>
			</div>
			
			<?php
				
			}
		?>
		
		
		
	</div>
	<?php
	}
?>