<?php 
	
	include('../../../conexi.php');
	include('../../../funciones/generar_select.php');
	include('../../../funciones/dame_permiso.php');
	$link = Conectarse();
	$fila = array();
	$respuesta = array();
	$categorias = array();
	$filas =  array();
	$totales =  array();
	$total_viajes =  0;
	
	$consulta_cat_gastos = "SELECT * FROM cat_gastos ORDEr BY descripcion_gastos";
	
	
	$result_categorias = mysqli_query($link,$consulta_cat_gastos) or die("Error en $consulta_cat_gastos ". mysqli_error($link));
	
	while($row = mysqli_fetch_assoc($result_categorias)){	
		$cat_gastos[] = $row ;
	}
	
	
	
	
	
	$consulta_gastos = "SELECT *, ";
	foreach($cat_gastos AS $i => $cat_gasto){ 
		
		$consulta_gastos.=" SUM(monto_gasto_$i) AS  monto_gasto_$i,";
	}
	
	$consulta_gastos  = rtrim($consulta_gastos, ",");
	
	$consulta_gastos.=" FROM unidades  
	
	LEFT JOIN 
	(
	SELECT num_eco, COUNT(*) AS viajes
	FROM
	boletos 
	WHERE 
	DATE(fecha_boletos) BETWEEN '{$_GET["fecha_inicial"]}'
	AND '{$_GET["fecha_final"]}'
	AND estatus_boletos = 'Activo'
	GROUP BY num_eco
	
	) AS t_viajes USING (num_eco)";
	
	
	foreach($cat_gastos AS $i => $cat_gasto){
		
		
		$consulta_gastos.="
		LEFT JOIN 
		(
		SELECT num_eco, SUM(importe) AS monto_gasto_$i
		
		FROM gastos_corrida 
		
		LEFT JOIN boletos USING(id_boletos)
		WHERE id_cat_gastos = {$cat_gasto["id_cat_gastos"]}
		AND DATE(fecha_boletos) BETWEEN '{$_GET["fecha_inicial"]}'
		AND '{$_GET["fecha_final"]}'
		AND estatus_boletos = 'Activo'
		AND estatus_gastos = 'Activo'
		
		GROUP BY num_eco
		
		
		UNION
		
		SELECT num_eco, SUM(monto) AS monto_gasto_$i
		
		FROM traspasos_utilidad 
		
		LEFT JOIN traspasos_utilidad_unidades USING(id_traspaso)
		WHERE id_cat_gastos = {$cat_gasto["id_cat_gastos"]}
		AND DATE(fecha_aplicacion) BETWEEN  '{$_GET["fecha_inicial"]}'
		AND '{$_GET["fecha_final"]}'
		AND estatus_traspaso = 'Activo'
		GROUP BY num_eco
		
		) AS t_$i USING (num_eco)
		
		
		";
		
	}
	
	$consulta_gastos.=" 
	
	WHERE estatus_unidades = 'Activo'
	AND id_empresas = '{$_COOKIE["empresa_asignada"]}'
	GROUP BY num_eco
	
	ORDER BY num_eco ASC";
	
	$result_gastos = mysqli_query($link,$consulta_gastos) or die("Error en $consulta_gastos ". mysqli_error($link));
	
	while($row = mysqli_fetch_assoc($result_gastos)){
		$filas[] = $row;
	}
	
	
	
?>  
<pre hidden >
	<?php echo $consulta_gastos;?>
</pre>

<table class="table table-bordered table-sm">
	<thead>
		<tr>
			<th>Unidad</th>
			<th>Viajes</th>
			<?php
				foreach($cat_gastos AS $i => $cat_gasto){ ?>
				<th><?php echo  $cat_gasto["descripcion_gastos"];?></th>
				<?php
				}
			?>
			
			
			
		</tr>
	</thead>
	<tbody>
		<?php 
			foreach($filas AS $fila){ 
				$total_viajes+=  $fila["viajes"];
			?>
			<tr class="focusable text-right" >
				
				<td><b><?php echo $fila["num_eco"]?></b></td>
				<td ><?php echo $fila["viajes"]?></td>
				<?php
					foreach($cat_gastos AS $i => $cat_gasto){ 
						$totales[$i]+= $fila["monto_gasto_$i"];
						
					?>
					<td class="text-right"><?php 
						if($fila["monto_gasto_$i"] == ""){
							
						}
						else{
							echo "$" .number_format($fila["monto_gasto_$i"],2);
						}
						
						
						
					?></td>
					<?php
					}
				?>
				
				
			</tr>
			
			<?php
			}
		?>
		
		
	</tbody>
	<tfoot class="bg-dark text-white">
		<tr class="text-right">
			
			<td colspan=""> TOTALES</td>
			<td><?php echo number_format($total_viajes);?></td>
			<?php
				foreach($totales AS $i => $total){  ?>
				<td>$<?php echo number_format($total,2);?></td>
				<?php
				}
			?>
			
			
			
			
		</tr>
	</tfoot>
</table>

