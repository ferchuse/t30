<form id="form_edicion" autocomplete="off" class="was-validated">
	<div class="modal " id="modal_edicion">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Editar</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				
				<div class="modal-body">
					
					<div class="form-group" hidden>
						<label >Id:</label>
						
						<input class="form-control" type="number" name="id_cat_gastos" id="id_cat_gastos" readonly>
					</div>	
					
					<div class="form-group">		
						<label >Descripción:</label>
						<input class="form-control" type="text" name="descripcion_gastos" id="descripcion_gastos" required>
					</div>
					
				</div>
				
				<!-- Modal footer -->
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal">
					<i class="fas fa-times"></i> Cancelar</button>
					<button type="submit" class="btn btn-success " >
					<i class="fas fa-save"></i> Guardar </button>
			</div>
		</div>
	</div>
</div>
</form>		
