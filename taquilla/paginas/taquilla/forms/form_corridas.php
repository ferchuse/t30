<form class="was-validated " id="form_corridas" autocomplete="off">
	
	<div class="modal fade" id="modal_corridas">
		<div class="modal-dialog modal-dialog-centered modal-lg">
			<div class="modal-content">
				
				<div class="modal-header">
					<h4 class="modal-title text-center">Editar Corrida</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				 
				<div class="modal-body">
					<div class="row">
						<div class="form-group col-sm-4">
							<label for="num_eco">Num Eco:</label>
							<input type="number" class="form-control"  name="num_eco" required autofocus="true">
							<input type="hidden" id="id_corridas" name="id_corridas" value="">
						</div>
						<div class="form-group col-sm-4">
							<label for="asientos">Asientos:</label>
							<input type="number" class="form-control"  name="asientos" required>
						</div>
						
						<div class="form-group col-sm-4">
							<label for="id_taquilla">Taquilla:</label>
							<?php echo generar_select($link, "taquillas", "id_taquilla", "nombre_taquilla", false, false, true, $_COOKIE["id_recaudaciones"]) ;	?>
						</div>
					</div>
					<div class="form-row">
						<div class="form-group col-md-2">
							<label for="id_corridas">Folio:</label> 
							<input type="number" readonly class="form-control" id="id_corridas" name="id_corridas" value="">
						</div>
						<div class="form-group col-md-5">
							<label for="origen">Origen:</label>
							<select  required class="form-control"  name="origen" id="origen">
								<option selected>INDIOS VERDES</option>
								<option >CATEMACO</option>
							</select>
						</div>
						
						<div class="form-group col-5">
							<label for="destino">Destino:</label>
							<select  required class="form-control"  name="destino" id="destino">
								<option >INDIOS VERDES</option>
								<option selected>CATEMACO</option>
							</select>
						</div>
					</div>
					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="fecha_corridas">Fecha de Salida:</label>
							<input type="date" class="form-control" value="<?php echo date("Y-m-d");?>" id="fecha_corridas" name="fecha_corridas" required >
						</div>
						<div class="form-group col-md-6">
							<label for="hora_corridas">Hora de Salida:</label>
							<input type="time" class="form-control" value="<?php echo date("H:i");?>" id="hora_corridas" name="hora_corridas" required >
						</div>
					</div>
					
				</div>
				
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
					<button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Guardar</button>
				</div>
				
			</div>
		</div>
	</div>
</form>												