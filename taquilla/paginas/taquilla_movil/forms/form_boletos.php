<form class="was-validated " id="form_boleto" autocomplete="off">
	<!-- The Modal -->
	<div class="modal fade" id="modal_boleto">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				
				<!-- Modal Header -->
				<div class="modal-header">
					<h4 class="modal-title text-center"></h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				
				<!-- Modal body -->
				<div class="modal-body">
					
					
					
					<div class="form-group">
						<label for="">Num Asiento</label> <br>
						<input id="num_asiento" class="form-control" type="number" readonly name="num_asiento" > 
						
					</div>
					<div class="form-group">
						<label for=""><input id="niño" required  class="tipo_boleto" type="radio" name="tipo_boleto" data-precio="250" value="Niño"> Niño	 $250</label> <br>
						<label for=""><input id="adulto" required class="tipo_boleto"  type="radio" name="tipo_boleto" data-precio="280" value="Adulto"> Adulto $280</label> <br>
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
