

<form class="was-validated " id="form_edicion" autocomplete="off">
	<!-- The Modal -->
	<div class="modal fade" id="modal_edicion">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				
				<!-- Modal Header -->
				<div class="modal-header">
					<h4 class="modal-title text-center"></h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				
				<!-- Modal body -->
				<div class="modal-body">
					<input type="text" hidden class="form-control" id="id_roles" name="id_roles">
					<div class="form-group col-sm-6">
						<label for="numero_roles">NUMERO </label>
						<input type="text" class="form-control" id="numero_roles" name="numero_roles" placeholder="Numero" required>
					</div>
					<div class="form-group col-sm-6">
						<label for="nombre_roles">NOMBRE</label>
						<input type="text" class="form-control" id="nombre_roles" name="nombre_roles" placeholder="Nombre " required>
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
								<tr class="rol" id="rol">
									<td>
										<?php echo generar_select($link, "origenes", "id_origenes", "nombre_origenes", false, false, false, 0,0,"id_origen[]")?>
									</td>
									<td>
										<?php echo generar_select($link, "origenes", "id_origenes", "nombre_origenes", false, false, false, 0,0,"id_destino[]")?>
									</td>
									
									<td>
										<button type="button" class="btn btn-sm btn-danger borrar_rol" >
											<i class="fas fa-times"></i>
										</button>
									</td>	
								</tr>
							</tbody>
						</table>
						
					</div>
					<!-- Modal footer -->
					<div class="modal-footer">
						<button type="button" class="btn btn-outline-danger" data-dismiss="modal">
							<i class="fa fa-times"></i> Cancelar
						</button>
						<button type="submit" class="btn btn-outline-success"><i class="fa fa-save"></i> Guardar</button>
					</div>
					
				</div>
			</div>
		</div>
	</form>
	
	<script>
		
		$(document).ready(function(){
			ListarRegistros();
		});
		
		function ListarRegistros() {
			
			$.ajax({
				url: 'control/listar.php',
				method: 'POST',
				dataType: 'JSON',
				data: {
					tabla: 'origenes',
					subconsulta: subconsulta
				}
				}).done(function(respuesta) {
				if (respuesta.estatus == 'success') {
					let roles = '';
					if (respuesta.num_rows > 0) {
						$.each(respuesta, function(i, item) {
							$('#destino_roles').append($('<option>', {
								value: item.id_origenes,
								text: item.nombre_origenes
							}));
							$('#origenes_roles').append($('<option>', {
								value: item.id_origenes,
								text: item.nombre_origenes
							}));
						});
					}
				}
			});
		}
		
	</script>				