<form class="was-validated " id="form_edicion">
	<!-- The Modal -->
	<div class="modal fade" id="modal_edicion">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				
				<!-- Modal Header -->
				<div class="modal-header">
					<h4 class="modal-title text-center"></h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				
				<!-- Modal body -->
				<div class="modal-body">
					<input type="text" hidden class="form-control" id="id_taquilla" name="id_taquilla">
					<div class="form-group">
						<label for="nombre_taquilla">Nombre</label>
						<input type="text" class="form-control" id="nombre_taquilla" name="nombre_taquilla" required>
					</div> 
					<div class="form-group">
						<label for="hora_salida">Hora de Salida:</label>
						<input type="time" class="form-control" id="hora_salida" name="hora_salida" required >
					</div> 
					
				</div> 
				<!-- Modal footer -->
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
					<button type="submit" class="btn btn-outline-success"><i class="fa fa-save"></i> Guardar</button>
				</div>
				
			</div>
		</div>
	</div>
</form>
