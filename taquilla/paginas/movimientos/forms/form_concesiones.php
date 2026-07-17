<form id="form_edicion" autocomplete="off" class="was-validated">
	<div class="modal " id="modal_edicion">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Edición de Unidad</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				
				<div class="modal-body">
					<div class="row mb-1">
						<div class="col-2">
							<label >No Eco:</label>
						</div>	
						<div class="col-5">			
							<input class="form-control" type="number" name="num_eco" id="num_eco" required>
						</div>
					</div>
					<div class="row mb-1">
						<div class="col-2">
							<label >Empresa:</label>
						</div>	 
						<div class="col-5">			
							<?php
								echo generar_select($link, "empresas", "id_empresas", "nombre_empresas", false, false, true);
							?>
						</div>
					</div>
					
					<div class="row mb-1">
						<div class="col-2">
							<label for="asientos">Asientos:</label>
						</div>	
						<div class="col-5">			
							<input class="form-control" type="number"  name="asientos" id="asientos" >
						</div>
					</div>
					<div class="row mb-1">
						<div class="col-2">
							<label >Propietario:</label>
						</div>	
						<div class="col-5">			
							<?php
								echo generar_select($link, "propietarios", "id_propietarios", "nombre_propietarios");
							?>
						</div>
					</div>
					<div class="row mb-1">
						<div class="col-2">
							<label >Derrotero:</label>
						</div>	
						<div class="col-5">			
							<?php
								echo generar_select($link, "derroteros", "id_derroteros", "nombre_derroteros");
							?>
						</div>
					</div>
					<div class="row mb-1">
						<div class="col-2">
							<label for="nombre_propietario">Fecha de Alta:</label>
						</div>	
						<div class="col-5">			
							<input class="form-control" type="date" name="fecha_ingreso" id="fecha_ingreso" value="<?php echo date("Y-m-d");?>">
						</div>
					</div>
					<div class="row mb-1">
						<div class="col-2">
							<label >Tipo Vehículo:</label>
						</div>	
						<div class="col-5">			
							<select class="form-control" id="tipo_unidad" name="tipo_unidad">
								<option value="">Seleccione</option>
								<option value="Autobús">Autobús</option>
								<option value="Camioneta">Camioneta</option>
								<option value="Sprinter">Sprinter</option>
							</select>
						</div>
					</div>
					<div class="row mb-1">
						<div class="col-2">
							<label >Estatus:</label>
						</div>	
						<div class="col-5">			
							<select class="form-control" id="estatus_unidades" name="estatus_unidades">
								<option value="">Seleccione</option>
								<option selected value="Alta">Alta</option>
								<option value="Inactivo">Inactivo</option>
							</select>
						</div>
					</div>
					<div class="row mb-1">
						<div class="col-2">
							<label >Tiene Placas:</label>
						</div>	
						<div class="col-5">			
							<select class="form-control " id="tiene_placas" name="tiene_placas">
								<option value="NO">NO</option>
								<option value="SI">SI</option>
							</select>
						</div>
					</div>
					<div class="row mb-1">
						<div class="col-2">
							<label >Tipo de Placa:</label>
						</div>	
						<div class="col-5">			
							<select disabled class="form-control placa" id="tipo_placas" name="tipo_placas">
								<option value="">Seleccione...</option>
								<option value="ESTATAL">ESTATAL</option>
								<option value="FEDERAL">FEDERAL</option>
							</select>
						</div>
					</div>
					<div class="row mb-1">
						<div class="col-2">
							<label for="placas">Placas:</label>
						</div>	
						<div class="col-5">			
							<input disabled class="form-control placa" type="text" name="placas" id="placas">
						</div>
					</div>
					<div class="row mb-1">
						<div class="col-2">
							<label for="">Razon Social:</label>
						</div>	
						<div class="col-5">			
							<input disabled class="form-control placa" type="text" name="razon_social" id="razon_social">
						</div>
					</div>
					<div class="row mb-1">
						<div class="col-2">
							<label for="nombre_propietario">Serie:</label>
						</div>	
						<div class="col-5">			
							<input class="form-control" type="text" name="serie" id="serie">
						</div>
					</div>
					<div class="row mb-1">
						<div class="col-2">
							<label for="nombre_propietario">Modelo:</label>
						</div>	
						<div class="col-5">			
							<input class="form-control" type="text" name="modelo" id="modelo">
						</div>
					</div>
					<div class="row mb-1">
						<div class="col-2">
							<label for="nombre_propietario">Poliza:</label>
						</div>	
						<div class="col-5">			
							<input class="form-control" type="text" name="poliza" id="poliza" required>
						</div>
					</div>
					<div class="row mb-1">
						<div class="col-2">
							<label for="nombre_propietario">Aseguradora:</label>
						</div>	
						<div class="col-5">			
							<input class="form-control" type="text" name="aseguradora" id="aseguradora">
						</div>
					</div>
					<div class="row mb-1">
						<div class="col-2">
							<label for="nombre_propietario">Vigencia:</label>
						</div>	
						<div class="col-5">			
							<input class="form-control" type="date" name="vigencia" id="vigencia">
						</div>
					</div>
					<div class="row mb-1" hidden>
						<div class="col-2">
							<label for="nombre_propietario">Rin:</label>
						</div>	
						<div class="col-5">			
							<input class="form-control" type="text" name="rin" id="rin" placeholder="Opcional">
						</div>
					</div>
					<div class="row mb-1" hidden>
						<div class="col-2">
							<label for="nombre_propietario">Tipo de Aceite:</label>
						</div>	
						<div class="col-5">			
							<input class="form-control" type="text" name="tipo_aceite" id="tipo_aceite" placeholder="Opcional">
						</div>
					</div>
					<div class="row mb-1" >
						<div class="col-2">
							<label for="nombre_propietario">Concesión:</label>
						</div>	
						<div class="col-5">			
							<input class="form-control" type="text" name="concesion" id="concesion" required>
						</div>
					</div>
					<div class="row mb-1">
						<div class="col-2">
							<label for="vigencia_concesion">Vigencia Concesión:</label>
						</div>	
						<div class="col-5">			
							<input class="form-control" type="date" name="vigencia_concesion" id="vigencia_concesion" required>
						</div>
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
