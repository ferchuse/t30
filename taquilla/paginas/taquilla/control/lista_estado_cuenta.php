<?php 
	include('../../../conexi.php');
	include('../../../funciones/generar_select.php');
	include('../../../funciones/console_log.php');
	$link = Conectarse();
	$filas = array();
	$respuesta = array();
	
	
	
	$consulta = "SELECT *, SUM(monto_abonogeneral) suma_abonos, 
	SUM(total_recaudado) AS suma_abono_unidades
	FROM unidades 
	LEFT JOIN derroteros USING(id_derroteros)
	LEFT JOIN propietarios USING(id_propietarios)
	LEFT JOIN empresas USING(id_empresas)
	LEFT JOIN abono_general USING(id_unidades)
	LEFT JOIN tarjetas USING(id_unidades)
	LEFT JOIN abonos_unidades USING(tarjeta)
	
	GROUP BY id_unidades
	";
  
	
	$result = mysqli_query($link,$consulta);
	if($result){
		
		if( mysqli_num_rows($result) == 0){
			die("<div class='alert alert-danger'>No hay registros</div>");
			
		}
		
		
		
	?> 
	<legend>Tarjeta</legend> 
	<table class="table table-bordered table-condensed">
		<thead>
			<tr>
				<th>Num Eco</th>
				<th>Empresa</th>
				<th>Titular</th>
				<th>Estatus</th>
				<th>Saldo Anterior</th>
				<th>Ingreso Bruto</th>
				<th>Cargos Admvos</th>
				<th>50% Admon GAAZ</th>
				<th>50% Admon Empresa</th>
				<th>Seguro Interno</th>
				<th>Traspaso Titulares</th>
				<th>Saldo</th>
				<th>Cargos Retenidos</th>
				<th>Saldo Disponible</th>
			</tr>
		</thead>
		<tbody>
			<?php 
				
				while($fila = mysqli_fetch_assoc($result)){
					console_log($fila);
					$filas = $fila ;
					//TODO
					///Tarjeta Cancelada
					///Tarjeta Ya recaudada
					
					$ingresos = $filas["suma_abonos"] + $filas["suma_abono_unidades"];
					$saldo_actual = $filas["saldo_unidades"] + $ingresos - $filas["gasto_administracion"] - $filas["seguro_derroteros"];
				?>
				<tr>
					<td><?php echo $filas["num_eco"]?></td>
					<td><?php echo $filas["nombre_empresas"]?></td>
					<td><?php echo $filas["nombre_propietarios"]?></td>
					<td><?php echo $filas["estatus_unidades"]?></td>
					<td><?php echo $filas["saldo_unidades"]?></td>
					<td><?php echo $ingresos;?></td>
					<td><?php echo $filas["gasto_administracion"]?></td>
					<td><?php echo $filas["gasto_administracion"] *.5?></td>
					<td><?php echo $filas["gasto_administracion"] *.5?></td>
					<td><?php echo $filas["seguro_derroteros"]?></td>
					<td>0</td>
					<td><?php echo $saldo_actual ?></td>
					<td>0</td>
					<td>
						<a href="estado_cuenta_detalle.php?id_unidades=<?php echo $filas["id_unidades"];?>">
							<?php echo $saldo_actual;?>
						</a>
					</td>
					
				</tr>
				
				<?php
					$total_saldo_unidades+= $filas["saldo_unidades"];
					$total_ingresos+= $ingresos;
					$total_cargos+= $filas["gasto_administracion"];
					$total_seguro+= $filas["seguro_derroteros"];
				
				}
			?>
			
			<tr>
				<td colspan="4"> TOTALES</td>
				<td><?php echo number_format($total_saldo_unidades);?></td>
				<td><?php echo number_format($total_ingresos);?></td>
				<td><?php echo number_format($total_cargos);?></td>
				<td><?php echo number_format($ingresos)?></td>
				
			</tr>
		</tbody>
	</table>
	
	<?php
		
	}
	
	else {
		echo "Error en ".$consulta.mysqli_Error($link);
	
	}
	
	
?>