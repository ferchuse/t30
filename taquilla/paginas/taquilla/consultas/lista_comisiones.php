<?php 
	
	include('../../../conexi.php');
	include('../../../funciones/generar_select.php');
	include('../../../funciones/dame_permiso.php');
	$link = Conectarse();
	$fila = array();
	$respuesta = array();
	$categorias = array();
	$filas =  array();
	$totales =  array(0,0,0,0,0);
	
	
	$update_comisiones = "UPDATE empresas SET 
	comision = '{$_GET["comision"]}',
	porc_incentivo = '{$_GET["porc_incentivo"]}',
	limite_incentivo = '{$_GET["limite_incentivo"]}'
	
	WHERE id_empresas =  '{$_GET["id_empresas"]}' 
	
	";
	
	$result = mysqli_query($link,$update_comisiones) or die("Error en $update_comisiones ". mysqli_error($link));
	
	
	$consulta_conductores = "SELECT 
    conductores.*,
    t_viajes.*,
	
    IFNULL(t_gastos_operador.suma_combustible_operador, 0) AS suma_combustible_operador,
    (
	IFNULL(t_viajes.suma_gasolina, 0) +
	IFNULL(t_gastos_operador.suma_combustible_operador, 0)
    ) AS total_combustible,
	
    IFNULL(t_gastos_operador.suma_casetas_operador, 0) AS suma_casetas_operador,
    (
	IFNULL(t_viajes.suma_casetas, 0) +
	IFNULL(t_gastos_operador.suma_casetas_operador, 0)
    ) AS total_casetas
	
	FROM conductores
	
	LEFT JOIN
	(
    SELECT
	b.id_conductores,
	GROUP_CONCAT(DISTINCT b.num_eco ORDER BY b.num_eco SEPARATOR ', ') AS num_ecos,
	COUNT(*) AS viajes,
	
	SUM(b.efectivo + IFNULL(r.recol_efectivo,0)) AS suma_efectivo,
	SUM(b.tarjeta + IFNULL(r.recol_tarjeta,0)) AS suma_tarjeta,
	SUM(b.transferencia + IFNULL(r.recol_transferencia,0)) AS suma_transferencia,
	
	SUM(b.total) AS monto_viajes,
	
	SUM(IFNULL(g.suma_gastos,0)) AS total_gastos,
	SUM(IFNULL(g.suma_gasolina,0)) AS suma_gasolina,
	SUM(IFNULL(g.suma_casetas,0)) AS suma_casetas
	
    FROM boletos b
	
    LEFT JOIN
    (
	SELECT
	id_boletos,
	SUM(CASE WHEN id_cat_gastos = 7 THEN importe ELSE 0 END) AS suma_gasolina,
	SUM(CASE WHEN id_cat_gastos = 17 THEN importe ELSE 0 END) AS suma_casetas,
	SUM(importe) AS suma_gastos
	FROM gastos_corrida
	WHERE estatus_gastos = 'Activo'
	GROUP BY id_boletos
    ) g
    USING(id_boletos)
	
    LEFT JOIN
    (
	SELECT
	id_boletos,
	SUM(CASE WHEN forma_pago='Efectivo' THEN anticipo ELSE 0 END) AS recol_efectivo,
	SUM(CASE WHEN forma_pago='Tarjeta' THEN anticipo ELSE 0 END) AS recol_tarjeta,
	SUM(CASE WHEN forma_pago='Transferencia' THEN anticipo ELSE 0 END) AS recol_transferencia
	FROM recolecciones
	GROUP BY id_boletos
    ) r
    USING(id_boletos)
	
    WHERE
	b.fecha_boletos BETWEEN '{$_GET["fecha_inicial"]}'
	AND '{$_GET["fecha_final"]}'
	AND b.estatus_boletos = 'Activo'
	
    GROUP BY b.id_conductores
	
	) AS t_viajes
	USING(id_conductores)
	
	LEFT JOIN
	(
    SELECT
	id_conductores,
	SUM(CASE WHEN id_cat_gastos = 7 THEN monto_gasto ELSE 0 END) AS suma_combustible_operador,
	SUM(CASE WHEN id_cat_gastos = 17 THEN monto_gasto ELSE 0 END) AS suma_casetas_operador
    FROM gastos_operador
    WHERE
	fecha_gasto BETWEEN '{$_GET["fecha_inicial"]}'
	AND '{$_GET["fecha_final"]}'
    GROUP BY id_conductores
	) AS t_gastos_operador
	USING(id_conductores)
	";
	
	
	$consulta_conductores.=" 
	
	WHERE estatus_conductores = 'Activo'
	
	GROUP BY conductores.id_conductores
	
	ORDER BY num_ecos ASC";
	
	$result_conductores = mysqli_query($link,$consulta_conductores) or die("Error en $consulta_conductores ". mysqli_error($link));
	
	while($row = mysqli_fetch_assoc($result_conductores)){
		$filas[] = $row;
	}
	
	
