<form class="was-validated " id="form_edicion" autocomplete="off" data-tabla="paquetes">
	<!-- The Modal -->
	<div class="modal fade" id="modal_edicion">
		<div class="modal-dialog modal-dialog-centered modal-lg">
			<div class="modal-content">
				
				<!-- Modal Header -->
				<div class="modal-header">
					<h4 class="modal-title text-center"></h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				
				<!-- Modal body --> 
				<div class="modal-body">
					<div class="form-group">
						<label>Tipo de Paquete</label>
						<select name="paquetes[tipo_paquete][]" class="form-control">
							<option value="">Elige</option>
							<option value="Chico">Chico</option>
							<option value="Mediano">Mediano</option>
							<option value="Grande">Grande</option>
						</select>
					</div>
					<div class="form-group">
						<label>Destinatario</label>
						<input name="paquetes[destinatario][]" class="form-control">
					</div>
					<div class="form-group">
						<label>Precio</label>
						<input name="paquetes[precio][]" class="form-control">
					</div>
					
				</div>
				<!-- Modal footer -->
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
					<button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Guardar</button>
				</div>
				
			</div>
		</div>
	</div>
</form>												