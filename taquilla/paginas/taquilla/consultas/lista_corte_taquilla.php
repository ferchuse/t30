<?php 
	session_start();
	include('../../../conexi.php');
	include('../../../funciones/generar_select.php');
	include('../../../funciones/console_log.php');
	$link = Conectarse();
	$filas = array();
	$totales = [
	"ventas_efectivo" => 0, 
	"ventas_tarjeta" => 0, 
	"ventas_transferencia" => 0, 
	"recol_efectivo" => 0, 
	"recol_tarjeta" => 0, 
	"recol_transferencia" => 0, 
	"abonos"  => 0, 
	"gastos"  => 0, 
	"traspasos_efectivo" => 0, 
	"traspasos_transferencia" => 0, 
	"recibos_salida" => 0, 
	"gran_total" => 0, 
	"saldo_final" => 0, 
	];
	
	$balance_total = 0;
	$abonos = 0;
	$respuesta = array();
	
	
	$consulta = "
	SELECT
	*, 
	COALESCE(importe_abonos, 0) AS abonos
	FROM usuarios
	LEFT JOIN 
	(
	SELECT
	id_usuarios,
	SUM(efectivo) AS total_efectivo,
	SUM(tarjeta) AS total_tarjeta,
	SUM(transferencia) AS total_transferencia,
	COUNT(*) AS boletos_vendidos
	FROM
	boletos
	WHERE
	estatus_boletos  = 'Activo'
	AND fecha_boletos BETWEEN '{$_GET["fecha_inicial"]}' AND '{$_GET["fecha_final"]}' ";
	
	if($_GET["id_usuarios"] != ""){
		
		$consulta .= " AND id_usuarios = '{$_GET["id_usuarios"]}'";
	}
	
	$consulta .= "
	GROUP BY
	id_usuarios
	) AS t_boletos
	USING(id_usuarios)
	
	
	####################### Recolecciones
	LEFT JOIN (
	SELECT 
    id_usuarios,
    SUM(CASE WHEN forma_pago = 'Efectivo' THEN anticipo ELSE 0 END) AS recol_efectivo,
    SUM(CASE WHEN forma_pago = 'Tarjeta' THEN anticipo ELSE 0 END) AS recol_tarjeta,
    SUM(CASE WHEN forma_pago = 'Transferencia' THEN anticipo ELSE 0 END) AS recol_transferencia
	FROM 
    recolecciones
	WHERE 
	DATE(fecha_captura) BETWEEN '{$_GET["fecha_inicial"]}' AND '{$_GET["fecha_final"]}' 
	GROUP BY 
    id_usuarios
	
	) AS t_recolecciones USING (id_usuarios)
	
	
	
	#######################Abonos
	LEFT JOIN (
	SELECT
	id_usuarios,
	SUM(monto) AS importe_abonos
	FROM
	recibos_entradas
	WHERE
	estatus_deposito = 'Activo'
	AND DATE(fecha_deposito) BETWEEN '{$_GET["fecha_inicial"]}' AND '{$_GET["fecha_final"]}' 
	GROUP BY
	id_usuarios
	) AS t_abonos USING (id_usuarios)
	
	
	#######################Gastos
	LEFT JOIN (
	SELECT
	id_usuarios,
	SUM(importe) AS importe_gastos
	FROM
	gastos_corrida
	WHERE
	estatus_gastos = 'Activo'
	AND fecha_gastos BETWEEN '{$_GET["fecha_inicial"]}' AND '{$_GET["fecha_final"]}' ";
	if($_GET["id_usuarios"] != ""){
		$consulta .= " AND id_usuarios = '{$_GET["id_usuarios"]}'";
	}
	$consulta .= "
	GROUP BY
	id_usuarios
	) AS t_gastos USING (id_usuarios)
	
	
	#######################Traspasos Efectivo
	LEFT JOIN (
	SELECT
	id_usuarios,
	SUM(importe_traspaso) AS traspasos_efectivo
	FROM
	traspasos_utilidad
	WHERE
	estatus_traspaso = 'Activo'
	AND forma_pago = 'Efectivo'
	AND DATE(fecha_traspaso) BETWEEN '{$_GET["fecha_inicial"]}' AND '{$_GET["fecha_final"]}' 
	GROUP BY
	id_usuarios
	) AS tras_ef USING (id_usuarios)
	
	
	#######################Traspasos Transferencia
	LEFT JOIN (
	SELECT
	id_usuarios,
	SUM(importe_traspaso) AS traspasos_transferencia
	FROM
	traspasos_utilidad
	WHERE
	estatus_traspaso = 'Activo'
	AND forma_pago = 'Transferencia'
	AND DATE(fecha_traspaso) BETWEEN '{$_GET["fecha_inicial"]}' AND '{$_GET["fecha_final"]}' 
	GROUP BY
	id_usuarios
	) AS t_trasp_tra USING (id_usuarios)
	
	
	####################### Recibos Salida
	LEFT JOIN (
	SELECT
	id_usuarios,
	SUM(monto_reciboSalidas) AS recibos_salida
	FROM
	recibos_salidas
	WHERE
	estatus_reciboSalidas = 'Activo'
	AND DATE(fecha_reciboSalidas) BETWEEN '{$_GET["fecha_inicial"]}' AND '{$_GET["fecha_final"]}' 
	GROUP BY
	id_usuarios
	) AS t_recibos USING (id_usuarios)
	
	
	
	
	WHERE 
	estatus_usuarios = 'Activo'
	AND empresa_asignada = '{$_COOKIE["empresa_asignada"]}'
	";
	
	if($_GET["id_usuarios"] != ""){
		
		$consulta .= " AND id_usuarios = '{$_GET["id_usuarios"]}'";
	}
	
	$consulta .= "
	ORDER BY nombre_usuarios
	
	";
	
	
	
	$result = mysqli_query($link,$consulta);
	if($result){
		
		if( mysqli_num_rows($result) == 0){
			die("<div class='alert alert-danger'>No hay registros</div>");
			
		}
		
		// echo $consulta;
		
	?>  
	<button class="btn btn-info imprimir d-none">
		<i class="fas fa-print"></i> Imprimir
	</button>
	
	
	<table class="table table-bordered table-sm table-hover">
		<thead>
			<tr class="text-center">
				<th>Usuario</th>
				<th>Boletos Vendidos</th>
				<th colspan="3" >Ventas</th>
				<th colspan="3" >Recolecciones</th>
				
				<th>Abonos a Caja</th>
				<th>Gastos</th>
				<th colspan="2" class="text-center">Traspasos</th>
				<th>Recibos Salida</th>
				<th>Saldo Final Efectivo</th>
				<th>Gran Total</th>
				<th>Imprimir</th>
			</tr>
			<tr class="text-center">
				<th></th>
				<th> </th>
				<th>Efectivo</th>
				<th>Tarjeta</th>
				<th>Transferencia</th>
				<th>Efectivo</th>
				<th>Tarjeta</th>
				<th>Transferencia</th>
				<th></th>
				<th></th>
				<th>Efectivo</th>
				<th>Transferencia</th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php 
				
				while($fila = mysqli_fetch_assoc($result)){
					$totales["ventas_efectivo"]+= $fila["total_efectivo"];
					$totales["ventas_tarjeta"]+= $fila["total_tarjeta"];
					$totales["ventas_transferencia"]+= $fila["total_transferencia"];
					$totales["recol_efectivo"]+= $fila["recol_efectivo"];
					$totales["recol_tarjeta"]+= $fila["recol_tarjeta"];
					$totales["recol_transferencia"]+= $fila["recol_transferencia"];
					$totales["gastos"]+= $fila["importe_gastos"];
					$totales["abonos"]+= $fila["importe_abonos"];
					$totales["traspasos_efectivo"]+= $fila["traspasos_efectivo"];
					$totales["traspasos_transferencia"]+= $fila["traspasos_transferencia"];
					$totales["recibos_salida"]+= $fila["recibos_salida"];
					$saldo_final = $fila["total_efectivo"] + $fila["abonos"] + $fila["recol_efectivo"] - $fila["traspasos_efectivo"]- $fila["recibos_salida"] - $fila["importe_gastos"];
					$totales["saldo_final"]+= $saldo_final;
					$gran_total = $saldo_final + $fila["recol_tarjeta"] +$fila["recol_transferencia"] +$fila["total_tarjeta"] + $fila["total_transferencia"] - $fila["traspasos_transferencia"];
					$totales["gran_total"]+= $gran_total;
				?>
				<tr  class="text-right">
					<td class="text-left">
						<?php echo $fila["nombre_usuarios"]?>
					</td>
					<td ><?php echo number_format($fila["boletos_vendidos"]); ?></td>
					<td class="efectivo"><?php echo "$ ".number_format($fila["total_efectivo"],2); ?></td>
					<td class="tarjeta"><?php echo "$ ".number_format($fila["total_tarjeta"],2); ?></td>
					<td class="transferencia"><?php echo "$ ".number_format($fila["total_transferencia"],2); ?></td>
					<td class="efectivo"><?php echo "$ ".number_format($fila["recol_efectivo"],2); ?></td>
					<td class="tarjeta"><?php echo "$ ".number_format($fila["recol_tarjeta"],2); ?></td>
					<td class="transferencia"><?php echo "$ ".number_format($fila["recol_transferencia"],2); ?></td>
					
					<td class="ingreso-efectivo"><?php echo "$ ".number_format($fila["importe_abonos"],2); ?></td>
					<td class="egreso-efectivo"><?php echo "$ ".number_format($fila["importe_gastos"],2); ?></td>
					<td class="egreso-efectivo" ><?php echo "$ ".number_format($fila["traspasos_efectivo"],2); ?></td>
					<td  class="transferencia"><?php echo "$ ".number_format($fila["traspasos_transferencia"],2); ?></td>
					<td  class="egreso-efectivo"><?php echo "$ ".number_format($fila["recibos_salida"],2); ?></td>
					<td><?php echo "$ ".number_format($saldo_final,2); ?></td>
					<td><?php echo "$ ".number_format($gran_total,2); ?></td>
					<td>
						<button  class="btn btn-sm btn-info btn_imprimir" type="button" data-id_usuarios="<?php echo $fila["id_usuarios"];?>" >
							<i class="fas fa-print"></i> 
						</button >
					</td>
				</tr>
				<?php
				}
			?>
		</tbody>
		<tfoot class="bg-secondary text-white">
			<tr>
				<td>
					
				</td>
				<td class="text-right">
					
				</td>
				<?php foreach($totales AS $total){?>
					<td class="text-right">
						<?php echo "$ ".number_format($total,2);  ?>
					</td>
					
				<?php } ?>
				<td>
					
				</td>
			</tr>
		</tfoot>
	</table>
	<?php
		
	}
	
	else {
		echo "Error en ".$consulta.mysqli_Error($link);
		
	}
	
	
?>																																							