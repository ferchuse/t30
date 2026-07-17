<form class="was-validated " id="form_edicion" autocomplete="off">
	<!-- The Modal -->
	<div class="modal fade" id="modal_edicion">
		<div class="modal-dialog modal-dialog-centered modal-md">
			<div class="modal-content">
				
				<!-- Modal Header -->
				<div class="modal-header">
					<h4 class="modal-title text-center"></h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				
				<!-- Modal body -->
				<div class="modal-body">
					
					<input type="number" hidden class="form-control"  id="id_precio" name="id_precio" >
					
					<div class="form-group">
						<label >Destino:</label>
						<input class="form-control" id="destino" name="destino" required >
					</div>
					
					<div class="form-group">
						<label >Precio:</label>
						<input type="number" step="any"  class="form-control text-right"  id="precio" name="precio" required>
					</div>
					
					<div class="form-group">
						<label >Estatus:</label>
						<select class="form-control" id="estatus_precio" name="estatus_precio" required>
							<option value="">Seleccione</option>
							<option selected value="Activo">Activo</option>
							<option value="Inactivo">Inactivo</option>
						</select>
					</div>
					
					<div class="form-group">
						<label >Tipo Viaje:</label>
						<select class="form-control" id="tipo_viaje" name="tipo_viaje" required>
							<option value="">Seleccione</option>
							<option selected value="Sencillo">Sencillo</option>
							<option value="Redondo">Redondo</option>
						</select>
					</div>
					
					
				</div>
				<!-- Modal footer -->
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
					<button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Guardar</button>
				</div>
				
			</div>
		</div>
	</div>
</form>												