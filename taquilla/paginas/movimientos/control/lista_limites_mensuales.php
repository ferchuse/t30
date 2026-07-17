<?php 
	session_start();
	
	if(count($_COOKIE) == 0) {
		die("Sesión caducada, recargue para accesar");
		
	}
	
	
	include('../../../conexi.php');
	include('../../../funciones/generar_select.php');
	$link = Conectarse();
	$filas = array();
	$respuesta = array();
	$totales = array_fill (  0 ,  3 , 0 ); //Llena el array totales con 10 elementos en 0s
	
	
	$fecha_from_mes = substr($_GET['mes_cargos'], 0, 4) ."-". substr($_GET['mes_cargos'], 4, 2). "-01";
	
	$consulta = "
	
	
	SELECT
	id_motivosSalida,
	fecha,
	nombre_motivosSalida,
	limite,
	COALESCE(total_gastado , 0) as total_gastado
	FROM
	limites_mensuales
	LEFT JOIN motivos_salida ON motivos_salida.id_motivosSalida = limites_mensuales.id_motivo 
	LEFT JOIN (
	SELECT
	id_motivosSalida,
	COALESCE ( SUM( monto_reciboSalidas ), 0 ) AS total_gastado 
	FROM
	recibos_salidas 
	WHERE
	
	EXTRACT( YEAR_MONTH FROM fecha_aplicacion ) = '{$_GET['mes_cargos']}'
	
	GROUP BY id_motivosSalida  
	) AS t_total_gastado
	USING(id_motivosSalida)
	WHERE
	EXTRACT( YEAR_MONTH FROM fecha ) = '{$_GET['mes_cargos']}'
	ORDER BY
	nombre_motivosSalida
	
	
	";
	
	
	
?>
<pre hidden>
	Consulta <?php echo $consulta;?>
</pre>
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
				
				<th>Motivo</th>
				<th>Limite</th>
				<th>Total Gastado</th>
				
			</thead>
			<tbody id="tabla_DB">
				<?php 
					foreach($filas as $index=>$fila){
						
						
					?>
					<tr>
						
						<td><?php echo $fila["nombre_motivosSalida"]?></td>
						<td>
							<input class="form-control limite text-right"
							data-id_motivo="<?php echo $fila["id_motivosSalida"]?>"  
							data-fecha="<?php echo $fila["fecha"] ;?>" 
							
							value="<?php echo $fila["limite"]?>">
						</td>
						<td class="text-right">$<?php echo number_format($fila["total_gastado"])?></td>
						
						
						
					</tr>
					<?
					}
				?>
			</tbody>
			<tfoot class="bg-secondary text-white">
				<tr><td colspan="6">Registros <?php echo mysqli_num_rows($result)?></td></tr>
			</tfoot>
		</table>
	</div>
	
	<?php
		
		
	}
	else {
		echo  "Error en ".$consulta.mysqli_Error($link);
	}
	
?>		