<?php 
	session_start();
	include('../../../conexi.php');
	include('../../../funciones/generar_select.php');
	$link = Conectarse();
	$filas = array();
	$respuesta = array();
	$totales = array_fill (  0 ,  1 , 0 ); //Llena el array totales con 10 elementos en 0s
	
	
	$consulta = "
	SELECT * FROM unidades
	
	
	#######################SALDO ANTERIOR ################
	
	LEFT JOIN (
	SELECT
	num_eco,
	SUM(total)  AS  importe_boletos_anterior
	FROM boletos
	WHERE 
	DATE(fecha_boletos) < '{$_GET["fecha_inicial"]}'
	AND estatus_boletos = 'Activo'
	GROUP BY num_eco
	) as t_boletos_anterior
	USING (num_eco)
	
	LEFT JOIN (
	SELECT
	num_eco,
	SUM(importe)  AS importe_gastos_operativos_anterior
	FROM gastos_corrida
	LEFT JOIN boletos
	USING(id_boletos)
	WHERE 
	DATE(fecha_gastos) < '{$_GET["fecha_inicial"]}'
	AND estatus_gastos= 'Activo'
	GROUP BY num_eco
	) as t_gastos_operativos_anterior
	USING (num_eco)
	
	LEFT JOIN (
	SELECT
	num_eco,
	SUM(monto)  AS abono_caja_anterior
	FROM recibos_entradas
	WHERE 
	DATE(fecha_aplicacion)  < '{$_GET["fecha_inicial"]}'
	AND estatus_deposito = 'Activo'
	GROUP BY num_eco
	) as t_depositos_anterior
	USING (num_eco)
	
	LEFT JOIN (
	SELECT
	num_eco,
	SUM(monto)  AS traspasos_anterior
	FROM traspasos_utilidad
	LEFT JOIN traspasos_utilidad_unidades USING (id_traspaso)
	WHERE 
	DATE(fecha_aplicacion)  < '{$_GET["fecha_inicial"]}'
	AND estatus_traspaso = 'Activo'
	GROUP BY num_eco
	) as t_traspaso_anterior
	USING (num_eco)
	
	LEFT JOIN (
	SELECT
	num_eco,
	SUM(monto)  AS cargos_anterior
	FROM cargos
	WHERE 
	DATE(fecha_cargos) <  '{$_GET["fecha_inicial"]}'
	AND estatus = 'Activo'	
	GROUP BY num_eco
	) as t_cargos_anterior
	USING (num_eco)
	
	LEFT JOIN (
	SELECT
	num_eco,
	SUM(monto)  AS cargos_fijos_anterior
	FROM cargos_fijos
	WHERE 
	DATE(fecha_cargos) <  '{$_GET["fecha_inicial"]}'
	AND estatus = 'Activo'	
	GROUP BY num_eco
	) as t_cargos_fijos_anterior
	USING (num_eco)
	
	LEFT JOIN (
	SELECT
	num_eco,
	SUM(importe)  AS casetas_anterior
	FROM casetas_tag
	LEFT JOIN unidades USING (tag)
	WHERE 
	DATE(fecha_viaje) <  '{$_GET["fecha_inicial"]}'
	GROUP BY num_eco
	) as t_casetas_anterior
	
	USING (num_eco)
	
	
	LEFT JOIN (
	SELECT
	num_eco,
	SUM(tarjeta) * 0.04  AS comision_tarjeta_anterior
	FROM boletos
	WHERE 
	DATE(fecha_boletos) < '{$_GET["fecha_inicial"]}'
	AND DATE(fecha_boletos) > '2023-11-30'
	AND estatus_boletos = 'Activo'
	GROUP BY num_eco
	) as t_comision_tarjeta_anterior
	USING (num_eco)
	
	
	
	
	#######################SALDO NUEVO########################
	
	
	
	LEFT JOIN (
	SELECT
	num_eco,
	SUM(total)  AS importe_boletos 
	FROM boletos
	WHERE 
	DATE(fecha_boletos) BETWEEN '{$_GET["fecha_inicial"]}'
	AND '{$_GET["fecha_final"]}'
	AND estatus_boletos = 'Activo'
	GROUP BY num_eco
	) as t_boletos
	USING (num_eco)
	
	LEFT JOIN (
	SELECT
	num_eco,
	SUM(importe)  AS importe_gastos_operativos
	FROM gastos_corrida
	LEFT JOIN boletos
	USING(id_boletos)
	WHERE 
	DATE(fecha_gastos) BETWEEN '{$_GET["fecha_inicial"]}'
	AND '{$_GET["fecha_final"]}'
	AND estatus_gastos= 'Activo'
	GROUP BY num_eco
	) as t_gastos_operativos
	USING (num_eco)
	
	LEFT JOIN (
	SELECT
	num_eco,
	SUM(monto)  AS abono_caja 
	FROM recibos_entradas
	WHERE 
	DATE(fecha_aplicacion)  BETWEEN '{$_GET["fecha_inicial"]}'
	AND '{$_GET["fecha_final"]}'
	AND estatus_deposito = 'Activo'
	GROUP BY num_eco
	) as t_depositos
	USING (num_eco)
	
	LEFT JOIN (
	SELECT
	num_eco,
	SUM(monto)  AS traspasos
	FROM traspasos_utilidad
	LEFT JOIN traspasos_utilidad_unidades USING (id_traspaso)
	WHERE 
	DATE(fecha_aplicacion)  BETWEEN '{$_GET["fecha_inicial"]}'
	AND '{$_GET["fecha_final"]}'
	AND estatus_traspaso = 'Activo'
	GROUP BY num_eco
	) as t_traspaso
	USING (num_eco)
	
	LEFT JOIN (
	SELECT
	num_eco,
	SUM(monto)  AS cargos 
	FROM cargos
	WHERE 
	DATE(fecha_cargos) BETWEEN  '{$_GET["fecha_inicial"]}'
	AND '{$_GET["fecha_final"]}'
	AND estatus = 'Activo'	
	GROUP BY num_eco
	) as t_cargos
	USING (num_eco)
	
	LEFT JOIN (
	SELECT
	num_eco,
	SUM(monto)  AS cargos_fijos
	FROM cargos_fijos
	WHERE 
	DATE(fecha_cargos) BETWEEN  '{$_GET["fecha_inicial"]}'
	AND '{$_GET["fecha_final"]}'
	AND estatus = 'Activo'	
	GROUP BY num_eco
	) as t_cargos_fijos
	
	USING (num_eco)
	
	LEFT JOIN (
	SELECT
	num_eco,
	SUM(importe)  AS casetas
	FROM casetas_tag
	LEFT JOIN unidades USING (tag)
	WHERE 
	DATE(fecha_viaje) BETWEEN  '{$_GET["fecha_inicial"]}'
	AND '{$_GET["fecha_final"]}'
	GROUP BY num_eco
	) as t_casetas
	
	USING (num_eco)
	
	
	
	LEFT JOIN (
	SELECT
	num_eco,
	
	CASE
	WHEN DATE('{$_GET["fecha_inicial"]}') > '2023-11-30' THEN SUM(tarjeta) * 0.04
	ELSE 0
    END AS comision_tarjeta
	
	
	FROM boletos
	WHERE 
	DATE(fecha_boletos) BETWEEN '{$_GET["fecha_inicial"]}'
	AND '{$_GET["fecha_final"]}'
	AND estatus_boletos = 'Activo'
	GROUP BY num_eco
	) as t_comision_tarjeta
	USING (num_eco)
	
	
	WHERE unidades.id_empresas = {$_COOKIE["empresa_asignada"]}
	"; 
	
	
	
	if($_GET["num_eco"] != ''){
		
		$consulta.=  " AND  num_eco = '{$_GET["num_eco"]}'"; 
	}
	
	$consulta.=  " ORDER BY num_eco"; 
	
	$result = mysqli_query($link,$consulta);
	
	if($result){
		
		if( mysqli_num_rows($result) == 0){
			die("<div class='alert alert-danger'>No hay registros</div>");
		}
		
		while($fila = mysqli_fetch_assoc($result)){
			// console_log($fila);
			$filas[] = $fila ;
		}
		
		//<a href="#" data-num_eco="<?php echo $fila["num_eco"]" > 
		
	?>
	
	<pre hidden >
		
		<?php echo $consulta?>
	</pre>
	<table class="table table-bordered table-condensed" id="dataTable" width="100%" cellspacing="0">
		<thead>
			<tr>
				<th>Num Eco</th>
				<th>Saldo Anterior</th>
				<th>Boletos</th>
				<th>Comision Tarjeta 4%</th>
				<th>Gastos Operativos</th>
				<th>Abonos de Caja</th>
				<th>Traspasos de Utilidad</th>
				<th>Cargos</th>
				<th>Casetas TAG</th>
				<th>Saldo</th>
				
			</thead>
			<tbody id="">
				<?php 
					foreach($filas as $index=>$fila){
						
						$saldo_anterior = $fila["importe_boletos_anterior"] + $fila["abono_caja_anterior"] - $fila["importe_gastos_operativos_anterior"] - $fila["traspasos_anterior"] - $fila["cargos_anterior"] - $fila["cargos_fijos_anterior"] - $fila["casetas_anterior"] - $fila["comision_tarjeta_anterior"];
						
						$total[7]+= $fila["comision_tarjeta"];
						$total[0]+= $fila["importe_boletos"];
						$total[1]+= $fila["importe_gastos_operativos"];
						$total[2]+= $fila["abono_caja"];
						$total[3]+= $fila["traspasos"];
						$total[4]+= $fila["cargos"] + $fila["cargos_fijos"];
						$total[5]+= $fila["casetas"] ;
						$total[6]+=  $fila["importe_boletos"] +  $fila["abono_caja"] - $fila["importe_gastos_operativos"] -  $fila["traspasos"] - $fila["cargos"] - $fila["cargos_fijos"] - $fila["casetas"] -  $fila["comision_tarjeta"];
						
						$saldo_restante=  $saldo_anterior + $fila["importe_boletos"] +  $fila["abono_caja"] - $fila["importe_gastos_operativos"] -  $fila["traspasos"] - $fila["cargos"] - $fila["cargos_fijos"] - $fila["casetas"] -  $fila["comision_tarjeta"];
					?>
					<tr class="text-right focusable" >						
						<td class="text-left"><?php echo $fila["num_eco"]?></td>
						<td > $<?php echo number_format($saldo_anterior,2)?>	</td>
						<td > $<?php echo number_format($fila["importe_boletos"],2)?></td>
						<td > $<?php echo number_format($fila["comision_tarjeta"],2)?></td>
						<td >
							
							$<?php echo number_format($fila["importe_gastos_operativos"],2)?>
							
						</td>
						<td >
							
							$<?php echo number_format($fila["abono_caja"],2)?>
							
							
						</td>
						<td >$<?php echo number_format($fila["traspasos"],2)?></td>
						<td >$<?php echo number_format($fila["cargos"] + $fila["cargos_fijos"] ,2)?></td>
						<td >$<?php echo number_format($fila["casetas"],2)?></td>
						<td >
							
							<a href="estado_cuenta_detalle.php?id_unidades=<?php echo $filas["id_unidades"];?>&num_eco=<?php echo $fila["num_eco"];?>
							&nombre_propietarios=<?php echo $fila["nombre_propietarios"];?>
							&fecha_inicial=<?php echo $_GET["fecha_inicial"];?>
							&fecha_final=<?php echo $_GET["fecha_final"];?>
							">
								$<?php echo number_format($saldo_restante,2);?>
							</a>
						</tr>
						<?php
						}
					?>
				</tbody>
				<tfoot>
					<tr class="bg-secondary text-white">
						<td><?php echo mysqli_num_rows($result);?> Registros</td>
						<td></td>
						<td class="text-right">$<?= number_format($total[0],2);?></td>
						<td class="text-right">$<?= number_format($total[7],2);?></td>
						<td class="text-right">$<?= number_format($total[1],2);?></td>
						<td class="text-right">$<?= number_format($total[2],2);?></td>
						<td class="text-right">$<?= number_format($total[3],2);?></td>
						<td class="text-right">$<?= number_format($total[4],2);?></td>
						<td class="text-right">$<?= number_format($total[5],2);?></td>
						<td class="text-right">$<?= number_format($total[6],2);?></td>
					</tr>
				</tfoot>
			</table>
		</div>
		
		<?php
			
			
		}
		else {
			echo  "Error en ".$consulta.mysqli_Error($link);
		}
		
	?>											