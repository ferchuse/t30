
<form id="form_pago" class="form" >
	<div id="modal_pago" class="modal fade" role="dialog">
		<div class="modal-dialog modal-sm"> 
			<!-- Modal content--> 
			<div class="modal-content">
				<div class="modal-header">
					
					<h4 class="modal-title text-center">Complemento de Pago</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				
				<div class="modal-body">
					
					
					<div class="form-group hidden">
						<input hidden type="text" required name="id_facturas" id="id_facturas" class="form-control" >
					</div>
					<div class="form-group ">
						<label>	Fecha de Pago:</label>
						<input  type="date" required name="fecha_pago" id="fecha_pago" class="form-control" value="<?php echo date("Y-m-d")?>">
					</div>
					<div class="form-group ">
						<label>Serie:</label>
						<input  type="text"  name="serie" id="serie" class="form-control" >
					</div>
					<div class="form-group ">
						<label>Folio:</label>
						<input  type="text"  name="folio" id="folio" class="form-control" >
					</div>
					<div class="form-group ">
						<label for="">Num Parcialidad:</label>
						<input  type="number" value="1" required name="num_parcialidad" id="num_parcialidad" class="form-control" >
					</div>
					<div class="form-group">
						<label for="id_niveles">Saldo Anterior:</label>
						<input  type="number" step="any"  readonly required name="saldo_anterior" id="saldo_anterior" class="form-control" >
					</div>
					<div class="form-group">
						<label for="id_niveles">Cantidad Pago:</label>
						<input  type="number" step="any"  required name="abono" id="abono" class="form-control" >
					</div>
					<div class="form-group">
						<label for="id_niveles">Saldo Restante:</label>
						<input  type="number" step="any" required name="saldo_restante" id="saldo_restante" class="form-control"  >
					</div>
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
					<div class="checkbox">
						<label >
							<input  type="checkbox" name="modo_pruebas" id="modo_pruebas"  >
							Modo Pruebas 
						</label>
						
					</div>
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
				
				<div class="modal-footer">
					
					<button type="button" class="btn btn-danger" data-dismiss="modal">
						<i class="fa fa-times"></i> Cancelar
					</button>
					<button type="submit" class="btn btn-success">
						<i class="fa fa-save" ></i> Guardar
					</button>
					
				</div>
				
			</div>
		</div>
	</div>
</form>
