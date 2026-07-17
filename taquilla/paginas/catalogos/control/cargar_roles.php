<?php 
	session_start();
	include('../../../conexi.php');
	include('../../../funciones/generar_select.php');
	$link = Conectarse();
	
	
	$consulta = "SELECT * FROM roles 
	LEFT JOIN roles_destinos USING(id_roles)
	WHERE id_roles = {$_GET['id_roles']}
	ORDER BY id_vueltas
	";
	
	$result = mysqli_query($link,$consulta);
	
	if($result){
		$num_registros = mysqli_num_rows($result);
		
		while($fila = mysqli_fetch_assoc($result)){
			
			$filas[] = $fila; 
		}
	?>
	<input type="text" hidden class="form-control" id="id_roles" name="id_roles" value="<?php echo $filas[0]["id_roles"]?>">
	<div class="form-group col-sm-6">
		<label for="numero_roles">NUMERO </label>
		<input type="text" class="form-control" id="numero_roles" name="numero_roles" placeholder="Numero" required value="<?php echo $filas[0]["numero_roles"]?>">
	</div>
	<div class="form-group col-sm-6">
		<label for="nombre_roles">NOMBRE</label>
		<input type="text" class="form-control" id="nombre_roles" name="nombre_roles" placeholder="Nombre " required value="<?php echo $filas[0]["nombre_roles"]?>">
	</div>
	<button type="button" class="btn btn-success" id="agregar_rol">
		<i class="fas fa-plus"></i> Agregar
	</button>
	<table>
		<table id="tabla_rol">
			<thead>
				<tr>
					<th>ORIGEN</th>
					<th>DESTINO</th>
					<th>Borrar</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($filas as $i=>$fila){?>
					<tr class="rol" id="rol">
						<td>
							<?php echo generar_select($link, "origenes", "id_origenes", "nombre_origenes", false, false, false, $fila["origen"],0,"id_origen[]")?>
						</td>
						<td>
							<?php echo generar_select($link, "origenes", "id_origenes", "nombre_origenes", false, false, false,  $fila["destino"],0,"id_destino[]")?>
						</td>
						
						<td>
							<button type="button" class="btn btn-sm btn-danger borrar_rol" >
								<i class="fas fa-times"></i>
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