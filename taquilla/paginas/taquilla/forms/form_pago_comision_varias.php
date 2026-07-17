


<form class="was-validated " id="form_pago_comision" autocomplete="off">
	<!-- The Modal -->
	<div class="modal fade" id="modal_pago_comision" data-backdrop="static">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				
				<!-- Modal Header -->
				<div class="modal-header">
					<h4 class="modal-title text-center">Pago de Comisión</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				
				<!-- Modal body -->
				<div class="modal-body">
					
					
					
					<div class="form-group">
						<label for="">Folios:</label> <br>
						<input  class="form-control" type="text" id="folios_pago" name="folios_pago" readonly> 
					</div>
					
					<div class="form-group">
						<div id="unidades">
							<div class="form-row text-right">
								<div class="form-group col-2">
								<input type="number" step="any" class="form-control num_eco text-right" name="num_eco[]" required >
								</div>
								<div class="form-group col-3">
									<input tabindex="-1"  type="text" class="form-control monto text-right" name="monto[]" readonly>
								</div>
								
								
							</div>	
						</div>	
					</div>
					
					<div class="form-group">
						<label >Operador:</label>
						<?php  echo generar_select($link, "conductores", "id_conductores", "nombre_conductores", false, false, true); ?>
					</div>
					
					<div class="form-group">
						<label>Observaciones</label> <br>
						<input  class="form-control" type="text" name="observaciones" id="observaciones" > 
					</div>
					
					
					<div class="form-group">
						<label>Total Viajes:</label>
						<div class="input-group input-group-lg">
							<div class="input-group-prepend">
								<span class="input-group-text">
									<i class="fas fa-dollar-sign"></i>
								</span>
							</div>
							<input  class="form-control text-right" type="number" name="total_viajes" id="total_viajes" step="any" readonly> 
						</div>
					</div>
					<div class="form-group">
						<label>Comisión:</label>
						<div class="input-group input-group-lg">
							<div class="input-group-prepend">
								<span class="input-group-text">
									<i class="fas fa-dollar-sign"></i>
								</span>
							</div>
							<input  class="form-control text-right" type="number" name="comision" id="comision" step="any" readonly> 
						</div>
					</div>
					
					
				</div>
				<!-- Modal footer -->
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal">
					<i class="fas fa-times"></i> Cerrar</button>
					<button type="submit"  class="btn btn-success">
						<i class="fas fa-check"></i> Aceptar
					</button>
				</div>
				
			</div>
		</div>
	</div>
</form>
