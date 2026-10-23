<form class="was-validated " id="form_cliente" autocomplete="off">
	<div class="modal fade" id="modalCliente" data-backdrop="static">
		
		<div class="modal-dialog">
			
			<div class="modal-content">
				
				<div class="modal-header bg-primary text-white">
					
					<h5 class="modal-title">
						Nuevo cliente
					</h5>
					
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					
				</div>
				
				<div class="modal-body">
					
					<div class="row">
						
						<div class="col-md-12 mb-2">
							<label>Nombre <span class="text-danger">* </span></label>
							<input
							type="text"
							class="form-control mayusculas"
							id="nombre_cliente"
							name="nombre_cliente"
							required>
						</div>
						
						<div class="col-md-6 mb-2">
							<label>Apellido paterno</label>
							<input
							type="text"
							class="form-control mayusculas"
							id="apellidop_cliente"
							name="apellidop_cliente">
						</div>
						
						<div class="col-md-6 mb-2">
							<label>Apellido materno</label>
							<input
							type="text"
							class="form-control mayusculas"
							id="apellidom_cliente"
							name="apellidom_cliente">
						</div>
						
						<div class="col-md-6 mb-2">
							<label>RFC</label>
							<input
							type="text"
							class="form-control mayusculas"
							id="rfc_cliente"
							name="rfc_cliente">
						</div>
						
						<div class="col-md-6 mb-2">
							<label>Teléfono <span class="text-danger">* </span></label>
							<input
							type="tel"
							class="form-control"
							id="telefono_cliente"
							name="telefono_cliente"
							required>
						</div>
						
						<div class="col-md-12 mb-2">
							<label>Correo</label>
							<input
							type="email"
							class="form-control"
							id="correo_cliente"
							name="correo_cliente">
						</div>
						
						
					</div>
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal">
						<i class="fa fa-times"></i> Cancelar
					</button>
					<button type="submit" class="btn btn-success">
						<i class="fa fa-save"></i> Guardar
					</button>
				</div>
				
			</div>
			
		</div>
		
	</div>
</form>												