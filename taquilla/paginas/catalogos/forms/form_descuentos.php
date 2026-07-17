<form class="was-validated " id="form_edicion" autocomplete="off">
	<!-- The Modal -->
	<div class="modal fade" id="modal_edicion">
		<div class="modal-dialog">
			<div class="modal-content">
				
				<!-- Modal Header -->
				<div class="modal-header">
					<h4 class="modal-title text-center"></h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				
				<!-- Modal body -->
				<div class="modal-body">
					
					<input type="text" hidden class="form-control" id="id_descuento" name="id_descuento">
					<div class="form-group">
						<label for="nombre_conductores">Descripción:</label>
						<input type="text" class="form-control" id="tipo_descuento" name="tipo_descuento"  required>
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
