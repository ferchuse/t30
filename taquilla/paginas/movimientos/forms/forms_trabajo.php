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
				<input type="text" hidden class="form-control" id="id_derroteros" name="id_derroteros">
				<div class="form-group">
					<label for="nombre_derroteros">NOMBRE</label>
					<input type="text" class="form-control" id="nombre_derroteros" name="nombre_derroteros" placeholder="Nombre derroteros" required>
				</div>
                <div class="form-group">
					<label for="cuenta_derroteros">CUENTA</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><i class="fas fa-dollar-sign"></i></div>
                        </div>
                        <input type="number" class="form-control" id="cuenta_derroteros" name="cuenta_derroteros"  min="0">
                    </div>
				</div> 
                <div class="form-group">
					<label for="gasto_administracion">GASTO DE ADMINISTRACIÓN</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><i class="fas fa-dollar-sign"></i></div>
                        </div>
                        <input type="number" class="form-control" id="gasto_administracion" name="gasto_administracion"  min="0">
                    </div>
				</div>
                <div class="form-group">
                    <label for="seguro_derroteros">SEGURO INTERNO</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><i class="fas fa-dollar-sign"></i></div>
                        </div>
                        <input type="number" class="form-control" id="seguro_derroteros" name="seguro_derroteros"  min="0">
                    </div>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="estatus_derrotero" name="estatus_derrotero">
                    <label class="form-check-label" for="estatus_derrotero">LIBERAR</label>
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
