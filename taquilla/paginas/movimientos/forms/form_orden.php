<form class="was-validated " id="form_edicion" autocomplete="off">
	<!-- The Modal -->
	<div class="modal fade" id="modal_edicion">
		<div class="modal-dialog ">
			<div class="modal-content">
				
				<!-- Modal Header -->
				<div class="modal-header">
					<h4 class="modal-title text-center"></h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				
				<!-- Modal body -->
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group" hidden>
								<label for="id_ordenes">Folio:</label>
								<input type="text" class="form-control" id="id_ordenes" name="id_ordenes" readonly>
							</div>	
							<div class="form-group">
								<label for="nombre_conductores">Operador: </label>
								<?php echo  generar_select($link, "conductores", "id_conductores", "nombre_conductores", false, false, true);
								?>
							</div>
							<div class="form-group">
								<label for="num_eco">Num Eco</label>
								<input type="number" class="form-control" id="num_eco" name="num_eco"  required>
							</div>
							
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label for="dias">Dias de Trabajo: </label>
								<select id="dias" name="dias" CLASS="form-control" required>
									<option >30</option>
									<option >60</option>
									<option >90</option>
								</select>
								</div>
							<div class="form-group">
								<label for="fecha_inicio">Fecha Inicial:</label>
								<input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?= date("Y-m-d")?>" required>
							</div> 
							<div class="form-group">
								<label for="fecha_fin">Fecha Final:</label>
								<input type="date" class="form-control" id="fecha_fin" name="fecha_fin" required value="<?= date("Y-m-d", strtotime("+30 days"))?>">
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
