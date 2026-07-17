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
	
	$consulta = "SELECT * FROM conductores WHERE 1";
	
	if($_GET["nombre_conductores"] != ""){
		
		$consulta.= " AND nombre_conductores LIKE '%{$_GET["nombre_conductores"]}%' ";
	}
	
	if($_GET["estatus_conductores"] != ""){
		
		$consulta.= " AND estatus_conductores = '{$_GET["estatus_conductores"]}' ";
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
				<th class="text-center">Vigencia Licencia</th>
				<th class="text-center">Vigencia Certificado</th>
				<th class="text-center">Vigencia Curso</th>
				<th class="text-center">Tag</th>
				<th class="text-center">Estatus</th>
				<th class="text-center"></th>
			</tr>
		</thead>
		<tbody >
			<?php
				while($fila = mysqli_fetch_assoc($result)){
					
					$dias_restantes_licencia = dias_restantes($fila["fechaVigencia_conductores"]);
					
					if($dias_restantes_licencia > 60){
						$vigencia_licencia = "<span class='badge badge-success'>".$dias_restantes_licencia ." días</span>";
					}
					if($dias_restantes_licencia < 60){
						$vigencia_licencia = "<span class='badge badge-warning'>".$dias_restantes_licencia ." días</span>";
						
					}
					if($dias_restantes_licencia < 0){
						$vigencia_licencia = "<span class='badge badge-danger'>VENCIDO</span>";
					}
					
					
					$dias_restantes_certificado = dias_restantes($fila["vigencia_certificado"]);
					
					if($dias_restantes_certificado > 60){
						$vigencia_certificado = "<span class='badge badge-success'>".$dias_restantes_certificado ." días</span>";
					}
					if($dias_restantes_certificado < 60){
						$vigencia_certificado = "<span class='badge badge-warning'>".$dias_restantes_certificado ." días</span>";
						
					}
					if($dias_restantes_certificado < 0){
						$vigencia_certificado = "<span class='badge badge-danger'>VENCIDO</span>";
					}
					
					
					$dias_restantes_curso = dias_restantes($fila["vigencia_curso"]);
					// echo $dias_restantes_curso;
					
					
					if($dias_restantes_curso > 15){
						// echo "verde";
						$vigencia_curso = "<span class='badge badge-success'>".$dias_restantes_curso ." días</span>";
					}
					if($dias_restantes_curso < 15){
						// echo "amarillo";
						$vigencia_curso = "<span class='badge badge-warning'>".$dias_restantes_curso ." días</span>";
						
					}
					if($dias_restantes_curso < 0){
						
						$vigencia_curso = "<span class='badge badge-danger'>VENCIDO</span>";
						// $vigencia_curso = "<span class='badge badge-danger'>".$dias_restantes_curso ." días</span>";
					}
					
					
				?>
				
				
				<tr>
					<td class="text-center"><?php echo $fila["id_conductores"];?></td>
					<td class="text-left"><?php echo $fila["nombre_conductores"];?></td>
					<td class="text-center">
						
						<?php 
							if($fila["fechaVigencia_conductores"] != '0000-00-00'){
								echo date("d/m/Y", strtotime($fila["fechaVigencia_conductores"]))."<br>". $vigencia_licencia;
							}
						?>
					</td>
					<td class="text-center">
						<?php 
							if($fila["vigencia_certificado"] != '0000-00-00'){
								echo date("d/m/Y", strtotime($fila["vigencia_certificado"]))."<br>". $vigencia_certificado;
							}
						?>
					</td>
					<td class="text-center">
						<?php 
							if($fila["vigencia_curso"] != '0000-00-00'){
								echo date("d/m/Y", strtotime($fila["vigencia_curso"]))."<br>". $vigencia_curso;
							}
							
						?>
					</td>
					<td class="text-center">
						<?php echo $fila["tag_operador"];?>
					</td>
					<td class="text-center">
						<?php
							if($fila["estatus_conductores"] == "Activo"){
								echo "<span class='badge badge-success'>{$fila["estatus_conductores"]}</span>";
							}
							else{
								echo "<span class='badge badge-danger'>{$fila["estatus_conductores"]}</span>";
							}
						?>
					</td>
					
					<td class="text-center"  >
						<?php if(in_array( dame_permiso("conductores.php", $link) ,array('Supervisor' ,'Escritura',"Administrador" ))){ ?>
							<button class="btn btn-outline-warning editar" data-id_conductores='<?php echo $fila["id_conductores"];?>'>
								<i class="fas fa-pencil-alt"></i>
							</button>
							<button class="btn btn-outline-danger eliminar" data-id_conductores='<?php echo $fila["id_conductores"];?>'>
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