<?php 
	session_start();
	include('../../../conexi.php');
	include('../../../funciones/generar_select.php');
	include('../../../funciones/console_log.php');
	$link = Conectarse();
	$filas = array();
	$respuesta = array();
	
	
	$consulta = "##Importes por Usuario
	SELECT
	id_usuarios,
	nombre_usuarios,
	suma_boletos,
	suma_paquetes,
	suma_equipaje,
	suma_gastos
	FROM
	usuarios
	
	
	LEFT JOIN (
	SELECT
	id_usuarios,
	SUM(precio_boletos) AS suma_boletos
	FROM
	boletos
	WHERE
	estatus_boletos <> 'Cancelado'
	AND DATE(fecha_boletos) BETWEEN '{$_GET["fecha_inicial"]}'
	AND '{$_GET["fecha_final"]}'
	GROUP BY
	id_usuarios
	) AS t_boletos USING (id_usuarios)
	
	LEFT JOIN (
	SELECT
	id_usuarios,
	SUM(costo) AS suma_paquetes
	FROM
	paquetes
	WHERE
	estatus_paquetes <> 'Cancelado'
	AND DATE(fecha_paquetes) BETWEEN '{$_GET["fecha_inicial"]}'
	AND '{$_GET["fecha_final"]}'
	GROUP BY
	id_usuarios
	) AS t_paquetes USING (id_usuarios)
	
	LEFT JOIN (
	SELECT
	id_usuarios,
	SUM(importe) AS suma_gastos
	FROM
	gastos_corrida
	WHERE
	estatus_gastos <> 'Cancelado'
	AND DATE(fecha_gastos) BETWEEN '{$_GET["fecha_inicial"]}'
	AND '{$_GET["fecha_final"]}'
	GROUP BY
	id_usuarios
	) AS t_gastos USING (id_usuarios)
	
	
	LEFT JOIN (
	SELECT
	id_usuarios,
	SUM(importe) AS suma_equipaje
	FROM
	equipaje
	WHERE
	estatus <> 'Cancelado'
	AND DATE(fecha_equipaje) BETWEEN '{$_GET["fecha_inicial"]}'
	AND '{$_GET["fecha_final"]}'
	GROUP BY
	id_usuarios
	) AS t_equipaje USING (id_usuarios)
	
	
	
	WHERE usuarios.id_administrador = '1'
	ORDER BY nombre_usuarios
	
	";
	
	
	
	$result = mysqli_query($link,$consulta);
	if($result){
		
		if( mysqli_num_rows($result) == 0){
			die("<div class='alert alert-danger'>No hay registros $consulta</div>");
			
		}
		
		
		
	?>  
	
	<table class="table table-bordered table-condensed">
		<thead>
			<tr>
				<th>Usuario</th>
				<th>Venta de Boletos</th>
				<th>Paquetería</th>
				<th>Equipaje Extra</th>
				<th>Gastos</th>
				<th>Total</th>
				<th></th>
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
						<?php echo $filas["nombre_usuarios"] == ''? 0 : $filas["nombre_usuarios"] ?>
					</td>
					<td class="text-right">
						$ <?php echo $filas["suma_boletos"]  == '' ? 0 : number_format($filas["suma_boletos"])?>
					</td>
					<td class="text-right">
						$ <?php echo $filas["suma_paquetes"]  == '' ? 0 : number_format($filas["suma_paquetes"])?>
					</td>
					<td class="text-right">
						$ <?php echo $filas["suma_equipaje"]  == '' ? 0 : number_format($filas["suma_equipaje"])?>
					</td>
					<td class="text-right">
						$ <?php echo $filas["suma_gastos"]  == ''? 0 : number_format($filas["suma_gastos"]) ?>
					</td>
				</a>
				<td class="text-right">
					$ <?php echo number_format($balance_usuario); ?>
				</td>
				
			</tr>
			
			<?php
				
				
			}
		?>
		
		
	</tbody>
	<tfoot>
		<tr class="h5">
			<td ><b> TOTALES<b></td>
				<?php
					$gran_total = 0;
					foreach($totales as $i =>$total){
						
					?>
					<td class="text-right"><b>$ <?php echo number_format($total)?></b></td>
					<?php	
					}
					
					
				?>
				<td class="text-right" ><b>$<?php echo number_format($balance_total)?></b></td>
				
			</tr>
			</tfoot>
		</table>
		
		<pre hidden>
			<?php echo $consulta;?>
		</pre>
		<?php
			
		}
		
		else {
			echo "Error en ".$consulta.mysqli_Error($link);
			
		}
		
		
	?>																												