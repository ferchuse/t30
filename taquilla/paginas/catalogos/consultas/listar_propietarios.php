<?php 
	session_start();
	include('../../../conexi.php');
	include('../../../funciones/dame_permiso.php');
	$link = Conectarse();
	
	$consulta = "SELECT * FROM propietarios 
	LEFT JOIN empresas USING(id_empresas) 
	WHERE 1 
	";
	
	
	
	if($_GET["id_empresas"] != ''){
		$consulta.= " AND id_empresas = '{$_GET["id_empresas"]}' ";
	}
	if($_GET["estatus"] != ''){
		$consulta.= " AND estatus = '{$_GET["estatus"]}' ";
	}
	
	if($_GET["nombre_propietarios"] != ""){
		
		$consulta.=" AND nombre_propietarios LIKE '%{$_GET["nombre_propietarios"]}%'";
	}
	$consulta.=" AND id_empresas = '{$_COOKIE["empresa_asignada"]}'";
	
	
	$consulta.= "ORDER BY nombre_propietarios ";
	$result = mysqli_query($link,$consulta);
	
	if($result){
		$num_registros = mysqli_num_rows($result);
	?>
	<table class="table table-bordered" id="tabla_registros" width="100%" cellspacing="0">
		<thead>
			<tr>
				<th class="text-center">Nombre</th>
				<th class="text-center">Empresa</th>
				<th class="text-center">Estatus</th>
				<th class="text-center">Acciones</th>
			</tr>
		</thead>
		<tbody >
			<?php
				while($fila = mysqli_fetch_assoc($result)){
					
					$color = $fila["estatus"]  == "Activo" ? "badge-success" : "badge-danger";
				?>
				
				
				<tr>
					
					<td><?php echo $fila["nombre_propietarios"];?></td>
					<td><?php echo $fila["nombre_empresas"];?></td>
					
					<td><?php echo "<span class='badge $color'>{$fila["estatus"]}</span>";?></td>
					<td>
						<?php if(dame_permiso("propietarios.php", $link) == 'Supervisor'){ ?>
							<button class="btn btn-warning btn_editar" data-id_registro="<?php echo $fila["id_propietarios"];?>">
								<i class="fas fa-edit"></i>
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