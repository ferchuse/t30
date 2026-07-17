<form class="was-validated " id="form_salida" autocomplete>
	<!-- The Modal -->
	<div class="modal fade" id="modal_salida">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				
				<!-- Modal Header -->
				<div class="modal-header">
					<h5 class="modal-title text-center"></h5>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				
				<!-- Modal body -->
				<div class="modal-body">
					<input type="text" hidden class="form-control" id="id_deposito" name="id_deposito">
					
					<div class="form-group">
						<label for="fecha_aplicacion">Fecha Aplicación:</label>
						<input type="date" class="form-control" id="fecha_aplicacion" name="fecha_aplicacion" required value="<?= date("Y-m-d")?>">
					</div>
					<div class="form-group d-none">
						<label for="id_empresas">EMPRESA</label>
						
						<input class="form-control" type="text" readonly value="<?php echo $cat_empresas[$_COOKIE["empresa_asignada"]]?>">
						<input class="form-control" type="hidden" name="id_empresas" value="<?php echo $_COOKIE["empresa_asignada"]?>">
					</div>
										
					<div class="form-group">
						<label for="Num Eco">Operador:</label>
						<?php echo generar_select($link, "conductores", "id_conductores", "nombre_conductores",  false, false, true); ?>
					</div> 			
					<div class="form-group">
						<label for="Num Eco">Num Eco:</label>
						<?php echo generar_select($link, "unidades", "num_eco", "num_eco",  false, false, true); ?>
					</div> 

					<div class="form-group">
						<label for="id_motivo_entrada">Motivo:</label>
						<?php echo generar_select($link, "motivos_entrada", "id_motivo_entrada", "motivo",  false, false, true); ?>
					</div> 
					<div class="form-group">
						<label for="concepto">Concepto:</label>
						<input type="text" class="form-control" id="concepto" name="concepto" required>
					</div> 
					<div class="form-group">
						<label for="monto">MONTO</label>
						<input type="number" class="form-control" id="monto" name="monto" required step="any">
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
