<?php 
	
	include('../../../conexi.php');
	include('../../../funciones/dame_permiso.php');
	$link = Conectarse();
	
	$fecha_inicio = $_GET["fecha_inicial"];
	$fecha_fin = $_GET["fecha_final"];
	
	$dias_es = [
	'Monday' => 'LUNES',
	'Tuesday' => 'MARTES',
	'Wednesday' => 'MIERCOLES',
	'Thursday' => 'JUEVES',
	'Friday' => 'VIERNES',
	'Saturday' => 'SABADO',
	'Sunday' => 'DOMINGO'
	];
	
	$consulta = "SELECT 
	u.num_eco,
	c.nombre_conductores,
	g.fecha_gasto,
	g.id_cat_gastos,
	SUM(g.monto_gasto) AS total_gasto
	FROM gastos_operador g
	INNER JOIN conductores c 
	ON c.id_conductores = g.id_conductores
	INNER JOIN unidades u 
	ON u.id_unidades = g.id_unidades
	WHERE DATE(fecha_gasto) BETWEEN '{$_GET["fecha_inicial"]}'
	AND '{$_GET["fecha_final"]}'
	AND id_cat_gastos = '7' #Combustible
	
	GROUP BY 
	u.num_eco,
	c.nombre_conductores,
	g.fecha_gasto
	ORDER BY 
	u.num_eco,
	c.nombre_conductores,
	g.fecha_gasto
	
	";
	
	
	if($_GET["id_usuarios"] != ""){
		$consulta.=" AND gastos_operador.id_usuarios = '{$_GET["id_usuarios"]}' ";
	}
	
	
	if($_GET["num_eco"] != ""){
		$consulta.=" AND num_eco = '{$_GET["num_eco"]}' ";
	}
	
	
	
	$result = mysqli_query($link,$consulta);
	
	if(!$result)
	{
		
		echo "Error en".$consulta. mysqli_error($link);
		exit();
	}
	
	
	$num_registros = mysqli_num_rows($result);
	
	
	
	$reporte = [];
	
	
	// Guardar datos
	while($fila = mysqli_fetch_assoc($result)){
		
		$key = $fila['num_eco']."_".$fila['nombre_conductores'];
		
		$reporte[$key]['eco'] = $fila['num_eco'];
		$reporte[$key]['operador'] = $fila['nombre_conductores'];
		
		$reporte[$key]['fechas'][$fila['fecha_gasto']] = $fila['total_gasto'];
		
		if(!isset($reporte[$key]['total'])){
			$reporte[$key]['total'] = 0;
		}
		
		$reporte[$key]['total'] += $fila['total_gasto'];
		
	}
	
	$fechas = [];
	
	$inicio = strtotime($fecha_inicio);
	$fin = strtotime($fecha_fin);
	
	for($i=$inicio; $i<=$fin; $i+=86400){
		$fechas[] = date("Y-m-d", $i);
	}
	
	$totales_dias = [];
	$gran_total = 0;
	
	foreach($fechas as $fecha){
		$totales_dias[$fecha] = 0;
	}
	
	
	$sql_gastos = "
	SELECT 
	g.id_gasto_operador,
	g.monto_gasto,
	g.id_conductores,
	c.nombre_conductores,
	g.id_unidades,
	u.num_eco,
	g.id_cat_gastos,
	g.fecha_captura,
	g.fecha_gasto,
	g.id_usuarios,
	us.nombre_usuarios,
	cat.descripcion_gastos,
	g.observaciones
	FROM gastos_operador g
	LEFT JOIN cat_gastos cat USING(id_cat_gastos)
	LEFT JOIN usuarios us USING(id_usuarios)
	LEFT JOIN conductores c USING(id_conductores)
	LEFT JOIN unidades u USING(id_unidades)
	WHERE g.fecha_gasto BETWEEN '{$fecha_inicio}' AND '{$fecha_fin}'
	ORDER BY g.id_gasto_operador ASC
	";
	
	$result_gastos = mysqli_query($link, $sql_gastos) or die(mysqli_error($link));
	
	$gastos_operador = [];
	$total_gastos_operador = 0;
	
	while($gasto = mysqli_fetch_assoc($result_gastos)){
		$gastos_operador[] = $gasto;
		$total_gastos_operador += $gasto["monto_gasto"];
	}
	
	
	
	
	
	
?>


<ul class="nav nav-tabs" id="tabsReporte" role="tablist">
	<li class="nav-item">
		<a class="nav-link active" id="tab-totales" data-toggle="tab" href="#totales" role="tab">
			Totales
		</a>
	</li>
	
	<li class="nav-item">
		<a class="nav-link" id="tab-gastos" data-toggle="tab" href="#gastos" role="tab">
			Detalle Gastos
		</a>
	</li>
