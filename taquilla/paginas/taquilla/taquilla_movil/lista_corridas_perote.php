<?php 
	
	include('../../../conexi.php');
	include('../../../funciones/generar_select.php');
	include('../../../funciones/console_log.php');
	$link = Conectarse();
	$corrida = array();
	$respuesta = array();
	
	
	function dame_permiso($url_paginas,$link){
		
		// return false;
		$consulta = "SELECT * FROM permisos LEFT JOIN paginas USING(id_paginas) 
		WHERE url_paginas = '$url_paginas' 
		AND id_usuarios = {$_COOKIE["id_usuarios"]}";
		
		
		$result = mysqli_query($link, $consulta) or die("Error dame_permiso($consulta) ". mysqli_error($link));
		
		if(mysqli_num_rows($result) > 0){
			while($fila = mysqli_fetch_assoc($result)){
				
				$respuesta= $fila["permiso"];
			}
			
			if($respuesta == "Sin Acceso"){
				return "hidden"; 
			}
			else{
				return $respuesta;
			}
			
			
		}
		else{
			
			return false;//"Pagina no existe, $url_paginas,{$_COOKIE["id_usuarios"]}, $consulta";
		}
		
	}
	
	
	$consulta = "SELECT * FROM corridas_perote 
	
	LEFT JOIN taquillas_perote USING(id_taquilla)
	LEFT JOIN unidades USING(num_eco)
	LEFT JOIN empresas ON corridas.id_empresas = empresas.id_empresas
	LEFT JOIN origenes USING(id_origenes)
	LEFT JOIN (
	SELECT id_origenes AS id_destinos, 
	nombre_origenes AS nombre_destinos 
	FROM origenes ) AS t_destinos 
	USING(id_destinos)
	LEFT JOIN usuarios USING(id_usuarios)
	LEFT JOIN (
	SELECT id_corridas, SUM(precio_boletos) AS importe_corridas
	FROM boletos WHERE estatus_boletos <> 'Cancelado' GROUP BY id_corridas
	) AS t_importes USING(id_corridas)
	WHERE 1
	
	
	AND date(fecha_corridas) BETWEEN '{$_GET["fecha_inicial"]}' AND '{$_GET["fecha_final"]}'
	";
	
	
	if($_GET["id_usuarios"] != ""){
		$consulta.="AND corridas.id_usuarios = '{$_GET["id_usuarios"]}'";
	}
	if($_GET["id_empresas"] != ""){
		$consulta.="AND corridas.id_empresas = '{$_GET["id_empresas"]}'";
	}	
	if($_GET["num_eco"] != ""){
		$consulta.="AND corridas.num_eco = '{$_GET["num_eco"]}'";
	}
	
	//Si la taquilla es indios verdes solo ostrar corridas de indios verdes
	if($_GET["id_taquilla"] == "INDIOS VERDES"){
		$consulta.="AND corridas.id_taquilla = '4'";
	}
	elseif($_GET["id_taquilla"] != "INDIOS VERDES" && $_GET["id_taquilla"] != "" ){
		//si la taquilla es diferente de IV y diferente de "Todos" mostrar CABADA, SAn andres y catemaco
		
		$consulta.=" AND corridas.id_taquilla <> '4'";
	}
	
	
	
	$consulta.="
	ORDER BY id_corridas DESC "
	;
	
	
	$result = mysqli_query($link,$consulta);
	if($result){
		
		if( mysqli_num_rows($result) == 0){
			die("<div class='alert alert-danger'>No hay registros 	</div> ");
			
		}
		
		while($row = mysqli_fetch_assoc($result)){
			
			$corridas[] = $row ;
		}
		
		
		foreach($corridas as $i=>$corrida){
			
		?> 
		<div class="row mb-5"> 
			<div class="col-6">
				<div class="text-success h4">#<?php echo $corrida["id_corridas"]?></div>
				
				<span class="text-primery h5"><i class="fas fa-bus"></i> <?php echo $corrida["num_eco"]?></span><br>
				</br>
				<?php echo date("d-m-Y", strtotime($corrida["fecha_corridas"]))?></br>
				<?php echo $corrida["hora_corridas"]?></br>
				
				<b>Origen:</b><br> <?php echo $corrida["origen"]?><i class="fas fa-arrow-right"></i></br>
				<b>Destino:</b><br><?php echo $corrida["destino"]?>
			</div>
			<div class="col-6">
				<?php
					if($corrida["estatus_corridas"] == "Activa"){
						echo "<span class='badge badge-success'>".$corrida["estatus_corridas"]."</span>";
					}
					elseif($corrida["estatus_corridas"] == "Cancelada"){
						echo "<span class='badge badge-danger'>".$corrida["estatus_corridas"]."</span>";
						echo "<small>".$corrida["datos_cancelacion"]."</small>";
					}
				?>
				<div class="btn-group-vertical">
					<?php
						switch($corrida["estatus_corridas"]){
							case "Activa":
							
						?>
						
						<button class="btn btn-success  btn-sm btn_venta" title="Venta de Boletos" 
						data-id_corridas="<?php echo $corrida["id_corridas"]?>"
						data-num_eco="<?php echo $corrida["num_eco"]?>"
						data-asientos="<?php echo $corrida["asientos"]?>"
						>
							<i class="fas fa-ticket-alt"></i> Venta de Boletos
						</button>
						<button class="btn btn-primary  btn-sm finalizar_corrida" title="Finalizar Corrida" 
						data-id_registro="<?php echo $corrida["id_corridas"]?>">
							<i class="fas fa-check"></i> Finalizar Corrida
						</button>
						
						<?php
							break;
							
							case "Finalizada":
							
							echo "<span class='badge badge-warning'>".$corrida["estatus_corridas"]."</span>";
							echo "<span class='badge badge-warning'>".$corrida["datos_finaliza"]."</span>";
							
							// if(dame_permiso(""))
						?>
						
						<button  class="btn btn-info  btn-sm imprimir " hidden title="Imprimir" data-id_registro='<?php echo $corrida["id_corridas"]?>'>
							<i class="fas fa-print"></i> Imprimir Guía
						</button>	
						<?php
							if(dame_permiso("venta_boletos.php", $link) == 'Supervisor'){
							?>
							<button class="btn btn-success  btn-sm activar_corrida" title="Activar Corrida" 
							data-id_corridas="<?php echo $corrida["id_corridas"]?>">
								<i class="fas fa-check"></i> Activar Corrida
							</button>
							
							<?php
							}
							
							break;
							
							case "Cancelada":
							
							
							break;
							
						}
						
					?>
					
					
					<?php if($corrida["estatus_corridas"] != 'Cancelada'){ 	?>
						
						<button class="btn btn-info  btn-sm btn_gastos" title="Gastos" 
						data-id_corridas="<?php echo $corrida["id_corridas"]?>"
						data-num_eco="<?php echo $corrida["num_eco"]?>"
						>
							<i class="fas fa-dollar-sign"></i> Gastos
						</button>
						
					
						
						
						<?php
							// echo "Permiso",dame_permiso("venta_boletos.php", $link);
							if(dame_permiso("venta_boletos.php", $link) == 'Supervisor' || dame_permiso("venta_boletos.php", $link) == 'Escritura' ){
							?>
							<button class="btn btn-secondary  btn-sm cambiar_unidad" title="Cambiar Unidad" 
							data-id_registro="<?php echo $corrida["id_corridas"]?>"
							data-num_eco="<?php echo $corrida["num_eco"]?>"
							>
								<i class="fas fa-exchange-alt"></i> Cambiar Unidad
							</button>
							
							
							<?php
							}
							
							if(dame_permiso("venta_boletos.php", $link) == 'Supervisor' ){
							?>
							
							<button class="btn btn-warning  btn-sm editar" title="Editar" 
							data-id_registro="<?php echo $corrida["id_corridas"]?>"
							data-num_eco="<?php echo $corrida["num_eco"]?>"
							>
								<i class="fas fa-edit"></i> Editar
							</button>
							<button class="btn btn-danger btn-sm cancelar" title="Cancelar"     data-id_registro='<?php echo $corrida["id_corridas"]?>'>
								<i class="fas fa-times"></i> Cancelar
							</button>	
							
							
							
							<?php	
							}
						}
					?>
					
					
					<p class="small">
						Creada por: <?php echo $corrida["nombre_usuarios"]?>
					</p>
					
				</div>
			</div> 
			
			
		</div>
		
		
		
		<?php
			
		}//foreach
	?>
	
	<?php
		
	}
	
	else {
		echo "Error en ".$consulta.mysqli_Error($link);
		
	}
	
	
?>									