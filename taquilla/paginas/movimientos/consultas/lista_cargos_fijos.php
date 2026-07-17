<?php 
	
	
	include('../../../conexi.php');
	include('../../../funciones/generar_select.php');
	$link = Conectarse();
	$filas = array();
	$respuesta = array();
	$totales = array_fill (  0 ,  3 , 0 ); //Llena el array totales con 10 elementos en 0s
	
	
	$fecha_from_mes = substr($_GET['mes_cargos'], 0, 4) ."-". substr($_GET['mes_cargos'], 4, 2). "-01";
	
	$consulta = "
	SELECT
	num_eco,
	t_gasto_admon.fecha_cargos,
	num_eco,
	nombre_empresas,
	estatus_unidades,
	gasto_administrativo,
	seguro,
	acceso
	FROM
	unidades
	LEFT JOIN empresas USING (id_empresas)
	
	
	LEFT JOIN (
	SELECT 
	num_eco,
	fecha_cargos,
	monto AS gasto_administrativo
	FROM
	cargos_fijos
	WHERE
	concepto = 'Gasto Administrativo'
	AND EXTRACT(YEAR_MONTH FROM fecha_cargos) = '{$_GET['mes_cargos']}'
	) AS t_gasto_admon USING (num_eco)
	
	
	LEFT JOIN (
	SELECT
	num_eco,
	fecha_cargos,
	monto AS seguro
	FROM
	cargos_fijos
	WHERE
	concepto = 'Estacionamiento'
	AND EXTRACT(YEAR_MONTH FROM fecha_cargos) =  '{$_GET['mes_cargos']}'
	) AS t_seguro USING (num_eco)
	
	
	LEFT JOIN (
	SELECT
	num_eco,
	fecha_cargos,
	monto AS acceso
	FROM
	cargos_fijos
	WHERE
	concepto = 'Acceso a Zona Federal'
	AND EXTRACT(YEAR_MONTH FROM fecha_cargos) =  '{$_GET['mes_cargos']}'
	) AS t_acceso USING (num_eco)
	
	
	
	WHERE unidades.id_empresas ='{$_COOKIE["empresa_asignada"]}'
	
	
	";
	
	
	
	if($_GET["num_eco"] != ""){
		$consulta.=  " AND num_eco = '{$_GET['num_eco']}' ";
		
	} 
	
	$consulta.=  "  ORDER BY num_eco "; 
	
?>
<pre hidden >
	
	<?php echo $consulta;?>
</pre>
<?php
	$result = mysqli_query($link,$consulta);
	if($result){
		
		if( mysqli_num_rows($result) == 0){
			
			
			die("<div class='alert alert-danger'>No hay registros</div");
			
			
		}
		
		while($fila = mysqli_fetch_assoc($result)){
			
			$filas[] = $fila ;
		}
	?>
	
	
	<table class="table table-bordered table-condensed" id="dataTable" width="100%" cellspacing="0">
		<thead>
			<tr>
				
				<th>Num Eco</th>
				<th>Estatus</th>
				<th>Gasto Administrativo</th>
				<th>Estacionamiento</th>
				<th>Acceso a Zona Federal</th>
				
			</thead>
			<tbody id="tabla_DB">
				<?php 
					foreach($filas as $index=>$fila){
						
						
					?>
					<tr>
						
						<td><?php echo $fila["num_eco"]?></td>
						<td><?php echo $fila["estatus_unidades"]?></td>
						<td>
							<input  type="number" step="any" class="form-control cargo text-right"
							data-num_eco="<?php echo $fila["num_eco"]?>"  
							data-fecha_cargos="<?php echo $fila["fecha_cargos"] == '' ? $fecha_from_mes : $fila["fecha_cargos"];?>" 
							data-concepto="Gasto Administrativo" 
							name="gasto_administrativo[]" 
							value="<?php echo $fila["gasto_administrativo"]?>">
						</td>
						<td>
							<input type="number" step="any" class="form-control cargo text-right"
							data-num_eco="<?php echo $fila["num_eco"]?>"  
							data-fecha_cargos="<?php echo $fila["fecha_cargos"] == '' ? $fecha_from_mes : $fila["fecha_cargos"];?>" 
							data-concepto="Estacionamiento" 
							name="seguro[]" 
							value="<?php echo $fila["seguro"]?>">
						</td>
						<td>
							<input type="number" step="any" class="form-control cargo text-right"
							data-num_eco="<?php echo $fila["num_eco"]?>"  
							data-fecha_cargos="<?php echo $fila["fecha_cargos"] == '' ? $fecha_from_mes : $fila["fecha_cargos"];?>" 
							data-concepto="Acceso a Zona Federal" 
							name="acceso[]" 
							value="<?php echo $fila["acceso"]?>">
						</td>
						
						
					</tr>
					<?php
					}
				?>
			</tbody>
			<tfoot>
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