</ul>

<div class="tab-content pt-3" id="tabsReporteContent">
	
	<div class="tab-pane fade show active" id="totales" role="tabpanel">
		<?php 
			echo "<table class='table table-bordered ' id='tabla_registros'>";
			
			echo "<thead><tr>";
			echo "<th>ECO</th>";
			echo "<th>OPERADOR</th>";
			
			foreach($fechas as $fecha){
				
				$dia_ingles = date("l", strtotime($fecha));
				$dia_espanol = $dias_es[$dia_ingles];
				
				echo "<th align='center'>";
				echo date("d-M", strtotime($fecha));
				echo "<br>";
				echo "<small>".$dia_espanol."</small>";
				echo "</th>";
			}
			
			
			echo "<th>TOTAL</th>";
			echo "</tr></thead>";
			
			foreach($reporte as $row){
				
				echo "<tr>";
				
				echo "<td>".$row['eco']."</td>";
				echo "<td>".$row['operador']."</td>";
				
				foreach($fechas as $fecha){
					
					$monto = 0;
					
					if(isset($row['fechas'][$fecha])){
						$monto = $row['fechas'][$fecha];
					}
					
					// Acumular total del día
					$totales_dias[$fecha] += $monto;
					
					echo "<td align='right'>";
					
					if($monto > 0){
						echo "$ ".number_format($monto,2);
						}else{
						echo "-";
					}
					
					echo "</td>";
				}
				
				// Acumular total general
				$gran_total += $row['total'];
				
				echo "<td bgcolor='#d9ead3' class='text-right'>";
				echo "<b>$ ".number_format($row['total'],2)."</b>";
				echo "</td>";
				
				echo "</tr>";
			}
			
			echo "<tfoot><tr style='font-weight:bold; background:#fff2cc;'>";
			
			echo "<td colspan='2' align='center'>TOTAL</td>";
			
			foreach($fechas as $fecha){
				
				echo "<td align='right'>";
				echo "$ ".number_format($totales_dias[$fecha],2);
				echo "</td>";
				
			}
			
			echo "<td class='text-right' bgcolor='#b6d7a8'>";
			echo "$ ".number_format($gran_total,2);
			echo "</td>";
			
			echo "</tr></tfoot>";
			
			echo "</table>";
			
		?>
	</div>
	
	<div class="tab-pane fade" id="gastos" role="tabpanel">
		
		<table  class="table table-bordered table-sm table-striped data_table">
			<thead class="thead-dark">
				<tr>
					<th>Folio</th>
					<th>Fecha Gasto</th>
					<th>Fecha Captura</th>
					<th>Num Eco</th>
					<th>Operador</th>
					<th>Usuario Captura</th>
					<th>Concepto</th>
					<th>Observaciones</th>
					<th>Monto</th>
					<th>Acciones</th>
				</tr>
			</thead>
			
			<tbody>
				<?php foreach($gastos_operador as $gasto){ ?>
					<tr>
						<td><?php echo $gasto["id_gasto_operador"]; ?></td>
						<td><?php echo date("d-m-Y" , strtotime($gasto["fecha_gasto"])); ?></td>
						<td><?php echo date("d-m-Y H:i:s" , strtotime($gasto["fecha_captura"])); ?></td>
						<td><?php echo $gasto["num_eco"]; ?></td>
						<td><?php echo $gasto["nombre_conductores"]; ?></td>
						<td><?php echo $gasto["nombre_usuarios"]; ?></td>
						<td><?php echo $gasto["descripcion_gastos"]; ?></td>
						<td><?php echo $gasto["observaciones"]; ?></td>
						<td class="text-right">
							$<?php echo number_format($gasto["monto_gasto"], 2); ?>
						</td>
						<td class="text-center">
							
							<?php
								if(in_array(dame_permiso("gastos_operador.php", $link), array('Supervisor', "Administrador"))){
								?>
								<a href="editar_gasto_operador.php?id=<?php echo $gasto["id_gasto_operador"]; ?>" 
								class="btn btn-warning btn-sm">
									Editar
								</a>
								<?php
								}
							?>
							<button 
							type="button" 
							class="btn btn-danger btn-sm btn-borrar-gasto d-none"
							data-id="<?php echo $gasto["id_gasto_operador"]; ?>">
								Borrar
							</button>
						</td>
					</tr>
				<?php } ?>
			</tbody>
			
			<tfoot class="bg-dark text-white">
				<tr>
					<td colspan="8" class="text-right">
						<b>TOTAL GASTOS</b>
					</td>
					<td class="text-right">
						<b>$<?php echo number_format($total_gastos_operador, 2); ?></b>
					</td>
					<td></td>
				</tr>
			</tfoot>
		</table>
		
	</div>
	
</div>


