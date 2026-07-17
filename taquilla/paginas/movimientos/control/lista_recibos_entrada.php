<?php 
	session_start();
	include('../../../conexi.php');
	include('../../../funciones/generar_select.php');
	include('../../../funciones/dame_permiso.php');
	$link = Conectarse();
	$filas = array();
	$respuesta = array();
	$motivos_entrada = array();
	
	
	$consulta_motivos = "SELECT * FROM motivos_entrada";
	
	
	$result_motivos = mysqli_query($link,$consulta_motivos) or die(mysqli_error($link));
	
	
	while($fila = mysqli_fetch_assoc($result_motivos)){
		
		$totales[$fila["id_motivo_entrada"]] = 0 ;
		$motivos_entrada[] = $fila;
	}
	
	
	$consulta = "SELECT * FROM recibos_entradas
	LEFT JOIN motivos_entrada USING(id_motivo_entrada) 
	LEFT JOIN empresas USING(id_empresas) 
	LEFT JOIN conductores USING(id_conductores) 
	LEFT JOIN beneficiarios USING(id_beneficiarios) 
	LEFT JOIN usuarios USING(id_usuarios)
	WHERE 1
	";
	
	
	
	
	if($_GET['mes'] != ""){
		$consulta.=  " 
		AND 	MONTH(fecha_aplicacion) = '{$_GET["mes"]}'
		AND YEAR(fecha_aplicacion) = '{$_GET["year"]}'"; 
	}
	else{
		$consulta.=  " 
		AND fecha_deposito BETWEEN '{$_GET["fecha_inicial"]}'
		AND  '{$_GET["fecha_final"]}'"; 
		
	}
	
	
	
	if($_GET["id_empresas"] != ""){
		$consulta.=  " AND recibos_entradas.id_empresas = '{$_GET["id_empresas"]}'"; 
	}
	
	if($_GET["empresas_accesibles"] != ""){
		$consulta.=  " AND recibos_entradas.id_empresas IN({$_GET["empresas_accesibles"]})"; 
	}
	
	if($_GET["id_usuarios"] != ""){
		$consulta.=  " AND recibos_entradas.id_usuarios = '{$_GET["id_usuarios"]}'"; 
	}
	
	if($_GET["id_motivo_entrada"] != ""){
		$consulta.=  " AND id_motivo_entrada = '{$_GET["id_motivo_entrada"]}'"; 
	}
	
	
	
	$consulta.=  " ORDER BY id_deposito ASC"; 
	
	
	$result = mysqli_query($link,$consulta);
	
	if($result){
		
		if( mysqli_num_rows($result) == 0){
			die("<div class='alert alert-danger'>No hay registros</div>");
		}
		
		while($fila = mysqli_fetch_assoc($result)){
			
			$filas[] = $fila ;
		}
	?>
	
	<table class="table table-bordered table-condensed" id="dataTable" width="100%" cellspacing="0">
		<thead>
			<tr>
				<th><input type="checkbox" id="check_all"></th>
				<th></th>
				<th>Folio</th>
				<th>Fecha Creacion </th>
				<th>Fecha Aplicación</th>
				<th>Motivo</th>
				<th>Num Eco</th>
				<th>Operador</th>
				<th>Monto</th>
				<th>Estatus</th>
				<th>Usuario</th>
			</thead>
			<tbody id="tabla_DB">
				<?php 
					foreach($filas as $index=>$fila){
					?>
					<tr>
						<td class="text-center"><input type="checkbox" class="seleccionar" value='<?php echo $fila['id_deposito']?>'></td>
						<td class="text-center"> 
							<?php if($fila["estatus_deposito"] != 'Cancelado' ){
								
								$totales[$fila["id_motivo_entrada"]] += $fila["monto"];
								
								// if($fila["id_motivo_entrada"] == "1"){
								// $totales[0]+= $fila["monto"];
								// }
								// elseif($fila["id_motivo_entrada"] == "2"){
								// $totales[1]+= $fila["monto"];
								// }
								// elseif($fila["id_motivo_entrada"] == "2"){
								// $totales[2]+= $fila["monto"];
								// }
								// elseif($fila["id_motivo_entrada"] == "2"){
								// $totales[3]+= $fila["monto"];
								// }
								
								if(dame_permiso("recibos_entrada.php", $link) == 'Administrador'){ 
								?>
								<button class="btn btn-danger cancelar" title="Cancelar" data-id_registro='<?php echo $fila['id_deposito']?>'>
									<i class="fas fa-times"></i>
								</button>
								
								<?php
								}
							?>
							<button class="btn btn-outline-info imprimir" data-id_registro='<?php echo $fila['id_deposito']?>'>
								<i class="fas fa-print"></i>
							</button>
							<?php	
							}
							else{
								echo "<span class='badge badge-danger'>".$fila["estatus_deposito"]."<br>".$fila["datos_cancelacion"]."</span>";
							}
							?>
						</td>
						<td><?php echo $fila["id_deposito"]?></td>
						<td><?php echo date("d-m-Y H:i:s",strtotime($fila["fecha_deposito"]) )?></td>
						<td><?php echo date("d-m-Y",strtotime($fila["fecha_aplicacion"]) )?></td>
						<td><?php echo $fila["motivo"]?></td>
						<td><?php echo $fila["num_eco"]?></td>
						<td><?php echo $fila["nombre_conductores"]?></td>
						<td class="text-right">
							<?php 
								if($fila["estatus_deposito"] != 'Cancelado'){	
									echo "$".number_format($fila["monto"], 2);
								}
							?>
						</td>
						
						<td><?php echo $fila["estatus_deposito"]?></td>
						<td><?php echo $fila["nombre_usuarios"]?></td>
						
					</tr>
					<?php
						
					}
				?>
			</tbody>
			<tfoot class="d-none">
				<?php foreach($motivos_entrada AS $i => $motivo_entrada){?>
				
				<tr class="text-bold">
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><?php echo $motivo_entrada["motivo"]?></td>
					<td class="text-right"><b>$<?php echo number_format($totales[$motivo_entrada["id_motivo_entrada"]])?></b></td>
					<td></td>
					<td></td>
				</tr>
				<?php
					}
					?>
				
				
			</tfoot>
		</table>
	</div>
	
	<?php
		
		
	}
	else {
		echo  "Error en ".$consulta.mysqli_Error($link);
	}
	
?>					