<?php 
	
	include('../../../conexi.php');
	include('../../../funciones/dame_permiso.php');
	
	function dias_restantes($fecha_final) {  
		$fecha_actual = date("Y-m-d");  
		$s = strtotime($fecha_final)-strtotime($fecha_actual);  
		$d = intval($s/86400);  // Segundos en 1 dia redondeado
		$diferencia = $d;  
		return $diferencia;  
	}  
	
	$link = Conectarse();
	
	$consulta = "SELECT * FROM descuentos WHERE 1";
	
	if($_GET["tipo_descuento"] != ""){
		
		$consulta.= " AND tipo_descuento LIKE '%{$_GET["tipo_descuento"]}%' ";
	}
	
	if($_GET["estatus_descuento"] != ""){
		
		$consulta.= " AND estatus_descuento = '{$_GET["estatus_descuento"]}' ";
	}
	
	
	$consulta.= " ORDER BY {$_GET["order_by"]} {$_GET["sort"]}";
	
	$result = mysqli_query($link,$consulta);
	
	if($result){
		$num_registros = mysqli_num_rows($result);
	?>
	<table class="table table-bordered" id="tabla_registros" width="100%" cellspacing="0">
		<thead>
			<tr>
				<th class="text-center">No.</th>
				<th class="text-left">Nombre</th>
				<th class="text-center">Estatus</th>
				<th class="text-center"></th>
			</tr>
		</thead>
		<tbody >
			<?php
				while($fila = mysqli_fetch_assoc($result)){
				
					
				?>
				
				
				<tr>
					<td class="text-center"><?php echo $fila["id_descuento"];?></td>
					<td class="text-left"><?php echo $fila["tipo_descuento"];?></td>
					
					<td class="text-center">
						<?php
							if($fila["estatus_descuento"] == "Activo"){
								echo "<span class='badge badge-success'>{$fila["estatus_descuento"]}</span>";
							}
							else{
								echo "<span class='badge badge-danger'>{$fila["estatus_descuento"]}</span>";
							}
						?>
					</td>
					
					<td class="text-center"  >
						<?php if(dame_permiso("descuentos.php", $link) == 'Supervisor' || dame_permiso("descuentos.php", $link) == 'Escritura' ){ ?>
							<button class="btn btn-outline-warning editar" data-id_descuento='<?php echo $fila["id_descuento"];?>'>
								<i class="fas fa-pencil-alt"></i>
							</button>
							<button class="btn btn-outline-danger eliminar" data-id_descuento='<?php echo $fila["id_descuento"];?>'>
								<i class="fas fa-trash-alt"></i>
							</button>
							<?php 	
							}
						?>
					</td>
					
				</tr>
				
				<?php 	
				}
			?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="7">
					<?php echo mysqli_num_rows($result);?> Registros.
				</td>
			</tr>
		</tfoot>
	</table>
	
	
	<?php
		
		
	}
	else {
		echo "Error en".$consulta. mysqli_error($link);
	}
	
	
?>		