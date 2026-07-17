<?php 
	
	include('../../../conexi.php');
	include('../../../funciones/generar_select.php');
	$link = Conectarse();
	$filas = array();
	$respuesta = array();
	$totales = array_fill (  0 ,  1 , 0 ); //Llena el array totales con 10 elementos en 0s
	
	
	
	$consulta = "SELECT * FROM seguro_interno
	LEFT JOIN empresas USING(id_empresas) 
	LEFT JOIN beneficiarios USING(id_beneficiarios) 
	LEFT JOIN usuarios USING(id_usuarios)
	LEFT JOIN unidades USING(id_unidades)
	WHERE usuarios.id_administrador = {$_COOKIE["id_administrador"]}
	";
	
	$consulta.=  " 
	AND  DATE(fecha)
	BETWEEN '{$_GET['fecha_inicial']}' 
	AND '{$_GET['fecha_final']}'"; 
	
	
	if($_GET["id_unidades"] != ""){
		$consulta.=  " AND id_unidades = '{$_GET['id_unidades']}' ";
	}
	$consulta.=  " ORDER BY id_seguro_interno"; 
	
	
	
	$result = mysqli_query($link,$consulta);
	
	if($result){
		
		if( mysqli_num_rows($result) == 0){
			die("<div class='alert alert-danger'>No hay registros</div>");
		}
		
		while($fila = mysqli_fetch_assoc($result)){
			// console_log($fila);
			$filas[] = $fila ;
		}
	?>
	
	<pre hidden>
		Id_empresas <?php echo $_COOKIE["id_empresas"]?>
		Session Id <?php echo session_id()?>
		Sesiion Estatus <?php echo session_status()?>
		Consulta <?php echo $consulta?>
	</pre>
	<table class="table table-bordered table-condensed" id="dataTable" width="100%" cellspacing="0">
		<thead>
			<tr>
				<th></th>
				<th>Folio</th>
				<th>Fecha </th>
				<th>Beneficiario</th>
				<th>Num Eco</th>
				<th>Empresa</th>
				<th>Monto</th>
				<th>Observaciones</th>
				<th>Estatus</th>
				<th>Usuario</th>
			</thead>
			<tbody id="tabla_DB">
				<?php 
					foreach($filas as $index=>$fila){
					?>
					<tr>
						<td class="text-center"> 
							<?php if($fila["estatus"] != 'Cancelado'){?>
								<button class="btn btn-danger cancelar" title="Cancelar" data-id_registro='<?php echo $fila['id_seguro_interno']?>'>
									<i class="fas fa-times"></i>
								</button>
								<button class="btn btn-outline-info imprimir" data-id_registro='<?php echo $fila['id_seguro_interno']?>'>
									<i class="fas fa-print"></i>
								</button>
								<?php
								}
								else{ ?>
									<span class="badge badge-danger small">
									
										<?php echo $fila['estatus']?>
										<?php echo $fila['datos_cancelacion']?>
									</span>
										<?php
									}
							?>
						</td>
						<td><?php echo $fila["id_seguro_interno"]?></td>
						<td><?php echo $fila["fecha"]?></td>
						<td><?php echo $fila["nombre_beneficiarios"]?></td>
						<td><?php echo $fila["num_eco"]?></td>
						<td><?php echo $fila["nombre_empresas"]?></td>
						<td class="text-right">$<?php echo number_format($fila["monto"])?></td>
						<td><?php echo $fila["observaciones"]?></td>
						<td><?php echo $fila["estatus"]?></td>
						<td><?php echo $fila["nombre_usuarios"]?></td>
						
					</tr>
					<?php
						
						if($fila["estatus"] != "Cancelado"){
							$totales[0]+= $fila["monto"];
							
						}
					}
				?>
			</tbody>
			<tfoot>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<?php
						foreach($totales as $i =>$total){
						?>
						<td class="h6 text-right">$<?php echo number_format($total)?></td>
						<?php	
						}
					?>
					<td></td>
					<td></td>
					<td></td>
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