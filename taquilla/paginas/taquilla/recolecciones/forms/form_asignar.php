
<form class="was-validated " id="form_asignar" autocomplete="off">
	<!-- The Modal -->
	<div class="modal fade" id="modal_asignar" data-backdrop="static">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				
				<!-- Modal Header -->
				<div class="modal-header">
					<h4 class="modal-title text-center">Asignar Unidad</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				
				<!-- Modal body -->
				<div class="modal-body">
					<div class="form-group">
						<label for="">Folio:</label> <br>
						<input  class="form-control" type="number" name="id_recoleccion" readonly id="asignar_id_recoleccion"> 
					</div>
					
					<div class="form-group">
						<label for="">Num Eco: </label> 
						<?php  echo generar_select($link, "unidades", "num_eco", "num_eco", false, false, true, 0 ,  0, "num_eco", "asignar_num_eco"); ?>
						
					</div>
					
					<div class="form-group">
						<label >Operador:</label>
						<?php  echo generar_select($link, "conductores", "id_conductores", "nombre_conductores", false, false, true,0 ,  0); ?>
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
