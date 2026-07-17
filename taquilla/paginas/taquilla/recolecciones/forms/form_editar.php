
<form class="was-validated " id="form_editar" autocomplete="off">
	<!-- The Modal -->
	<div class="modal fade" id="modal_editar">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				
				<!-- Modal Header -->
				<div class="modal-header">
					<h4 class="modal-title text-center">Editar Recolección</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				
				<!-- Modal body -->
				<div class="modal-body">
					
					<div class="row"  >
						<div class="form-group col-sm-6"  >
							<label>Fecha de Recoleccion</label>
							<input id="editar_fecha_recoleccion" name="fecha_recoleccion" class="form-control" type="datetime-local" value="<?= date("Y-m-d")?>"  >
						</div>
						<div class="form-group col-sm-6"  >
							<label>Folio:</label>
							<input class="form-control" type="number" name="id_recoleccion" readonly id="editar_id_recoleccion">
						</div>
					</div>
					
					<div class="form-group row">
						
						
						<div class="form-group col-sm-6">
							<label class="">Lugar Recolección:</label>
							<select class="form-control" id="editar_destino" name="destino" required>
								<option value="" required >Elige un destino:</option>
								<?php
									foreach($destinos AS $i=> $destino){
									?>
									<option 
									data-precio="<?php echo $destino["precio"];?>"
									data-precio_ejecutiva="<?php echo $destino["precio_ejecutiva"];?>"
									><?php echo $destino["destino"];?></option>
									<?php
									}
								?>
							</select>
						</div>
						
						<div class="col-sm-6">
							<label>Num Pasajeros:</label>
							<input type="number" required id="editar_pasajeros" name="pasajeros" class="form-control text-right">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-sm-6">
							<label>Nombre Pasajero:</label>
							<input type="text" required id="editar_nombre_pasajero" name="nombre_pasajero" class="form-control mayus">
						</div>
						<div class="col-sm-6">
							<label>#Teléfono:</label>
							<input type="tel"  id="editar_celular" name="celular" class="form-control" required>
						</div>
						
					</div>
					
					
					<div class="row">
						<div class="form-group col-sm-6">
							<label>Precio :</label>
							<input type="number" id="editar_total" name="total" class="form-control text-right" required>
						</div>
						<div class="form-group col-sm-6">
												<label>Tipo :</label>
												<select class="form-control" id="tipo_recoleccion" name="tipo_recoleccion" required>
													<option selected >RECOLECCIÓN</option>
													<option  >RESERVACIÓN</option>
											
												</select>
											</div>
						
					</div>
					
					<div class="row">
						<div class="form-group col-sm-6">
							<label>Anticipo :</label>
							<input type="number" id="editar_anticipo" name="anticipo" class="form-control text-right" required >
						</div>
						<div class="form-group col-sm-6">
							<label class="">Forma de Pago:</label>
							<select class="form-control" id="editar_forma_pago" name="forma_pago" required>
								<option value="" >Elige..</option>
								<option  >Efectivo</option>
								<option  >Transferencia</option>
								<option >Tarjeta</option>
								<option  >Mixto</option>
							</select>
						</div>
					</div>
					<div class="row form-group">
						<div class=" col-sm-6">
							<label>Restante :</label>
							<input type="number" id="editar_restante" name="restante" class="form-control text-right" required readonly>
						</div>
						<div class=" col-sm-6">
							<label>Folio Transferencia :</label>
							<input type="number" id="editar_referencia" name="referencia" class="form-control text-right" >
						</div>
					</div>
					
				</div>
				<!-- Modal footer -->
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal">
					<i class="fas fa-times"></i> Cancelar
					</button>
					<button type="submit"  class="btn btn-success">
						<i class="fas fa-save"></i> Guardar
					</button>
				</div>
				
			</div>
		</div>
	</div>
</form>
