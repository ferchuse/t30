<?php 
	session_start();
	include('../../../conexi.php');
	include('../../../funciones/generar_select.php');
	include('../../../funciones/console_log.php');
	$link = Conectarse();
	$filas = array();
	$totales = array();
	$balance_total = 0;
	$respuesta = array();
	
	
	$consulta = "
	SELECT
	id_corridas,
	fecha_corridas,
	suma_boletos,
	suma_paquetes,
	suma_equipaje,
	suma_gastos
	FROM
	(
	SELECT
	id_corridas,
	id_usuarios,
	SUM(precio_boletos) AS suma_boletos
	FROM
	boletos
	WHERE
	estatus_boletos <> 'Cancelado'
	AND DATE(fecha_boletos) = '{$_GET["fecha_inicial"]}'
	AND id_usuarios = '{$_GET["id_usuarios"]}'
	GROUP BY
	id_corridas
	) AS t_boletos 
	
	LEFT JOIN (
	SELECT
	id_corridas,
	id_usuarios,
	SUM(costo) AS suma_paquetes
	FROM
	paquetes
	WHERE
	estatus_paquetes <> 'Cancelado'
	AND DATE(fecha_paquetes) = '{$_GET["fecha_inicial"]}'
	AND id_usuarios = '{$_GET["id_usuarios"]}'
	GROUP BY
	id_corridas
	) AS t_paquetes USING (id_corridas)
	
	LEFT JOIN (
	SELECT
	id_corridas,
	id_usuarios,
	SUM(importe) AS suma_gastos
	FROM
	gastos_corrida
	WHERE
	estatus_gastos <> 'Cancelado'
	AND DATE(fecha_gastos) = '{$_GET["fecha_inicial"]}'
	AND id_usuarios = '{$_GET["id_usuarios"]}'
	GROUP BY
	id_corridas
	) AS t_gastos USING (id_corridas)
	
	
	LEFT JOIN (
	SELECT
	id_corridas,
	id_usuarios,
	SUM(importe) AS suma_equipaje
	FROM
	equipaje
	WHERE
	estatus <> 'Cancelado'
	AND DATE(fecha_equipaje) = '{$_GET["fecha_inicial"]}'
	AND id_usuarios = '{$_GET["id_usuarios"]}'
	GROUP BY
	id_corridas
	) AS t_equipaje USING (id_corridas)
	
	LEFT JOIN corridas USING (id_corridas)
	
	WHERE id_corridas IS NOT NULL
	
	ORDER BY id_corridas
	
	";
	
	
	
	$result = mysqli_query($link,$consulta);
	if($result){
		
		if( mysqli_num_rows($result) == 0){
			die("<div class='alert alert-danger'>No hay registros</div>");
			
		}
		
		// echo $consulta;
		
	?>  
	<button class="btn btn-info imprimir">
		<i class="fas fa-print"></i> Imprimir
	</button>
	
	
	<table class="table table-bordered table-condensed">
		<thead>
			<tr>
				<th>Corrida</th>
				
				<th>Detalle</th>
			</tr>
		</thead>
		<tbody>
			<?php 
				
				while($fila = mysqli_fetch_assoc($result)){
					
					$filas = $fila ;
					$totales[0]+= $filas["suma_boletos"];
					$totales[1]+= $filas["suma_paquetes"];
					$totales[2]+= $filas["suma_equipaje"];
					$totales[3]+= $filas["suma_gastos"];
					$balance_usuario  = $filas["suma_boletos"] + $filas["suma_paquetes"]  + $filas["suma_equipaje"] - $filas["suma_gastos"] ;
					$balance_total+= $balance_usuario;
				?>
				<tr>
					<td>
						
						#<?php echo $filas["id_corridas"] == ''? 0 : $filas["id_corridas"] ?> <br>
						<?php echo date("d-m-Y", strtotime($filas["fecha_corridas"]))?>
					</td>
					<td>
						<b>Boletos:</b> 
						<div class="text-right float-right">
							$<?php echo $filas["suma_boletos"]  == '' ? 0 : number_format($filas["suma_boletos"])?>
						</div>
						<br>
						<b>Paqueteria: </b>
						<div class="text-right float-right">
							$ <?php echo $filas["suma_paquetes"]  == '' ? 0 : number_format($filas["suma_paquetes"])?>
						</div>
						<br>
						<b>Equipaje</b>
						<div class="text-right float-right">
							$ <?php echo $filas["suma_equipaje"]  == '' ? 0 : number_format($filas["suma_equipaje"])?>
						</div>
						<br>
						<b>Gastos</b>
						<div class="text-right float-right">
							$ <?php echo $filas["suma_gastos"]  == ''? 0 : number_format($filas["suma_gastos"]) ?>
						</div>
						<br>
						<b>Balance</b>
						<div class="text-right float-right">
							$ <?php echo number_format($balance_usuario); ?>
						</div>
						
					</td>
					
					
				</tr>
				
				<?php
					
					
				}
			?>
			
			
		</tbody>
		
	</table>
	
	<h5>
		<div class="row text-success">
			<div class="col-6">
				Total Boletos: 
			</div>
			<div class="col-6 text-right">
				+$<?php echo number_format($totales[0])?>
			</div>
		</div>
		<div class="row text-success">
			<div class="col-6">
				Total Paqueteria:
			</div>
			<div class="col-6 text-right">
				+ $<?php echo number_format($totales[1])?> <br>
			</div>
		</div>
		
		<div class="row text-success">
			<div class="col-6">
				Total Equipaje:
			</div>
			<div class="col-6 text-right">
				+ $<?php echo number_format($totales[2])?> <br>
			</div>
		</div>
		
		<div class="row text-danger">
			<div class="col-6">
				Total Gastos: 
			</div>
			<div class="col-6 text-right">
				- $<?php echo number_format($totales[3])?> <br><br>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-6">
				Total en Caja:
			</div>
			<div class="col-6 text-right">
				$<?php echo number_format($balance_total)?> <br>
			</div>
		</div>
		
		
		
		
		
		
	</h5>
	<?php
		
	}
	
	else {
		echo "Error en ".$consulta.mysqli_Error($link);
		
	}
	
	
?>																																					