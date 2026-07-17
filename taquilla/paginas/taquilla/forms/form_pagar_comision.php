


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
					
					
					<div class="form-group row align-items-center section-row">
						<div class="col">
							Num Eco
						</div>
						<div class="col">
							Monto
						</div>
						<div class="col">
							Comisión
						</div>
						
						<div class="col">
							Borrar
						</div>
					</div>
					<div id="lista_unidades">
						
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

<!-- plantilla para filas de texto -->
<template id="plantilla_num_eco">
	<div class="form-group row align-items-center section-row">
		<div class="col">
			<input type="text" class="form-control num_eco" placeholder="" name="num_eco[]">
		</div>
		<div class="col">
			<input type="text" class="form-control monto" placeholder="" name="monto[]">
		</div>
		
		<div class="col">
			<input type="text" class="form-control comision" placeholder="" name="comision_unidad[]">
		</div>
		
		<div class="col">
			<button type="button" class="btn btn-link text-danger btn_borrar" tabindex="-1">
				<i class="fas fa-trash"></i>
			</button>
		</div>
	</div>
</template>
