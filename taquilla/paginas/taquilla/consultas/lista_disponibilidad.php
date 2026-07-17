<?php 
	
	include('../../../conexi.php');
	include('../../../funciones/generar_select.php');
	include('../../../funciones/dame_permiso.php');
	$link = Conectarse();
	$fila = array();
	$respuesta = array();
	$categorias = array();
	$filas =  array();
	$totales =  array();
	
	
	$consulta_unidades = "SELECT * FROM unidades  WHERE estatus_unidades = 'Activo'";
	
	
	$result = mysqli_query($link,$consulta_unidades) or die("Error en $consulta_unidades ". mysqli_error($link));
	
	while($row = mysqli_fetch_assoc($result)){	
		$filas[] = $row ;
	}
	
	
?>  
<pre hidden >
	<?php echo $consulta_gastos;?>
</pre>
<!-- Contenedor -->
<div class="container my-3">
	
	<!-- Encabezado + filtro -->
	<div class="d-flex flex-wrap gap-2 align-items-center mb-2">
		<h5 class="mb-0">Disponibilidad de Vehículos</h5>
		<div class="ms-auto">
			<select id="filtroEstado" class="form-select form-select-sm">
				<option value="">Todos</option>
				<option>Disponible</option>
				<option>En ruta</option>
				<option>Reservado</option>
				<option>Mantenimiento</option>
			</select>
		</div>
	</div>
	
	<!-- Leyenda -->
	<div class="small mb-2">
		<span class="badge bg-success">Disponible</span>
		<span class="badge bg-danger">En ruta</span>
		<span class="badge bg-warning text-dark">Reservado</span>
		<span class="badge bg-secondary">Mantenimiento</span>
	</div>
	
	<!-- Tabla -->
	<div class="table-responsive">
		<table class="table table-sm table-hover align-middle" id="tablaVehiculos">
			<thead class="table-light">
				<tr>
					<th>Placas</th>
					<th>Marca/Modelo</th>
					<th>Estado</th>
					<th>Conductor</th>
					<th>Último movimiento</th>
					<th class="text-end">Acciones</th>
				</tr>
			</thead>
			<tbody>
				<!-- Ejemplos (reemplaza por tu loop PHP) -->
				<?php 
					foreach($filas AS $fila){ 
						
					?>
					<tr data-estado="Disponible">
						<td><?php echo $fila["placas"]?></td>
						<td><?php echo $fila["tipo_unidad"]?></td>
						<td><span class="badge bg-success text-white"><?php echo $fila["disponibilidad"]?></span></td>
						<td>—</td>
						<td>2025-11-07 08:30</td>
						<td class="text-end">
							<button class="btn btn-sm btn-primary">Asignar</button>
							<button class="btn btn-sm btn-outline-secondary">Historial</button>
						</td>
					</tr>
					
					<?php
					}
				?>
				<tr data-estado="En ruta">
					<td>XYZ-456</td>
					<td>Toyota Hiace 2020</td>
					<td><span class="badge bg-danger text-white">En ruta</span></td>
					<td>Juan Pérez</td>
					<td>2025-11-07 10:00</td>
					<td class="text-end">
						<button class="btn btn-sm btn-success">Registrar llegada</button>
						<button class="btn btn-sm btn-outline-secondary">Historial</button>
					</td>
				</tr>
				<tr data-estado="Mantenimiento">
					<td>JKL-789</td>
					<td>Chevrolet Aveo 2018</td>
					<td><span class="badge bg-secondary text-white">Mantenimiento</span></td>
					<td>—</td>
					<td>2025-11-06 17:20</td>
					<td class="text-end">
						<button class="btn btn-sm btn-outline-primary">Marcar disponible</button>
						<button class="btn btn-sm btn-outline-secondary">Historial</button>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>

<script>
	// Filtro por estado (sin librerías)
	document.getElementById('filtroEstado').addEventListener('change', function(){
		const val = this.value;
		document.querySelectorAll('#tablaVehiculos tbody tr').forEach(tr => {
			tr.style.display = (!val || tr.getAttribute('data-estado') === val) ? '' : 'none';
		});
	});
</script>




<table class="table table-bordered table-sm">
	<thead>
		<tr>
			<th>Unidad</th>
			<th>Viajes</th>
			<?php
				foreach($cat_gastos AS $i => $cat_gasto){ ?>
				<th><?php echo  $cat_gasto["descripcion_gastos"];?></th>
				<?php
				}
			?>
			
			
			
		</tr>
	</thead>
	<tbody>
		<?php 
			foreach($filas AS $fila){ 
				$total_viajes+=  $fila["viajes"];
			?>
			<tr class="focusable text-right" >
				
				<td><b><?php echo $fila["num_eco"]?></b></td>
				<td ><?php echo $fila["viajes"]?></td>
				<?php
					foreach($cat_gastos AS $i => $cat_gasto){ 
						$totales[$i]+= $fila["monto_gasto_$i"];
						
					?>
					<td class="text-right"><?php 
						if($fila["monto_gasto_$i"] == ""){
							
						}
						else{
							echo "$" .number_format($fila["monto_gasto_$i"],2);
						}
						
						
						
					?></td>
					<?php
					}
				?>
				
				
			</tr>
			
			<?php
			}
		?>
		
		
	</tbody>
	<tfoot class="bg-dark text-white">
		<tr class="text-right">
			
			<td colspan=""> TOTALES</td>
			<td><?php echo number_format($total_viajes);?></td>
			<?php
				foreach($totales AS $i => $total){  ?>
				<td>$<?php echo number_format($total,2);?></td>
				<?php
				}
			?>
			
			
			
			
		</tr>
	</tfoot>
</table>

