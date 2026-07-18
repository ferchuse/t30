<?php 
	session_start();
	include('../../../conexi.php');
	$link = Conectarse();
	
	$consulta = "SELECT * FROM usuarios 
	
	WHERE 1";
	
	if($_GET["estatus_usuarios"] != ""){
		
		$consulta.=" AND estatus_usuarios = '{$_GET["estatus_usuarios"]}'";
		}
	if($_GET["nombre_usuarios"] != ""){
		
		$consulta.=" AND nombre_usuarios LIKE '%{$_GET["nombre_usuarios"]}%'";
	}
	
	// $consulta.=" AND empresa_asignada = '{$_COOKIE["empresa_asignada"]}'";
	
	$consulta.="
	
	
	ORDER BY nombre_usuarios
	"; 
	
	$result = mysqli_query($link,$consulta);
	
	if($result){
		$num_registros = mysqli_num_rows($result);
	?>
	<table class="table table-bordered" id="tabla_registros" width="100%" cellspacing="0">
		<thead>
			<tr>
				<th class="text-center">Nombre Completo</th>
				<th class="text-center">Usuario</th>
				<th class="text-center">Estatus</th>
				<th class="text-center"></th>
			</tr>
		</thead>
		<tbody >
			<?php
				while($fila = mysqli_fetch_assoc($result)){
					
					if($fila["estatus_usuarios"] == "Activo"){
						$badge = "badge-success";
					}
					else{
						$badge = "badge-danger";
					} 
					
				?>
				
				<tr>
					<td><?php echo $fila["nombre_completo_usuarios"];?></td>
					<td><?php echo $fila["nombre_usuarios"];?></td>
					<td> 
						<span class="badge <?php echo $badge;?>">
							<?php echo $fila["estatus_usuarios"];?>
							
						</span>
					</td>
					<td>
						<button class="btn btn-warning btn_editar" data-id_registro="<?php echo $fila["id_usuarios"];?>"> 
							<i class="fas fa-edit"></i>
						</button>
					</td>
				</tr>
				
				<?php 	
				}
			?>
		</tbody>
	</table>
	
	
	<?php
		
		
	}
	else {
		echo "Error en".$consulta. mysqli_error($link);
	}
	
	
?>	