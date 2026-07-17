<?php 
	
	if (dame_permiso("boletos_vendidos.php", $link) == "Escritura" ){
		
		$readonly = "readonly";
	}
	else	if (dame_permiso("boletos_vendidos.php", $link) == "Supervisor" ){
		$readonly = "readonly";
	}
	else{
		$readonly = "";
	}
?>


<form class="was-validated " id="form_editar_boleto" autocomplete="off">
	<!-- The Modal -->
	<div class="modal fade" id="modal_editar_boleto" data-backdrop="static">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				
				<!-- Modal Header -->
				<div class="modal-header">
					<h4 class="modal-title text-center">Editar Boleto</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				
				<!-- Modal body -->
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-6">
							
							
							
							<div class="form-group">
								<label for="">Folio:</label> <br>
								<input  class="form-control" type="number" name="id_boletos" readonly id="boletos_id_boletos"> 
							</div>
							
							<div class="row">
								<div class="form-group col-sm-6">
									<label for="">Num Eco</label> 
									<?php  echo generar_select($link, "unidades", "num_eco", "num_eco", false, false, true, 0 ,  0, "num_eco", "boletos_num_eco"); ?>
									
								</div>
								<div class="form-group col-sm-6">
									<label  >
										Requiere Factura: 
									</label>
									<select class="form-control" id="facturar" name="facturar" required <?php echo $readonly;?>>
										<option value="" >Elige..</option>
										<option  >SI</option>
										<option  >NO</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="">Origen</label> 
								<input  class="form-control" type="text" name="origen" id="origen" <?php echo $readonly;?>> 
							</div>
							<div class="form-group">
								<label for="">Destino</label> 
								<input  class="form-control" type="text" name="destino" id="destino" <?php echo $readonly;?>> 
							</div>
							<div class="form-group">
								<label >Operador:</label>
								<?php  echo generar_select($link, "conductores", "id_conductores", "nombre_conductores", false, false, true); ?>
							</div>
							
							<div class="form-group">
								<label for="">Nombre Pasajero</label> 
								<input  class="form-control" type="text" name="nombre_pasajero" id="nombre_pasajero" > 
							</div>
							
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label >Taquilla:</label>
								
								<select class="form-control" id="taquilla" name="taquilla" >
									
									<option value="NACIONAL" SELECTED>
										NACIONAL
									</option>
									<option value="INTERNACIONAL" > 
										INTERNACIONAL
									</option>
									
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
								<label for="">Importe Total:</label> <br>
								<input  class="form-control text-right" type="number" name="total" id="total" step="any" <?php echo $readonly;?>> 
							</div>
							
							<div class="form-group row hidden">
								<div class="col-sm-4">
									<label>Efectivo:</label>
									<input type="number" step="any" id="efectivo" name="efectivo" class="form-control text-right" <?php echo $readonly;?>>
								</div>
								<div class="col-sm-4">
									<label>Tarjeta:</label>
									<input type="number"  step="any" id="tarjeta" name="tarjeta" class="form-control text-right" <?php echo $readonly;?>>
								</div>
								<div class="col-sm-4">
									<label>Transferencia:</label>
									<input type="number"  step="any" id="transferencia" name="transferencia" class="form-control text-right" <?php echo $readonly;?>>
								</div>
							</div>
							
							<div class="form-group">
								
								
								
								<?php 
									
									if (dame_permiso("boletos_vendidos.php", $link) == "Administrador" ){
									?>
									<label for="">Usuario</label> 
									<?php 
										echo generar_select($link, "usuarios", "id_usuarios", "nombre_usuarios", false, false, true, 0 ,  0, "id_usuarios", "boletos_id_usuarios"); 
									}
									else{
									?>
									<input type="hidden"  step="any" id="boletos_id_usuarios" name="id_usuarios" class="form-control text-right" <?php echo $readonly;?>>
									
									<?php 
									}
								?>
								
							</div>
							
							<div class="form-group">
								<label for="">Pasajeros</label> 
								<input  class="form-control" type="text" name="pasajeros" id="pasajeros" > 
							</div>
							<div class="form-group" id="div_terminal" style="display:none">
								<label>Terminal:</label>
								<?php echo generar_select($link, "cat_terminales", "id_terminal", "terminal"); ?>
							</div>
						</div>
					</div>
				</div>
				<!-- Modal footer -->
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal">
					<i class="fas fa-times"></i> Cerrar</button>
					<button type="submit" id="btn_guardar_tarjeta" class="btn btn-success">
						<i class="fas fa-check"></i> Aceptar
					</button>
				</div>
				
			</div>
		</div>
	</div>
</form>
