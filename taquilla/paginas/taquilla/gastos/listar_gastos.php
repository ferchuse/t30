<?php 
	
	include('../../../conexi.php');
	include('../../../funciones/dame_permiso.php');
	$link = Conectarse();
	
	$consulta = "SELECT *, gastos_corrida.datos_cancelacion AS motivo 
	FROM gastos_corrida
	
	LEFT JOIN usuarios USING(id_usuarios)
	LEFT JOIN boletos USING(id_boletos)
	LEFT JOIN unidades USING(num_eco)
	LEFT JOIN empresas USING(id_empresas)
	LEFT JOIN cat_gastos USING(id_cat_gastos)
	WHERE 1
	
	AND 
	DATE(fecha_boletos) BETWEEN '{$_GET["fecha_inicial"]}'
	AND '{$_GET["fecha_final"]}'
	";
	
	
	if($_GET["id_usuarios"] != ""){
		$consulta.=" AND gastos_corrida.id_usuarios = '{$_GET["id_usuarios"]}' ";
	}
	if($_GET["recibe"] != ""){
		$consulta.=" AND recibe LIKE '%{$_GET["recibe"]}%' ";
	}
	
	if($_GET["num_eco"] != ""){
		$consulta.=" AND num_eco = '{$_GET["num_eco"]}' ";
	}
	if($_GET["id_empresas"] != ""){
		$consulta.=" AND id_empresas = '{$_GET["id_empresas"]}' ";
	}
	
	
	$consulta.= " ORDER BY id_boletos ";
	$result = mysqli_query($link,$consulta);
	
	if($result){
		$num_registros = mysqli_num_rows($result);
	?>
	<table class="table table-bordered" id="tabla_registros" width="100%" cellspacing="0">
		<thead>
			<tr>
				
				
				<th class="text-center">Acciones</th>
				<th class="text-center">Folio</th>
				<th class="text-center">Boleto</th>
				<th class="text-center">Fecha Viaje</th>
				<th class="text-center">Fecha Captura</th>
				<th class="text-center">Concepto</th>
				<th class="text-center">Num Eco</th>
				<th class="text-center">Recibe</th>
				<th class="text-center">Importe</th>
				<th class="text-center">Usuario</th>
				
			</tr>
		</thead>
		<tbody >
			<?php
				while($fila = mysqli_fetch_assoc($result)){ 
					
					
				?>
				
				<tr>																 
					<td>
						<?php
							if($fila["estatus_gastos"] == "Cancelado"){
								echo "<span class='badge badge-danger'>{$fila["motivo"]}</span>";
							}
							else{
								
								$suma_gastos+= $fila["importe"];
								
								if (in_array(dame_permiso("gastos.php", $link) , array("Supervisor", "Administrador"))){
								?>
								<button class="btn  btn-sm btn-danger cancelar_gasto" title="Cancelar"     data-id_registro='<?php echo $fila["id_gastos"]?>'>
									<i class="fas fa-times"></i>
								</button>
									
								<button class="btn  btn-sm btn-warning btn_editar" title="Editar Gasto"     data-id_registro='<?php echo $fila["id_gastos"]?>'>
									<i class="fas fa-edit"></i>
								</button>
									
								<button class="btn btn-sm btn-info btn_imprimir" title="Reimpresión"     data-id_registro='<?php echo $fila["id_gastos"]?>'>
									<i class="fas fa-print"></i>
								</button>	
								
								
								<?php 	
								}
							}
							
							
						?>
						
						
					</td>
					
					<td><?php echo $fila["id_gastos"];?></td>
					<td><?php echo $fila["id_boletos"];?></td>
					<td><?php echo date("d/m/Y", strtotime($fila["fecha_boletos"]));?></td>
					<td><?php echo date("d/m/Y H:i:s", strtotime($fila["fecha_gastos"]));?></td>
					<td>
						<?php echo $fila["descripcion_gastos"];?>
						<div class="small">
							<?php echo $fila["detalles"];?>
						</div>
					</td>
					<td class="text-right"><?php echo $fila["num_eco"];?></td>
					<td><?php echo $fila["recibe"];?></td>
					<td class="text-right">$<?php echo number_format($fila["importe"]);?></td>
					<td><?php echo $fila["nombre_usuarios"];?></td>
					
					
					
				</tr>
				
				<?php 	
				}
			?>
		</tbody>
		<tfoot>
			<tr>
				<td >
					<?php echo mysqli_num_rows($result);?> Registros.
				</td>
				<td ></td>
				<td ></td>
				<td ></td>
				<td ></td>
				<td ></td>
				<td ></td>
				<td ><B> Total Gastos</b></td>
				<td class="text-right">
					$<?php echo number_format($suma_gastos);?>
				</td>
				<td ></td>
				<td ></td>
			</tr>
		</tfoot>
	</table>
	
	
	<?php
		
		
	}
	else {
		echo "Error en".$consulta. mysqli_error($link);
	}
	
	
?>					