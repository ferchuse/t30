<?php 
	session_start();
	include('../../../conexi.php');
	include('../../../funciones/dame_permiso.php');
	$link = Conectarse();
	
	$consulta = "SELECT * FROM cat_gastos
	";
	
	
	$consulta.= "ORDER BY descripcion_gastos ";
	$result = mysqli_query($link,$consulta);
	
	if($result){
		$num_registros = mysqli_num_rows($result);
	?>
	<table class="table table-bordered" id="tabla_registros" width="100%" cellspacing="0">
		<thead>
			<tr>
				
				<th class="text-center">Descripción</th>
				<th class="text-center"></th>
				
			</tr>
		</thead>
		<tbody >
			<?php
				while($fila = mysqli_fetch_assoc($result)){?>
				
				<tr>
					<td><?php echo $fila["descripcion_gastos"];?></td>
					
					<td>
						<?php if(in_array(dame_permiso("gastos.php", $link), array('Supervisor', "Administrador"))){ ?>
							<button class="btn btn-warning btn_editar" data-id_registro="<?php echo $fila["id_cat_gastos"];?>">
								<i class="fas fa-edit"></i>
							</button>
							<button class="btn btn-danger btn_borrar" data-id_registro="<?php echo $fila["id_cat_gastos"];?>">
								<i class="fas fa-trash"></i> 
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