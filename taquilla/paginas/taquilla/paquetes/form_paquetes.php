<form id="form_paquetes" autocomplete="off" class="was-validated">
	<div class="modal " id="modal_paquetes">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Nuevo Paquete</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				
				<div class="modal-body">
					
					<div class="form-group">		
						<label >Corrida:</label>
						<select required class="form-control" id="paquete_id_corridas" name="id_corridas" required>
							<option value="">Elige...</option>
							<?php foreach($corridas as $corrida ){?>
								
								<option value="<?= $corrida["id_corridas"]?>">
									<?= "#".$corrida["id_corridas"]." ". date("d-m-Y", strtotime($corrida["fecha_corridas"]))." Eco: ".$corrida["num_eco"] ?>
									
								</option>
							<?php }?>
						</select>
					</div>
					<div class="form-group " >		
						<label >Taquilla Destino:</label>
						<select  class="form-control" name="id_taquilla" id="id_taquilla"  >
							<option value="4" >INDIOS VERDES</option>
							<option value="3" selected >CATEMACO</option>
						</select>	
					</div>
					<div class="form-group">		
						<label >Tamaño:</label>
						<select required class="form-control" id="tipo_paquete" name="tipo_paquete" required>
							<option value="">Elige...</option>
							<option data-precio="80">PEQUEÑO (30X25X25) $80</option>
							<option data-precio="140">MEDIANO (40X36X36) $140</option>
							<option data-precio="210">GRANDE (60X30X33) $210</option>
							<option data-precio="300">EXTRA GRANDE (70X80X80) $300</option>
							<option data-modificable="SI" data-precio="0"> EXCESO DE DIMENSIONES</option>
							
						</select>
						</div>
						<div class="form-group">		
						<label >Remitente:</label>
						<input class="form-control" type="text" name="remitente" id="remitente" required>
					</div>
					<div class="form-group">		
						<label >Nombre Destinatario:</label>
						<input class="form-control" type="text" name="destinatario" id="destinatario" required>
					</div>
					<div class="form-group">		
						<label >Contenido:</label>
						<input class="form-control" type="text" name="contenido" id="contenido" required>
					</div>
					<div class="form-group">		
						<label >Costo:</label>
						<input min="1" readonly class="form-control" type="number" name="costo" id="costo" required >
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
