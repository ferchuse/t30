<form class="was-validated " id="form_modal" autocomplete="off">
	<!-- The Modal -->
	<div class="modal fade" id="modal_modal">
		<div class="modal-dialog modal-dialog-centered modal-md">
			<div class="modal-content">
				
				<!-- Modal Header -->
				<div class="modal-header">
					<h4 class="modal-title text-center"></h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				
				<!-- Modal body -->
				<div class="modal-body">
					<input type="text" hidden class="form-control" id="id_taquilla" name="id_taquilla">
					
					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="fecha_abonogeneral">Taquilla:</label>
							<?php echo generar_select($link, "recaudaciones", "id_recaudaciones", "nombre_recaudaciones", false, false, true, $_COOKIE["id_recaudaciones"])?>
						</div>
						<div class="form-group col-md-6">
							<label for="fecha_abonogeneral">Fecha:</label>
							<input type="date" class="form-control" value="<?php echo date("Y-m-d");?>" id="fecha_boletaje" name="fecha_boletaje" required>
						</div>
					</div>
					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="fecha_abonogeneral">Num Eco:</label>
							<input type="number" class="form-control"  id="num_eco" name="num_eco" required>
							<input type="number" hidden class="form-control"  id="id_unidades" name="id_unidades" required>
						</div>
						<div class="form-group col-md-6">
							<label for="fecha_abonogeneral">Asientos:</label>
							<input type="number" class="form-control"  id="asientos" name="asientos" required>
						</div>
					</div>
					
					<hr>
					
					<div class="form-row" >
						<div class="form-group col-md-4">
							<label for="">Denominación</label>							
						</div>
						<div class="form-group col-md-4">
							<label for="">Cantidad</label>
						</div>
						<div class="form-group col-md-4">
							<label for="">Importe</label>
							
						</div>
					</div>
					
					<div id="boletos">
						<div class="form-row" >
							<div class="form-group col-md-4">
								<div class="input-group">
									<div class="input-group-prepend">
										<div class="input-group-text"><i class="fas fa-dollar-sign"></i></div>
									</div>
									<input type="number" min="0" class="form-control denominacion" name="denominacion[]">
								</div> 
							</div>
							<div class="form-group col-md-4">
								<input type="number" min="0" class="form-control cantidad" name="cantidad[]">
							</div>
							<div class="form-group col-md-4"> 
								<input type="number" min="0" class="form-control importe" name="importe[]" readOnly>
							</div>
						</div> 
					</div>
					
					<div class="form-row"> 
						<div class=" col-md-2 ">
							<button class="btn btn-success" type="button" id="agregar_fila">
								<i class="fa fa-plus"> </i> 
							</button>
						</div>
						<div class="form-group col-md-2 text-right">
							<label for="">Total:</label>
						</div>
						<div class="form-group  col-md-4">
							<input type="number" min="0" class="form-control" id="cantidad_boletos" name="cantidad_boletos" val="0" readOnly >
						</div>
						<div class="form-group  col-md-4">
							<input type="number" min="0" class="form-control" id="total_boletaje" name="total_boletaje" val="0" readOnly >
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