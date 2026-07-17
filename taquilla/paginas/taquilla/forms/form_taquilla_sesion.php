<form class="was-validated " id="form_taquilla_sesion" autocomplete="off">
	<!-- The Modal -->
	<div class="modal fade" id="modal_taquilla_sesion">
		<div class="modal-dialog modal-dialog-centered modal-sm">
			<div class="modal-content">
				
				<!-- Modal Header -->
				<div class="modal-header">
					<h4 class="modal-title text-center">Elige Taquilla</h4>
					
				</div>
				
				<!-- Modal body -->
				<div class="modal-body">
					
					<div class="form-group">
						<label for="">Taquilla:</label>
						<?php echo generar_select($link, "taquillas", "id_taquilla", "nombre_taquilla", false, false, true, $_COOKIE["id_taquilla"], 0, "id_taquilla" , "sesion_id_taquillas")?>
					</div>
					
					
					
				</div>
				<!-- Modal footer -->
				<div class="modal-footer">
					
					<button type="submit" class="btn btn-outline-success"><i class="fa fa-save"></i> Aceptar</button>
				</div>
				
			</div>
		</div>
	</div>
</form>												