
<form class="was-validated " id="form_liquidar" autocomplete="off">
	<!-- The Modal -->
	<div class="modal fade" id="modal_liquidar" data-backdrop="static">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				
				<!-- Modal Header -->
				<div class="modal-header">
					<h4 class="modal-title text-center">Liquidar Recolección</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				
				<!-- Modal body -->
				<div class="modal-body">
					<div class="form-group">
						<label for="">Folio:</label> <br>
						<input  class="form-control" type="number" name="id_recoleccion" readonly id="liquidar_id_recoleccion"> 
					</div>
					
					
					<div class="form-group">
						<label  >
							Requiere Factura: 
						</label>
						<select class="form-control" id="facturar" name="facturar" required>
							<option value="" >Elige..</option>
							<option  >SI</option>
							<option  >NO</option>
						</select>
					</div>
					
					<div class="form-group">
						<label class="">Forma de Pago:</label>
						<select class="form-control" id="forma_pago" name="forma_pago" required>
							<option value="" >Elige..</option>
							<option  >Efectivo</option>
							<option >Tarjeta</option>
							<option >Transferencia</option>
							<option >Mixto</option>
						</select>
					</div>
					<div class="form-group">
						<label for="">Folio de la Transferencia:</label> <br>
						<input placeholder="Requerido si es por Transferencia" class="form-control text-right" type="number" name="referencia" id="liquidar_referencia" step="any" > 
					</div>
					<div class="form-group">
						<label for="">Terminal:</label> <br>
						<select class="form-control" name="id_terminal" id="liquidar_id_terminal">
							<option value="" >Selecciona</option>
							<?php foreach($terminales as $i => $terminal){ ?>
								
								<option value="<?php echo $terminal["id_terminal"];?>" >
									<?php echo $terminal["terminal"];?>
								</option>
								<?php
								}?>
						</select>
					</div>
					<div class="form-group">
						<label for="">Saldo:</label> <br>
						<input readonly class="form-control text-right" type="number" name="restante" id="liquidar_restante" step="any"> 
					</div>
					
					
					
				</div>
				<!-- Modal footer -->
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal">
					<i class="fas fa-times"></i> Cancelar</button>
					<button type="submit" id="btn_guardar_tarjeta" class="btn btn-success">
						<i class="fas fa-check"></i> Aceptar
					</button>
				</div>
				
			</div>
		</div>
	</div>
</form>
