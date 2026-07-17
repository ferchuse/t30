<?php 
	session_start();
	include('../../../conexi.php');
	include('../../../funciones/generar_select.php');
	include('../../../funciones/dame_permiso.php');
	$link = Conectarse();
	$filas = array();
	$respuesta = array();
	$totales = array_fill (  0 ,  1 , 0 ); //Llena el array totales con 10 elementos en 0s
	
	
	
	$consulta = "SELECT *, GROUP_CONCAT(num_eco) AS unidades
	FROM traspasos_utilidad
	LEFT JOIN cat_gastos USING(id_cat_gastos)
	LEFT JOIN traspasos_utilidad_unidades USING(id_traspaso)
	
	LEFT JOIN empresas USING(id_empresas) 
	LEFT JOIN beneficiarios USING(id_beneficiarios) 
	LEFT JOIN usuarios USING(id_usuarios)
	WHERE 1
	";
	
	$consulta.=  " 
	AND  DATE(fecha_traspaso)
	BETWEEN '{$_GET['fecha_inicial']}' 
	AND '{$_GET['fecha_final']}'"; 
	
	if($_GET['mes'] != ""){
		
		$consulta.=  " 
		AND 	MONTH(fecha_aplicacion) = '{$_GET["mes"]}'
		AND YEAR(fecha_aplicacion) = '{$_GET["year"]}'"; 
		
	}
	
	
	if($_GET['id_beneficiarios'] != ""){
		$consulta.=  " AND id_beneficiarios =  '{$_GET['id_beneficiarios']}' "; 
	}
	if($_GET['id_empresas'] != ""){
		$consulta.=  " AND id_empresas =  '{$_GET['id_empresas']}' "; 
	}
	if($_GET['num_eco'] != ""){
		$consulta.=  " AND num_eco =  '{$_GET['num_eco']}' "; 
	}
	
	if($_GET["id_usuarios"] != ""){
		$consulta.=  " AND recibos_salidas.id_usuarios = '{$_GET["id_usuarios"]}'"; 
	}
	$consulta.=  " AND traspasos_utilidad.id_empresas = '{$_COOKIE["empresa_asignada"]}'"; 
	
	$consulta.=  " GROUP BY id_traspaso"; 
	$consulta.=  " ORDER BY id_traspaso ASC"; 
	
	
	
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
	
	
	<table class="table table-bordered table-condensed" id="dataTable" width="100%" cellspacing="0">
		<thead>
			<tr>
				<th><input type="checkbox" id="check_all"></th>
				<th></th>
				<th>Folio</th>
				<th>Fecha Creación</th>
				<th>Fecha Aplicación</th>
				<th>Beneficiario</th>
				<th>Num Eco</th>
				<th>Categoria</th>
				<th>Monto</th>
				<th>Forma de Pago</th>
				<th>Observaciones</th>
				<th>Estatus</th>
				<th>Usuario</th>
			</thead>
			<tbody id="tabla_DB">
				<?php 
					foreach($filas as $index=>$fila){
					?>
					<tr>
						<td class="text-center"><input type="checkbox" class="seleccionar" value='<?php echo $fila['id_traspaso']?>'></td>
						<td class="text-center"> 
							<?php if($fila["estatus_traspaso"] != 'Cancelado'){ 
								
								$totales[0]+= $fila["importe_traspaso"];
								if(dame_permiso("traspasos_utilidad.php", $link) == 'Administrador'){ 
								?>
								<button class="btn btn-danger cancelar" title="Cancelar" data-id_registro='<?php echo $fila['id_traspaso']?>'>
									<i class="fas fa-times"></i>
								</button>
								
								<button class="btn btn-warning btn_editar d-none" title="Editar" data-id_registro='<?php echo $fila['id_traspaso']?>'>
									<i class="fas fa-edit"></i>
								</button>
								
								<?php
								}
							?>
							
							<button class="btn btn-outline-info imprimir" data-id_registro='<?php echo $fila['id_traspaso']?>'>
								<i class="fas fa-print"></i>
							</button>
							<?php	
							}
							else{
								
								
								echo "<span class='badge badge-danger'>".$fila["estatus_traspaso"]."<br>".$fila["datos_cancelacion"]."</span>";
							}
							?>
						</td>
						<td><?php echo $fila["id_traspaso"]?></td>
						<td><?php echo date("d-m-Y H:i:s", strtotime($fila["fecha_traspaso"]))?></td>
						<td><?php echo date("d-m-Y", strtotime($fila["fecha_aplicacion"]))?></td>
						<td><?php echo $fila["nombre_beneficiarios"]?></td>
						<td><?php echo $fila["unidades"]?></td>
						<td><?php echo $fila["descripcion_gastos"]?></td>
						<td class="text-right">
							<?php 
								if($fila["estatus_traspaso"] != 'Cancelado'){	
									echo "$".number_format($fila["importe_traspaso"], 2);
								}
							?>
						</td>
						<td><?php echo $fila["forma_pago"]?></td>
						
						
						<td><?php echo $fila["observaciones"]?></td>
						<td>
							
							<?php
								if($fila["estatus_traspaso"] == "Cancelado"){
									
									echo "<span class='badge badge-danger'>".$fila["estatus_traspaso"]."<br>".$fila["datos_cancelacion"]."</span>";
								}
								else{
									
									echo $fila["estatus_traspaso"];
								}
								
								
								
							?>
						</td>
						<td><?php echo $fila["nombre_usuarios"]?></td>
						
					</tr>
					<?php
						
						
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
					<td></td>
					<td></td>
					<?php
						foreach($totales as $i =>$total){
						?>
						<td class="h6 text-right">$<?php echo number_format($total,2)?></td>
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