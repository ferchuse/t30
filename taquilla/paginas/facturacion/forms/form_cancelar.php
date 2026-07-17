<form id="form_cancelar" class="form" >
			<div id="modal_cancelar" class="modal fade" role="dialog">
				<div class="modal-dialog modal-sm"> 
					<!-- Modal content--> 
					<div class="modal-content">
						<div class="modal-header">
							
							<h4 class="modal-title text-center">
								Cancelar Factura
							</h4>
							
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>
						
						<div class="modal-body">
							<input  type="hidden"  name="id_facturas" id="cancelar_id_facturas" >
							<input  type="hidden"  name="id_emisores" id="cancelar_id_emisores"  >
							
							
							<div class="form-group">
								<label for="cancelar_uuid">Folio Fiscal:</label>
								<input  type="text" readonly name="uuid" id="cancelar_uuid" class="form-control" >
							</div>
							<div class="form-group">
								<label for="motivo">Motivo:</label>
								<select id="motivo_cancelacion" required name="motivo_cancelacion" class="form-control">
									<option value="">Seleccione...</option>
									<option value="01">"01" Comprobante emitido con errores con relación</option>
									<option value="02">"02" Comprobante emitido con errores sin relación</option>
									<option value="03">"03" No se llevó a cabo la operación</option>
									<option value="04">"04" Operación nominativa relacionada en la factura global</option>
								</select>
							</div>
							<div class="form-group">
								<label for="folio_sustituye">Folio que sustituye(UUID):</label>
								<input  type="text" placeholder="Obligatorio solo si Motivo 01" name="folio_sustituye" id="folio_sustituye" class="form-control" >
							</div>
							
						</div>
						
						<div class="modal-footer">
							
							<button type="button" class="btn btn-danger" data-dismiss="modal">
								<i class="fa fa-times"></i> Cerrar
							</button>
							<button type="submit" class="btn btn-success">
								<i class="fa fa-check" ></i> Aceptar
							</button>
							
						</div>
						
					</div>
				</div>
			</div>
		</form>