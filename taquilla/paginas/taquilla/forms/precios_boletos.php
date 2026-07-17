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
					<div class="form-row">
						
						<div class="form-group col-md-6">
							<label >Origen:</label>
								<?php echo generar_select($link, "origenes", "id_origenes", "nombre_origenes", false, false, true, $_COOKIE["id_recaudaciones"], 0, "id_origenes")?>
						</div>
						<div class="form-group col-md-6">
							<label >Destino:</label>
								<?php echo generar_select($link, "origenes", "id_origenes", "nombre_origenes", false, false, true, $_COOKIE["id_recaudaciones"],0, "id_destinos", "id_destinos")?>
						</div>
						<div class="form-group col-md-6">
							<label >Tipo de Boleto:</label>
								<select class="form-control"  id="tipo_precio" name="tipo_precio" required>
								<option value="">Elige</option>
								<option value="Niño">Niño</option>
								<option value="Adulto">Adulto</option>
								</select>
						</div>
						
						<div class="form-group col-md-6">
							<label >Precio:</label>
							<input type="number" step="any"  class="form-control"  id="precio" name="precio" required>
						</div>
						<div class="form-group col-md-6">
							<label >Estatus:</label>
								<select class="form-control"  id="estatus_precio" name="estatus_precio" required>
								<option >ACTIVO</option>
								<option >INACTIVO</option>
								
								</select>
						</div>
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