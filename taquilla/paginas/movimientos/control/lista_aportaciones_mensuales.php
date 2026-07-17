<?php 
	
	if(count($_COOKIE) == 0) {
		die("Sesión caducada, recargue para accesar");
		
	}
	/*
		INSERT INTO aportaciones (fecha, id_empresas, aportacion_mensual)
		SELECT '2021-07-01' , id_empresas , 0 FROM empresas
		
	*/
	
	include('../../../conexi.php');
	include('../../../funciones/generar_select.php');
	$link = Conectarse();
	$filas = array();
	$respuesta = array();
	$totales = array_fill (  0 ,  4 , 0 ); //Llena el array totales con 10 elementos en 0s
	
	
	$fecha_from_mes = substr($_GET['mes_cargos'], 0, 4) ."-". substr($_GET['mes_cargos'], 4, 2). "-01";
	
	$consulta = "
	
	
	SELECT
	id_empresas,
	fecha,
	nombre_empresas,
	aportacion_mensual,
	aportacion_mensual -  COALESCE ( total_aportado, 0 ) AS aportacion_pendiente,
	COALESCE(total_aportado , 0) as total_aportado,
	COALESCE(total_servicio , 0) as total_servicio,
	COALESCE(total_prorrata , 0) as total_prorrata
	
	FROM
	aportaciones
	LEFT JOIN empresas USING(id_empresas)
	
	
	LEFT JOIN (
	SELECT
	id_empresas,
	COALESCE ( SUM( monto ), 0 ) AS total_aportado
	FROM
	recibos_entradas
	WHERE estatus_deposito = 'Activo'
	AND id_motivo_entrada = 1
	AND
	
	EXTRACT( YEAR_MONTH FROM fecha_aplicacion ) = '{$_GET['mes_cargos']}'
	
	GROUP BY id_empresas  
	) AS t_total_aportado
	USING(id_empresas)
	
	LEFT JOIN (
	SELECT
	id_empresas,
	COALESCE ( SUM( monto ), 0 ) AS total_servicio
	FROM
	recibos_entradas
	WHERE estatus_deposito = 'Activo'
	AND id_motivo_entrada = 2
	AND
	
	EXTRACT( YEAR_MONTH FROM fecha_aplicacion ) = '{$_GET['mes_cargos']}'
	
	GROUP BY id_empresas  
	) AS t_total_servicio
	USING(id_empresas)
	
	LEFT JOIN (
	SELECT
	id_empresas,
	COALESCE ( SUM( monto ), 0 ) AS total_prorrata
	FROM
	recibos_entradas
	WHERE estatus_deposito = 'Activo'
	AND id_motivo_entrada = 3
	AND
	
	EXTRACT( YEAR_MONTH FROM fecha_aplicacion ) = '{$_GET['mes_cargos']}'
	
	GROUP BY id_empresas  
	) AS t_total_prorrata
	USING(id_empresas)
	
	LEFT JOIN (
	SELECT
	id_empresas,
	COALESCE ( SUM( monto ), 0 ) AS total_allegros
	FROM
	recibos_entradas
	WHERE estatus_deposito = 'Activo'
	AND id_motivo_entrada = 58
	AND
	
	EXTRACT( YEAR_MONTH FROM fecha_aplicacion ) = '{$_GET['mes_cargos']}'
	
	GROUP BY id_empresas  
	) AS t_total_allegros
	USING(id_empresas)
	
	
	WHERE
	EXTRACT( YEAR_MONTH FROM fecha ) = '{$_GET['mes_cargos']}'
	ORDER BY
	id_empresas
	
	
	";
	
	
	
?>


<?php
	$result = mysqli_query($link,$consulta);
	if($result){
		
		if( mysqli_num_rows($result) == 0){
			
			
			die("<div class='alert alert-danger'>No hay registros</div");
			
			
		}
		
		while($fila = mysqli_fetch_assoc($result)){
			// console_log($fila);
			$filas[] = $fila ;
		}
	?>
	
	
	<table class="table table-bordered table-condensed" id="dataTable" width="100%" cellspacing="0">
		<thead>
			<tr>
				
				<th>Empresa</th>
				<th>Aportación Mensual</th>
				<th>Total Aportado</th>
				<th>Total Servicio Pachuca</th>
				<th>Total Prorrata</th>
				<th>Total Allegros</th>
				<th>Aportación Pendiente</th>
			</tr>
		</thead>
		<tbody id="tabla_DB">
			<?php 
				$totales = [0,0,0,0];
				foreach($filas as $index=>$fila){
					$totales[0]+= $fila["aportacion_mensual"];
					$totales[1]+= $fila["total_aportado"];
					$totales[2]+= $fila["aportacion_pendiente"];
					$totales[3]+= $fila["total_servicio"];
					$totales[4]+= $fila["total_prorrata"];
					$totales[5]+= $fila["total_allegros"];
					
				?>
				<tr>
					
					<td><?php echo $fila["nombre_empresas"]?></td>
					<td>
						<input type="number" onfocus="this.select()" class="form-control aportacion text-right"
						data-id_empresas="<?php echo $fila["id_empresas"]?>"  
						data-fecha="<?php echo $fila["fecha"] ;?>" 
						
						value="<?php echo $fila["aportacion_mensual"]?>">
					</td>
					<td class="text-right">$ <?php echo number_format($fila["total_aportado"],2)?></td>
					<td class="text-right">$ <?php echo number_format($fila["total_servicio"],2)?></td>
					<td class="text-right">$ <?php echo number_format($fila["total_prorrata"],2)?></td>
					<td class="text-right">$ <?php echo number_format($fila["total_allegros"],2)?></td>
					<td class="text-right">$ <?php echo number_format($fila["aportacion_pendiente"],2)?></td>
					
					
					
				</tr>
				<?php
				}
			?>
		</tbody>
		<tfoot class="bg-secondary text-white text-right">
			<tr>
				<td class="text-left" > <?php echo mysqli_num_rows($result)?> Registros</td>
				<td >$<?php echo number_format($totales[0],2)?></td>
				<td >$<?php echo number_format($totales[1],2)?></td>
				<td >$<?php echo number_format($totales[3],2)?></td>
				<td >$<?php echo number_format($totales[4],2)?></td>
				<td >$<?php echo number_format($totales[5],2)?></td>
				<td >$<?php echo number_format($totales[2],2 )?></td>
				
			</tr>
			</tfoot>
		</table>
		
		<?php
			}else{
			echo  "Error en ".$consulta.mysqli_error($link);
		}
	?>				