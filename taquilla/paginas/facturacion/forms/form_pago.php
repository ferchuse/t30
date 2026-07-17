
<form id="form_pago" class="form" >
	<div id="modal_pago" class="modal fade" role="dialog" data-backdrop="static">
		<div class="modal-dialog modal-lg"> 
			<!-- Modal content--> 
			<div class="modal-content">
				<div class="modal-header">
					
					<h4 class="modal-title text-center">Complemento de Pago</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				
				<div class="modal-body">
					
					
					
					<div class="row">
						<div class="col-sm-4">
							<div class="hidden ">
								<input  type="hidden"  name="id_facturas" id="id_facturas" class="form-control" >
							</div>
							<div class="form-group ">
								<label>	Fecha de Pago:</label>
								<input  type="date" required name="fecha_pago" id="fecha_pago" class="form-control" value="<?php echo date("Y-m-d")?>">
							</div>
							<div class="form-group ">
								<label>Serie:</label>
								<input  type="text"  name="serie" id="serie" class="form-control" readonly >
							</div>
							<div class="form-group ">
								<label>Folio:</label>
								<input  type="text"  name="folio" id="folio" class="form-control" readonly>
							</div>
							
							
						</div>
						<div class="col-sm-4">
							
							<div class="form-group">
								<label class="control-label" for="forma_pago">Forma de Pago:</label>
								<select id="forma_pago" name="forma_pago" class="form-control" >
									<option value="">Seleccione...</option>
									<option value="01" >01 Efectivo</option>
									<option value="02">02 Cheque nominativo</option>
									<option selected value="03" >03 Transferencia electrónica de fondos</option>
									<option value="04">04 Tarjeta de crédito</option>
									<option value="06">Dinero Electrónico</option>
									<option value="28" >28 Tarjeta de débito</option>
									<option value="29" >29 Tarjeta de servicios</option>
									<option  value="99" >99 Por definir</option>
								</select>
							</div>
						</div>
						
						<div class="col-sm-4">
							<div class="form-group ">
								<label for="">Num Parcialidad:</label>
								<input  type="number" value="1" required name="num_parcialidad" id="num_parcialidad" class="form-control" >
							</div>
							<div class="form-group">
								<label class="control-label" for="tipo_factor">Tipo Factor:</label>
								<select id="tipo_factor" name="tipo_factor" class="form-control" >
									<option value="">Seleccione...</option>
									<option selected value="Tasa" >Tasa</option>
									<option value="Cuota" >Cuota</option>
									<option value="Exento">Exento</option>
								</select>
							</div>
							<div class="form-group">
								<label class="control-label" for="tasa_iva">Tasa IVA:</label>
								<select id="tasa_iva" name="tasa_iva" class="form-control" >
									<option value="0.000000">0%</option>
									<option selected value="0.160000">16%</option>
									
								</select>
							</div>
						</div>
						
						
						
					</div>
					
					<div class="row">
						<div class="col-sm-12">
							<caption>Documentos Relacionados</caption>
							<table class="table table-sm table-bordered">
								
								<thead>
									<tr>
										<td>Folio</td>
										<td>Saldo Anterior</td>
										<td>Pago </td>
										<td>Saldo Restante </td>
									</tr>
								</thead>
								<tbody id="dctos_relacionados">
									
								</tbody>
								<tfoot >
									<tr >
										<td >
											<h4>Totales:  </h4>
										</td>
										<td >
											
											<input  type="number" step="any"  required name="saldo_anterior" id="saldo_anterior" class="form-control text-right" >
										</td>
										<td >
											
											<input  type="number" step="any"  required name="abono" id="abono" class="form-control text-right" >
										</td>
										<td >
											
											<input  type="number" step="any" required name="saldo_restante" id="saldo_restante" class="form-control text-right" readonly >
										</td>
									</tr>
									
								</tfoot>
							</table>
							
							
							<div id="mensaje_error" class="alert alert-danger d-none">
								
							</div>
							<div id="mensaje_timbrado" class="alert alert-success d-none">
								Facturando <i class="fa fa-spinner fa-spin"></i>
							</div>
							<div id="mensaje_pdf" class="alert alert-success d-none">
								Generando PDF <i class="fa fa-spinner fa-spin"></i>
							</div>
							<div id="mensaje_correo" class="alert alert-success d-none">
								Enviando Correo <i class="fa fa-spinner fa-spin"></i>
							</div>
							<pre id="debug" hidden>
							</pre>
						</div>
					</div>
				</div>
				
				<div class="modal-footer">
					<div class="checkbox">
						<label >
							<input  type="checkbox" name="modo_pruebas" id="pago_modo_pruebas"  >
							Modo Pruebas 
						</label>
						
					</div>
					<button type="button" class="btn btn-danger" data-dismiss="modal">
						<i class="fa fa-times"></i> Cerrar
					</button>
					<button type="submit" class="btn btn-success">
						<i class="fa fa-save" ></i> Guardar
					</button>
					
				</div>
			</div>
		</div>
	</div>
</form>
