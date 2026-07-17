<?php 
	session_start();
	include('../../../conexi.php');
	include('../../../funciones/dame_permiso.php');
	$link = Conectarse();
	
	function dias_restantes($fecha_final) {  
		$fecha_actual = date("Y-m-d");  
		$s = strtotime($fecha_final)-strtotime($fecha_actual);  
		$d = intval($s/86400);  
		$diferencia = $d;  
		return $diferencia;  
	}  
	
	$consulta = "SELECT * FROM unidades 
	LEFT JOIN empresas USING(id_empresas) 
	LEFT JOIN propietarios USING(id_propietarios) 
	WHERE 1 
	";
	
	if($_GET["num_eco"] != ''){
		$consulta.= " AND num_eco = '{$_GET["num_eco"]}' ";
	}
	
	if($_GET["id_empresas"] != ''){
		$consulta.= " AND unidades.id_empresas = '{$_GET["id_empresas"]}' ";
	}
	
	if($_GET["empresas_accesibles"] != ""){
		$consulta.=  " AND unidades.id_empresas IN({$_GET["empresas_accesibles"]})"; 
	}
	if($_GET["estatus_unidades"] != ''){
		$consulta.= " AND estatus_unidades = '{$_GET["estatus_unidades"]}' ";
	}
	if($_GET["id_propietarios"] != ''){
		$consulta.= " AND id_propietarios = '{$_GET["id_propietarios"]}' ";
	}
	$consulta.= "ORDER BY num_eco ";
	$result = mysqli_query($link,$consulta);
	
	if($result){
		$num_registros = mysqli_num_rows($result);
		
		
	?>
	<table class="table table-bordered" id="tabla_registros" width="100%" cellspacing="0">
		<thead>
			<tr>
				<th class="text-center"><input id="check_all"  type="checkbox"></th>
				<th class="text-center">Num Eco</th>
				<th class="text-center">Propietario</th>
				<th class="text-center">Tipo Unidad</th>
				<th class="text-center">Vigencia Poliza</th>
				<th class="text-center">Estatus</th>
				<th class="text-center">Acciones</th>
			</tr>
		</thead>
		<tbody >
			<?php
				while($fila = mysqli_fetch_assoc($result)){
					$color = $fila["estatus_unidades"]  == "Activo" ? "badge-success" : "badge-danger";
					
					
					//si es menor a 15 marcar en amarillo, si es menor a 0´poner vencido
					$dias_restantes = dias_restantes($fila["vigencia"]);
					
					
					if($dias_restantes > 15){
							$mensaje_dias = "<span class='badge badge-success'>".$dias_restantes ." días</span>";
						
					}
					if($dias_restantes < 15){
						$mensaje_dias = "<span class='badge badge-warning'>".$dias_restantes ." días</span>";
						
					}
					if($dias_restantes < 0){
						$mensaje_dias = "<span class='badge badge-danger'>PÓLIZA VENCIDA</span>";
						
					}
					
				?>
				
				<tr>
					<td>
						<input value="<?php echo $fila["num_eco"];?>" class="seleccionar"  type="checkbox">
						
					</td>
					<td><?php echo $fila["num_eco"];?></td>
					<td><?php echo $fila["nombre_propietarios"];?></td>
					<td><?php echo $fila["tipo_unidad"];?></td>
					<td class="text-right">
					<?php
						if($fila["vigencia"] != '0000-00-00'){
							
							echo date("d-m-Y", strtotime($fila["vigencia"]))."<br>".$mensaje_dias;  
						}
						
					?>
					</td>
					<td><?php
						
						echo "<span class='badge $color'>{$fila["estatus_unidades"]}</span>";
						
						
					?></td>
					<td>
						
						<button class="btn btn-warning btn_editar" data-id_registro="<?php echo $fila["num_eco"];?>">
						<i class="fas fa-edit"></i>
						</button>	
						
						<?php if(in_array(dame_permiso("unidades.php", $link), array('Supervisor' , "Administrador" ))){ ?>
							<button class="btn btn-info btn_historial" data-id_registro="<?php echo $fila["num_eco"];?>">
								<i class="fas fa-clock"></i> 
							</button>
							<a hidden target="_blank" class="btn btn-secondary" href="unidades/imprimir_qr.php?num_eco=<?php echo $fila["num_eco"];?>">
								<i class="fas fa-qrcode"></i> 
							</a>
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