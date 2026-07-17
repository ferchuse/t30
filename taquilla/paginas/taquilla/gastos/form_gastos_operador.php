<form id="form_gasto_operador" autocomplete="off" class="was-validated">
	<div class="modal " id="modal_gasto_operador">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Nuevo Gasto</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				
				<div class="modal-body">
					
					<div class="form-group">		
						<label >Folio:</label>
						<input readonly type="number" class="text-right form-control" id="id_gasto" name="id_gasto" value="">
					</div>
					<div class="form-group">		
						<label >Fecha:</label>
						<input  type="date" class="form-control" id="fecha_gasto" name="fecha_gasto" value="<?php echo date("Y-m-d")?>">
					</div>
					<div class="form-group">		
						<label >Operador:</label>
						<?= generar_select($link, "conductores", "id_conductores", "nombre_conductores", false, false, true) ?>
					</div>
					<div class="form-group">		
						<label >Num Eco:</label>
						<?= generar_select($link, "unidades", "id_unidades", "num_eco", false, false, true) ?>
					</div>
					<div class="form-group">		
						<label >Concepto:</label>
						<?= generar_select($link, "cat_gastos", "id_cat_gastos", "descripcion_gastos", false, false, true, 7) ?>
					</div>
					<div class="form-group">		
						<label >Importe:</label>
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<span class="input-group-text" >$</span>
							</div>
							<input class="form-control text-right" type="number" name="monto_gasto" id="monto_gasto" required min="0" step="any" >
						</div>
						
					</div>
					<div class="form-group">		
						<label >Observaciones:</label>
						<input class="form-control" type="text" name="observaciones" id="observaciones" >
					</div>
					
					
					
				</div>
				
				<!-- Modal footer -->
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal">
					<i class="fas fa-times"></i> Cancelar</button>
					<button type="submit" class="btn btn-success " >
					<i class="fas fa-save"></i> Guardar </button>
				</div>
			</div>
		</div>
	</div>
</form>		
