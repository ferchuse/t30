
<!-- The Modal -->
<div class="modal fade" id="modal_ponchar" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			
			<!-- Modal Header -->
			<div class="modal-header">
				<h4 class="modal-title text-center">Ponchar Boletos</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			
			<!-- Modal body -->
			<div class="modal-body">
				
				<div class="form-group">
					<label for="boleto">Boleto</label>
					<input type="number" class="form-control" id="boleto" name="boleto"  required autofocus>
				</div>
				
				
				
				<form id="form_boletos">
					
					
					<div class="form-group">
						<label>Empresa:</label>
						<?php echo generar_select($link,"empresas", "id_empresas" ,"nombre_empresas", false, false, true )?>
					</div>
					
					<div class="form-group">
						<label>Beneficiario:</label>
						<?php echo generar_select($link,"beneficiarios", "id_beneficiarios" ,"nombre_beneficiarios", false, false, true )?>
					</div>
					
					<button class="btn btn-info  float-right " type="submit">
						<i class="fas fa-check"> </i> Generar Recibo
					</button>
					<hr>
					<input name="monto_recibo" id="monto_recibo" value="0" type="hidden">
					
					<div class="overflow" style="height: 200px; width: 100%; overflow: auto;" id="div_boletos_pochados">
						<table   class="table table-bordered table-condensed table-sm sticky">
							<thead class="sticky" style="position: sticky; top: 0; cursor: pointer;">
								
								<tr class="bg-primary">
									
									<th>Quitar</th>
									<th>Folio</th>
									<th>Fecha Venta</th>
									<th>Fecha Ponchado</th>
									<th>Destino</th>
									<th>Precio</th>
									
								</tr>
							</thead>
							<tbody id="tablaboletossencillos">
							</tbody>
							<tfoot >
								<tr class="bg-dark text-white">
									
									<th><span id="cant_boletos">0</span> Boletos</th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th><span id="total_boletos">$0</span></th>
								</tr>
							</tfoot>
							
							
						</table>
					</div>
					
					
				</form>
				
			</div>
			<!-- Modal footer -->
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
				
			</div>
			
		</div>
	</div>
</div>

