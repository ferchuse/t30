<form class="was-validated " id="form_edicion" autocomplete="off">
	<!-- The Modal -->
	<div class="modal fade" id="modal_edicion">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				
				<!-- Modal Header -->
				<div class="modal-header">
					<h4 class="modal-title text-center"></h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				
				<!-- Modal body -->
				<div class="modal-body">
					<div class="row">
						<div class="col-6">
							<input type="text" hidden class="form-control" id="id_conductores" name="id_conductores">
							<div class="form-group">
								<label for="nombre_conductores">Nombre Completo:</label>
								<input type="text" class="form-control" id="nombre_conductores" name="nombre_conductores"  required>
							</div>
							<div class="form-group d-none">
								<label for="id_empresas">EMPRESA</label>
								<input id="id_empresas" value="2">
							</div> 
							<div class="form-group">
								<label for="rfc_conductores">RFC</label>
								<input type="text" class="form-control" id="rfc_conductores" name="rfc_conductores" >
							</div> 
							<div class="form-group">
								<label for="noLicencia_conductores">Tipo de Licencia</label>
								<input type="text" class="form-control" id="tipo_licencia" name="tipo_licencia" >
							</div>
							<div class="form-group">
								<label for="noLicencia_conductores">Folio Licencia:</label>
								<input type="text" class="form-control" id="noLicencia_conductores" name="noLicencia_conductores" >
							</div>
							
							
							<div class="form-group">
								<label for="curp_conductores">CURP</label>
								<input type="text" class="form-control" id="curp_conductores" name="curp_conductores" >
							</div> 
							<div class="form-group">
								<label >Estatus:</label>
								
								<select class="form-control" id="estatus_conductores" name="estatus_conductores">
									<option value="">Seleccione</option>
									<option selected value="Activo">Activo</option>
									<option value="Inactivo">Inactivo</option>
									
								</select>
							</div>
							
						</div>
						<div class="col-6">
							<div class="form-group ">
								<button type="button" class="upload_widget btn btn-success" class="">
									<i class="fas fa-upload"></i> Licencia
								</button>
								
								<img  class="img-thumbnail" src="../../img/blank_image.png">
								
								<a target="_blank" href="" class="link" ></a>
								
								<input  type="hidden" class="url"  name="foto_licencia" id="foto_licencia">
							</div>
							
							<div class="form-group">
								<label for="fechaVigencia_conductores">Vigencia Licencia:</label>
								<input type="date" class="form-control" id="fechaVigencia_conductores" name="fechaVigencia_conductores" value="0000-00-00">
							</div> 
							
							<div class="form-group ">
								<button type="button" class="upload_widget btn btn-success" class="">
									<i class="fas fa-upload"></i> Certificado Médico
								</button>
								
								<img  class="img-thumbnail" src="../../img/blank_image.png">
								
								<a target="_blank" href="" class="link" ></a>
								
								<input  type="hidden" class="url"  name="foto_certificado" id="foto_certificado">
							</div>
							<div class="form-group">
								<label for="vigencia_certificado">Vigencia Certificado:</label>
								<input type="date" class="form-control" id="vigencia_certificado" name="vigencia_certificado" value="0000-00-00">
							</div> 
							
							<div class="form-group ">
								<button type="button" class="upload_widget btn btn-success" class="">
									<i class="fas fa-upload"></i> Curso SCT
								</button>
								
								<img  class="img-thumbnail" src="../../img/blank_image.png">
								
								<a target="_blank" href="" class="link" ></a>
								
								<input  type="hidden" class="url"  name="foto_curso" id="foto_curso">
							</div>
							
							<div class="form-group">
								<label for="vigencia_curso">Vigencia Curso SCT:</label>
								<input type="date" class="form-control" id="vigencia_curso" name="vigencia_curso" value="0000-00-00">
							</div> 
							
							<div class="form-group">
								<label for="vigencia_curso">Tag:</label>
								<input type="text" class="form-control" id="tag_operador" name="tag_operador" >
							</div> 
							
						</div>
					</div>
				</div>
				<!-- Modal footer -->
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
					<button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Guardar</button>
				</div>
				
			</div>
		</div>
	</div>
</form>
