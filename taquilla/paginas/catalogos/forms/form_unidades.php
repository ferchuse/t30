<form id="form_edicion" autocomplete="off" class="was-validated" >
	<div class="modal " id="modal_edicion" data-backdrop="static">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Edición de Unidad</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				
				<div class="modal-body">
					<div class="row mb-2">
						<div class="col-sm-2">
							<label >No Eco:</label>
						</div>	
						<div class="col-sm-5">			
							<input class="form-control" type="number" name="num_eco" id="num_eco" required>
						</div>
						
						<div class="col-sm-5">			
							<div class="form-group">
								<button type="button" class="upload_widget btn btn-success" class="">
									<i class="fas fa-upload"></i> Poliza
								</button>
								
								<img  class="img-thumbnail " src="../../img/blank_image.png">
								
								<a target="_blank" href="" class="link" ></a>
								
								<input  type="hidden" class="url"  name="foto_poliza" id="foto_poliza">
							</div>
							
						</div>
						
					</div>
					<div class="row mb-2">
						<div class="col-sm-2">
							<label class="d-none" >Empresa:</label>
						</div>	 
						<div class="col-sm-5">			
							<input class="form-control d-none" type="text" readonly value="<?php echo $cat_empresas[$_COOKIE["empresa_asignada"]]?>">
							<input class="form-control d-none" type="hidden" name="id_empresas" value="<?php echo $_COOKIE["empresa_asignada"]?>">
						</div>
						<div class="col-sm-5">			
							<div class="form-group ">
								<button type="button" class="upload_widget btn btn-success" class="">
									<i class="fas fa-upload"></i> Tarjeta de Circulación
								</button>
								
								<img  class="img-thumbnail" src="../../img/blank_image.png">
								
								<a target="_blank" href="" class="link" ></a>
								
								<input  type="hidden" class="url"  name="foto_tarjeta" id="foto_tarjeta">
							</div>
						</div>
						
						
					</div>
					<div class="row mb-2">
						<div class="col-sm-2">
							<label >Propietario:</label>
						</div>	 
						<div class="col-sm-5">			
							<?php
								echo generar_select($link, "propietarios", "id_propietarios", "nombre_propietarios", false, false, true);
							?>
						</div>
						<div class="col-sm-5">			
							<div class="form-group ">
								<button type="button" class="upload_widget btn btn-success" class="">
									<i class="fas fa-upload"></i> Factura
								</button>
								
								<img  class="img-thumbnail" src="../../img/blank_image.png">
								
								<a target="_blank" href="" class="link" ></a>
								
								<input  type="hidden" class="url"  name="foto_factura" id="foto_factura">
							</div>
						</div>
						
						
					</div>
					
					<div class="row mb-2">
						<div class="col-sm-2">
							<label for="nombre_propietario">Fecha de Alta:</label>
						</div>	
						<div class="col-sm-5">			
							<input class="form-control" type="date" name="fecha_ingreso" id="fecha_ingreso" value="<?php echo date("Y-m-d");?>">
						</div>
						
						<div class="col-sm-5">			
							<div class="form-group ">
								<button type="button" class="upload_widget btn btn-success" class="">
									<i class="fas fa-upload"></i> Foto Unidad
								</button>
								
								<img  class="img-thumbnail" src="../../img/blank_image.png">
								
								<a target="_blank" href="" class="link" ></a>
								
								<input  type="hidden" class="url"  name="foto_unidad" id="foto_unidad">
							</div>
						</div>
					</div>
					<div class="row mb-2">
						<div class="col-sm-2">
							<label >Tipo Vehículo:</label>
						</div>	
						<div class="col-sm-5">			
							<select class="form-control" id="tipo_unidad" name="tipo_unidad">
								<option value="">Seleccione</option>
								<option value="Sedan (4 pasajeros)">Sedan (4 pasajeros)</option>
								<option value="Ertiga (6 pasajeros)">Ertiga (6 pasajeros)</option>
								<option value="Suburvan (7 pasajeros)">Suburvan (7 pasajeros)</option>
								<option value="NV (12 pasajeros)">NV (12 pasajeros) </option>
								<option  value="JAC Sunray (16 pasajeros)">JAC Sunray (16 pasajeros)</option>
								<option value="Crafter (18 pasajeros)">Crafter (18 pasajeros)</option>
								<option value="Sprinter (22 pasajeros)">Sprinter (22 pasajeros)</option>
							</select>
						</div>
					</div>
					<div class="row mb-2">
						<div class="col-sm-2">
							<label >Estatus:</label>
						</div>	
						<div class="col-sm-5">			
							<select class="form-control" id="estatus_unidades" name="estatus_unidades">
								<option value="">Seleccione</option>
								<option selected value="Activo">Activo</option>
								<option value="Inactivo">Inactivo</option>
								
							</select>
						</div>
					</div>
					
					<div class="row mb-2">
						<div class="col-sm-2">
							<label for="placas">Placas:</label>
						</div>	
						<div class="col-sm-5">			
							<input  class="form-control placa" type="text" name="placas" id="placas">
						</div>
					</div>
					
					<div class="row mb-2">
						<div class="col-sm-2">
							<label for="nombre_propietario">Serie:</label>
						</div>	
						<div class="col-sm-5">			
							<input class="form-control" type="text" name="serie" id="serie">
						</div>
					</div>
					<div class="row mb-2">
						<div class="col-sm-2">
							<label for="nombre_propietario">Modelo:</label>
						</div>	
						<div class="col-sm-5">			
							<input class="form-control" type="text" name="modelo" id="modelo">
						</div>
					</div>
					<div class="row mb-2">
						<div class="col-sm-2">
							<label for="nombre_propietario">Poliza:</label>
						</div>	
						<div class="col-sm-5">			
							<input class="form-control" type="text" name="poliza" id="poliza" required>
						</div>
					</div>
					<div class="row mb-2">
						<div class="col-sm-2">
							<label for="nombre_propietario">Aseguradora:</label>
						</div>	
						<div class="col-sm-5">			
							<input class="form-control" type="text" name="aseguradora" id="aseguradora">
						</div>
					</div>
					<div class="row mb-2">
						<div class="col-sm-2">
							<label for="nombre_propietario">Vigencia:</label>
						</div>	
						<div class="col-sm-5">			
							<input class="form-control" type="date" name="vigencia" id="vigencia">
						</div>
					</div>
					<div class="row mb-2">
						<div class="col-sm-2">
							<label for="tag">Tag:</label>
						</div>	
						<div class="col-sm-5">			
							<input class="form-control" type="text" name="tag" id="tag">
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
