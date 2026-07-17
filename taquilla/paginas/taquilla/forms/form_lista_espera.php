<form class="was-validated " id="form_lista_espera">
	<!-- The Modal -->
	<div class="modal fade" id="modal_lista_espera">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				
				<!-- Modal Header -->
				<div class="modal-header">
					<h4 class="modal-title text-center"></h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				
				<!-- Modal body -->
				<div class="modal-body">
					<input type="text" hidden class="form-control" id="id_espera" name="id_espera">
					
					<div class="form-group">
						<label for="cliente">Nombre Cliente:</label>
						<input type="text" class="form-control" id="cliente" name="cliente" required>
					</div> 
					<div class="form-group">
						<label for="telefono">Teléfono: </label>
						<input type="tel" class="form-control" id="telefono" name="telefono" required>
					</div> 
					<div class="form-group">
						<label for="pasajeros">Pasajeros: </label>
						<input type="number" class="form-control" id="pasajeros" name="pasajeros" required>
					</div> 
					<div class="form-group">
						<label for="pasajeros">Num Eco: </label>
						<?php echo generar_select($link, "unidades", "num_eco", "num_eco", false, false, false); ?>
					</div> 
					<div class="form-group">
						<label>Estatus:</label>
						<select  class="form-control"  name="estatus" id="estatus" required>
							<option value="">Elige</option>
							<option selected value="En Espera">En Espera</option>
							<option  value="Finalizado">Finalizado</option>
							<option  value="Cancelado">Cancelado</option>
						</select>
					</div>
					
					
					
				</div> 
				<!-- Modal footer -->
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal">
						<i class="fa fa-times"></i> Cancelar
					</button>
					<button type="submit" class="btn btn-success">
						<i class="fa fa-save"></i> Guardar
					</button>
				</div>
				
			</div>
		</div>
	</div>
</form>
