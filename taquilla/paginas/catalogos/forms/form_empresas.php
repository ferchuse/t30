<form class="was-validated " id="form_empresas">
	  <!-- The Modal -->
	 <div class="modal fade" id="modal_empresas">
	   <div class="modal-dialog modal-sm">
		 <div class="modal-content">

		   <!-- Modal Header -->
		   <div class="modal-header">
			 <h4 class="modal-title text-center"></h4>
			 <button type="button" class="close" data-dismiss="modal">&times;</button>
		   </div>

		   <!-- Modal body -->
			<div class="modal-body">
					<input type="text" hidden class="form-control" id="id_empresas" name="id_empresas">
				<div class="form-group">
					<label for="nombre_empresas">NOMBRE EMPRESA</label>
					<input type="text" class="form-control" id="nombre_empresas" name="nombre_empresas" placeholder="Nombre la empresa" required>
				</div>
                <div class="form-group">
					<label for="usuario_empresas">Usuario:</label>
					<input type="text" class="form-control" id="usuario_empresas" name="usuario_empresas" placeholder="" required>
				</div> 
                <div class="form-group">
					<label for="password_empresas">Contraseña:</label>
					<input type="password" class="form-control" id="password_empresas" name="password_empresas" placeholder="">
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
