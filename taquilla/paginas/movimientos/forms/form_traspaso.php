<form class="was-validated " id="form_salida">
	<!-- The Modal -->
	<div class="modal" id="modal_salida" data-backdrop="static">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				
				<!-- Modal Header -->
				<div class="modal-header">
					<h5 class="modal-title text-center"></h5>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				
				<!-- Modal body -->
				<div class="modal-body">
					
					
					<div class="form-group">
						<label for="fecha_aplicacion">Fecha Aplicación:</label>
						<input type="date" class="form-control" id="fecha_aplicacion" name="fecha_aplicacion" required value="<?= date("Y-m-d")?>">
					</div>
					<div class="form-group d-none">
						<label for="id_empresas">Empresa</label>
						
						<input class="form-control" type="text" readonly value="<?php echo $cat_empresas[$_COOKIE["empresa_asignada"]]?>">
						<input class="form-control" type="hidden" name="id_empresas" value="<?php echo $_COOKIE["empresa_asignada"]?>">
					</div>
					<div class="form-group">
						<label for="id_beneficiarios">Beneficiario</label>
						<?php echo generar_select($link, "beneficiarios", "id_beneficiarios", "nombre_beneficiarios",  false, false, true); ?>
					</div> 
					<div class="form-group">		
						<label >Categoria:</label>
						<?php echo generar_select($link, "cat_gastos", "id_cat_gastos", "descripcion_gastos") ?>
					</div>
					<div class="form-group">		
						<label >Forma de Pago:</label>
						<select required class="form-control" name="forma_pago" id="forma_pago">
							<option>Efectivo</option>
							<option>Transferencia</option>
						</select>
					</div>
					
					
					<div class="form-group">
						<label for="num_eco">Unidades:
							<button class="btn btn-success btn-sm" type="button" id="btn_agregar">
								<i class="fas fa-plus"></i>
								</button>
							</label>
							
							<div class="form-row" >
								<div class="col-2">
								<b for="">Num Eco:</b>
							</div>
							<div class="col-3"> 
								<b for="">Saldo:</b>
							</div>
							<div class="col-3">
								<b for="">Monto:</b>
							</div>
							<div class="col-3">
								<b for="">Saldo Restante:</b> 
							</div>
							
						</div>	
						<div id="unidades">
							<div class="form-row text-right">
								<div class="form-group col-2">
									<?php echo generar_select($link, "unidades", "num_eco", "num_eco",  false, false, true, 0,0,"num_eco[]", "", "num_eco" ); ?>
								</div>
								<div class="form-group col-3">
									<input tabindex="-1"  type="text" class="form-control saldo_actual text-right" name="saldo_anterior[]" readonly>
								</div>
								<div class="form-group col-3">
									<input type="number" step="any" class="form-control monto text-right" name="monto[]" required >
								</div>
								<div class="form-group col-3">
									<input tabindex="-1"  type="number" class="form-control saldo_restante text-right" name="saldo_restante[]" readonly>
								</div>
								<div class="col-1">
									<button class="btn btn-danger btn-sm quitar_unidad" type="button">
										<i class="fas fa-times"></i>
									</button>
								</div>
							</div>	
						</div>	
						
					</div>
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label for="importe_traspaso">Monto:</label>
								<input type="number" class="form-control text-right" id="importe_traspaso" name="importe_traspaso" required step="any" <?php echo dame_permiso(basename($_SERVER['PHP_SELF']), $link) == "Escritura" ? "readonly" : ""?>>
							</div> 
							
							
							<div class="form-group">
								<label for="observaciones_reciboSalidas">Observaciones</label>
								<input type="text" class="form-control mayus" id="observaciones" name="observaciones" required placeholder="Observaciones">
							</div>
						</div>
					</div>
					
					
					
				</div>
				<!-- Modal footer -->
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
					<button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Guardar</button>
				</div>
				
			</div>
		</div>
	</div>
</form>