?>  
<pre hidden  >
	<?php echo $consulta_conductores;?>
</pre>

<table class="table table-bordered table-sm">
	<thead>
		<tr>
			<th>Num Eco</th>
			<th>Operador</th>
			<th>Num Viajes</th>
			<th>Efectivo</th>
			<th>Tarjeta</th>
			<th>Transferencia</th>
			<th>Importe Bruto</th>
			<th>Comisión Tarjeta</th>
			<th>Gasolina</th>
			<th>Casetas</th>
			<th>Total Gastos</th>
			<th>Bruto - Gastos</th>
			<th>Comisión</th>
			
			
			
		</tr>
	</thead>
	<tbody>
		<?php 
			foreach($filas AS $i => $fila){ 
				
				$comision_tarjeta  = $fila["suma_tarjeta"] * .039;
				$total_gastos =   $fila["total_combustible"] + $fila["total_casetas"] +$comision_tarjeta;
				$total =  $fila["monto_viajes"] - $total_gastos;
				
				if($total > $_GET["limite_incentivo"]){
					$comision = $total * $_GET["porc_incentivo"] / 100; 
				}
				else{
					$comision = $_GET["comision"]; 
				}
				
				$totales[0]+=  $fila["viajes"];
				$totales[1]+=  $fila["suma_efectivo"];
				$totales[2]+=  $fila["suma_tarjeta"];
				$totales[3]+=  $fila["suma_transferencia"];
				$totales[4]+=  $fila["monto_viajes"];
				$totales[5]+=  $comision_tarjeta;
				$totales[6]+=  $fila["total_combustible"];
				$totales[7]+=  $fila["total_casetas"];
				$totales[8]+=  $total_gastos;
				$totales[9]+=  $total;
				$totales[10]+=  $comision;
				
			?>
			<tr class="focusable text-right" >
				
				<td class="text-right"><b>
					<?php echo $fila["num_ecos"]?><br>
					<?php echo $fila["economicos"]?>
				</b></td>
				<td class="text-left"><b><?php echo $fila["nombre_conductores"]?></b></td>
				<td ><?php echo $fila["viajes"]?></td>
				<td >$<?php echo number_format($fila["suma_efectivo"])?></td>
				<td >$<?php echo number_format($fila["suma_tarjeta"])?></td>
				<td >$<?php echo number_format($fila["suma_transferencia"])?></td>
				<td >$<?php echo number_format($fila["monto_viajes"])?></td>
				<td >$<?php echo number_format($comision_tarjeta)?></td>
				<td >$<?php echo number_format($fila["total_combustible"])?></td>
				<td >$<?php echo number_format($fila["total_casetas"])?></td>
				<td >$<?php echo number_format($total_gastos)?></td>
				<td >$<?php echo number_format($total)?></td>
				<td >$<?php echo number_format($comision)?></td>
				
			</tr>
			
			<?php
			}
		?>
		
		
	</tbody>
	<tfoot class="bg-dark text-white">
		<tr class="text-right">
			
			<td colspan=""> </td>
			<td colspan=""> TOTALES</td>
			<td><?php echo $totales[0];?></td>
			<td>$<?php echo number_format($totales[1],2);?></td>
			<td>$<?php echo number_format($totales[2],2);?></td>
			<td>$<?php echo number_format($totales[3],2);?></td>
			<td>$<?php echo number_format($totales[4],2);?></td>
			<td>$<?php echo number_format($totales[5],2);?></td>
			<td>$<?php echo number_format($totales[6],2);?></td>
			<td>$<?php echo number_format($totales[7],2);?></td>
			<td>$<?php echo number_format($totales[8],2);?></td>
			<td>$<?php echo number_format($totales[9],2);?></td>
			<td>$<?php echo number_format($totales[10],2);?></td>
		</tr>
	</tfoot>
</table>

