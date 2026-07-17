<?php 
	session_start();
	include('../../../conexi.php');
	include('../../../funciones/generar_select.php');
	include('../../../funciones/console_log.php');
	$link = Conectarse();
	$filas = array();
	$respuesta = array();
	
?>

<?php
	
	$consulta = "
	##Recaudacion
	SELECT
	id_empresas,
	'Recaudación' AS tipo_ingreso,
	nombre_empresas,
	ingreso
	FROM
	empresas
	LEFT JOIN (
	SELECT
	id_empresas,
	SUM(abono_unidad) AS ingreso
	FROM
	abonos_unidades
	LEFT JOIN tarjetas USING (tarjeta)
	WHERE
	estatus_abonos <> 'Cancelado'
	AND date(fecha_abonos) BETWEEN '{$_GET["fecha_inicial"]}'
	AND '{$_GET["fecha_final"]}'
	GROUP BY
	id_empresas
	) t_abonos USING (id_empresas)
	WHERE
	abonos_unidades = 2
	UNION
	##Abono General
	SELECT
	id_empresas,
	'Abono General' AS tipo_ingreso,
	nombre_empresas,
	ingreso
	FROM
	empresas
	LEFT JOIN (
	SELECT
	id_empresas,
	SUM(monto_abonogeneral) AS ingreso
	FROM
	abono_general
	LEFT JOIN unidades USING (id_unidades)
	WHERE
	estatus_abono <> 'Cancelado'
	AND date(fecha_abonogeneral) BETWEEN '{$_GET["fecha_inicial"]}'
	AND '{$_GET["fecha_final"]}'
	AND id_empresas = 2
	GROUP BY
	id_empresas
	) t_abono_general USING (id_empresas)
	WHERE
	id_empresas = 2
	UNION
	##Mutualidad
	SELECT
	id_empresas,
	'Mutualidad' AS tipo_ingreso,
	nombre_empresas,
	ingreso
	FROM
	empresas
	LEFT JOIN (
	SELECT
	id_empresas,
	SUM(monto_mutualidad) AS ingreso
	FROM
	mutualidad
	WHERE
	estatus_mutualidad <> 'Cancelado'
	AND date(fecha_mutualidad) BETWEEN '{$_GET["fecha_inicial"]}'
	AND '{$_GET["fecha_final"]}'
	GROUP BY
	id_empresas
	) t_mutualidad USING (id_empresas)
	WHERE
	id_empresas = 2;
	
	
	";
	
  // echo $consulta;
	
	$result = mysqli_query($link,$consulta);
	if($result){
		
		if( mysqli_num_rows($result) == 0){
			die("<div class='alert alert-danger'>No hay registros $consulta</div>");
			
		}
		
		
		
	?>  
	
	<table class="table table-bordered table-condensed">
		<thead class="text-center">
			<tr>
				<th colspan="2">Ingresos</th>
				
			</tr>
		</thead>
		<tbody>
			<tr>
				<th colspan="">Concepto</th>
				<th colspan="">Monto</th>
			</tr>
			<?php 
				
				
				while($fila = mysqli_fetch_assoc($result)){
					$total_ingresos+= $fila["ingreso"] ;
				?>
				<tr>
					<td><?php echo $fila["tipo_ingreso"] ?></td>
					<td><?php echo number_format($fila["ingreso"]) ?></td>
				</tr>
				<?php
				}
			?>
		</tbody>
		<tfoot>
			<tr class="h4">
				<td >
					<b> TOTAL</b>
				</td>
				
				<td ><b><?php echo number_format($total_ingresos)?></b></td>
			</tr>
		</tfoot>
	</table>
	<?php
	}
	else{
		echo "<pre>Error:".mysqli_error($link)." $consulta</pre>";
		}
	
?>	

<?php
	$consulta = "
	##Egresos

SELECT
	id_empresas,
	'Recibos de Salida' AS tipo_egreso,
	nombre_empresas,
	egreso
FROM
	empresas
LEFT JOIN (
	SELECT
		id_empresas,
		SUM(monto_reciboSalidas) AS egreso
	FROM
		recibosSalidas
	WHERE
		estatus_reciboSalidas <> 'Cancelado'
	AND date(fecha_reciboSalidas) BETWEEN '{$_GET["fecha_inicial"]}'
	AND '{$_GET["fecha_final"]}'
	GROUP BY
		id_empresas
) t_recibosSalidas USING (id_empresas)
WHERE
	id_empresas = 2
UNION


##Traspaso

SELECT
	id_empresas,
	'Traspaso de Utilidad' AS tipo_egreso,
	nombre_empresas,
	egreso
FROM
	empresas
LEFT JOIN (
	SELECT
		id_empresas,
		SUM(monto_traspaso) AS egreso
	FROM
		traspaso_utilidad
LEFT JOIN usuarios USING(id_usuarios)
	WHERE
		estatus_traspaso <> 'Cancelado'
	AND date(fecha_traspaso) BETWEEN '{$_GET["fecha_inicial"]}'
	AND '{$_GET["fecha_final"]}'
	GROUP BY
		id_empresas
) t_traspasos USING (id_empresas)
WHERE
	id_empresas = 2;
	";
	
  
	
	$result = mysqli_query($link,$consulta);
	if($result){
		
		if( mysqli_num_rows($result) == 0){
			die("<div class='alert alert-danger'>No hay registros $consulta</div>");
			
		}
		
		
		
	?>  
	<table class="table table-bordered table-condensed">
		<thead class="text-center">
			<tr>
				<th colspan="2">Egresos</th>
				
			</tr>
		</thead>
		<tbody>
			<tr>
				<td colspan="">Concepto</td>
				<td colspan="">Monto</td>
			</tr>
			<?php 
				
				
				while($fila = mysqli_fetch_assoc($result)){
					$total_egresos+= $fila["egreso"] ;
				?>
				<tr>
					<td><?php echo $fila["tipo_egreso"] ?></td>
					<td><?php echo number_format($fila["egreso"]) ?></td>
				</tr>
				<?php
				}
			?>
		</tbody>
		<tfoot>
			<tr class="h4">
				<td >
					<b> TOTALES</b>
				</td>
				
				<td ><b><?php echo number_format($total_egresos)?></b></td>
			</tr>
		</tfoot>
	</table>
	<?php
	}
?>	
