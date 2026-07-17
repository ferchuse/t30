<?php
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	
	date_default_timezone_set('America/Mexico_City');
	setlocale(LC_ALL,"en_US"); 
	
	include("control/is_selected.php");
	include("conexi.php");
	include("funciones/funciones_factura.php");
	include("funciones/generar_select.php");
	
	$link = Conectarse();
	
	$id_emisores = 1;
	
	$emisor = getEmisor($link, $id_emisores );
	
	// $folio = getFolio($link, $id_emisores);
	
	// $productos = copyProductos($link, $_GET["folio"]);
	
	// $venta = copyVenta($link, $_GET["folio"], $_GET["fecha"]);
	
	//NOTA: Una vez emitida la factura ya no podrás corregir ningún dato, por favor asegúrate que estén correctos antes de dar el click para emitir la factura.
	// print_r($venta);
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Nueva Factura</title>
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
		<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
		
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.9.0/css/all.css" integrity="sha384-i1LQnF23gykqWXg6jxC2ZbCbUMxyw5gLZY6UiUS98LYV5unm8GWmfkIS6jqJfb4E" crossorigin="anonymous">
		
		<STYLE>
			.mayus 
			{
			text-transform: uppercase;
			}
			
		</STYLE>
		
	</head>
	<body>
		
		
		<form id="form_factura" class="was-validated" >
			
			<div class="container">
				
				
				<div class="card card-primary">
					
					<div class="card-header">
						<h4 class="text-center">Nueva Factura</h4>
						
					</div>
					
					
					
					
					<div class="card-body">
						<div class="row">
							<div class="col-sm-6  ">
								<input name="id_ventas" type="hidden" id="id_ventas" value="<?php echo $_GET["folio"];?>">
								<input name="id_emisores" type="hidden" id="id_emisores" value="<?php echo $id_emisores;?>">
								<input name="tipo_comprobante" type="hidden" id="tipo_comprobante" value="I">
								<input name="metodo_pago" type="hidden" id="metodo_pago" value="PUE">
								
								
								
								<div class="form-group d-none">
									<label for="">Num Cliente</label>
									<input type="text" readonly id="id_clientes" name="id_clientes" class="form-control" value="">
									
								</div>
								<div class="form-group">
									<label for="">RFC: </label>
									<input type="text"  name="rfc_clientes" id="rfc_clientes" class="form-control mayus" required value="" >
								</div>
								<div class="form-group">
									<label for="">Razon Social: </label>
									<input type="text"  name="razon_social_clientes" id="razon_social_clientes" class="form-control mayus" required   value="">
								</div>
								
								<div class="row">
									<div class="form-group col-sm-6">
										<label for="">Codigo Postal: </label>
										<input type="number"  name="cp_clientes" id="cp_clientes" class="form-control text-right" required value="">
									</div>
									<div class="form-group col-sm-6">
										<label for="">Uso CFDI: </label>
										<?php echo generar_select($link, "cat_uso_cfdi", "uso_cfdi", "descripcion_uso_cfdi", false, false, true, 'G03');?>
									</div>
								</div>
								
								
								
								
							</div>
							<div class="col-sm-6  ">
								<div class="form-group ">
									<label class="control-label" for="regimen_clientes">
										Régimen fiscal
										<span class="requerido">*</span>:
									</label>
									<select id="regimen_clientes" required  name="regimen_clientes" class="form-control" >
										<option value="">Seleccione...</option>
										<option value="601">601	General de Ley Personas Morales</option>
										<option value="603">603	Personas Morales con Fines no Lucrativos</option>
										<option  value="605">605	Sueldos y Salarios e Ingresos Asimilados a Salarios</option>
									<option value="606">606	Arrendamiento</option>
									<option  value="607">607	Régimen de Enajenación o Adquisición de Bienes</option>
									<option   value="608">608	Demás ingresos</option>
									<option  value="609">609	Consolidación</option>
									<option  value="610">610	Residentes en el Extranjero sin Establecimiento Permanente en México</option>
									<option  value="611">611	Ingresos por Dividendos (socios y accionistas)</option>
									<option  value="612">612	Personas Físicas con Actividades Empresariales y Profesionales</option>
									<option  value="614">614	Ingresos por intereses</option>
									<option  value="615">615	Régimen de los ingresos por obtención de premios</option>
									<option  value="616">616	Sin obligaciones fiscales</option>
									<option  value="620">620	Sociedades Cooperativas de Producción que optan por diferir sus ingresos</option>
									<option  value="621">621	Incorporación Fiscal</option>
									<option  value="622">622	Actividades Agrícolas, Ganaderas, Silvícolas y Pesqueras</option>
									<option  value="623">623	Opcional para Grupos de Sociedades</option>
									
									<option  value="624">624	Coordinados</option>
									<option  value="625">625 – Régimen de las Actividades Empresariales con ingresos a través de Plataformas Tecnológicas</option>
									<option value="626">626 Regimen Simplificado de Confianza</option>
									<option  value="628">628	Hidrocarburos</option>
									<option  value="629">629	De los Regímenes Fiscales Preferentes y de las Empresas Multinacionales</option>
									<option  value="630">630	Enajenación de acciones en bolsa de valores</option>
								</select>
							</div>
							
							<div class="form-group ">
								<label class="control-label" for="forma_pago">Forma de Pago:</label>
								<select required id="forma_pago" name="forma_pago" class="form-control" >
									<option value="">Seleccione...</option>
									<option <?php echo $venta["fila"]["forma_pago"] == "Efectivo" ? "selected" : ""?> value="01" >01 Efectivo</option>
									
									<option value="03" >03 Transferencia electrónica de fondos</option>
									<option value="04">04 Tarjeta de crédito</option>
									<option <?php echo $venta["fila"]["forma_pago"] == "Tarjeta" ? "selected" : ""?> value="28" >28 Tarjeta de débito</option>
									<option value="29" >29 Tarjeta de servicios</option>
									<option  value="31" >31 Intermediario de Pagos</option>
								</select>
							</div>
							
							<div class="form-group">
								<label for="enviar_correo d-none">
									<input type="checkbox" id="enviar_correo" checked class="d-none"> Correo: 
								</label>
								
								<input type="email" name="correo_clientes" id="correo_clientes" class="form-control minus"  required value=""> 
							</div>
						</div>
					</div>
				</div>
			</div>
			
			
			
			<div class="table-responsive " >
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>CANTIDAD</th>
							<th>DESCRIPCIÓN</th>
							<th>PRECIO</th>
							<th>IMPORTE</th>
						</tr>
					</thead>
					<tbody>
						
						<?php 
							$traslados = 0;
							$subtotal = 0;
							$partida = 0;
							$i = 0;
							
							foreach ($productos["productos"] as $partida => $producto){
								$iva = Round($producto["precio"] * $producto["tasa_iva"], 2); 
								$importe = Round($producto["precio"] * $producto["cantidad"], 2);
								$subtotal+= $importe;
								$traslados+= $iva;
								
								if($producto["precio"] > 0){
									
								?>
								<tr>
									<td>
										
										<input  readonly type="number" min="0" step="any"  name="cantidad[]" class="form-control cantidad conceptos text-right" value="<?php echo $producto["cantidad"]?>">
										
										
										<td>
											<textarea <?php echo isset($_GET["id_emisores"]) ? "" : "readonly"?> required cols="20"  rows="2"  name="descripcion[]" class="form-control conceptos "><?php echo $producto["descripcion"]?></textarea>
										</td>
										<td>
											<input  readonly type="number" min="0" step="any" name="precio_unitario[]" class="form-control conceptos precio_sin_iva text-right" value="<?php echo Round($producto["precio"], 2)?>">
										</td>
										<td>
											<input readonly step="any"  name="importe[]" class="form-control importe conceptos text-right" value="<?php echo $importe;?>">
										</td>
										<td class="d-none">
											<input  readonly  name="clave_unidad[]" class="clave_unidad conceptos " value="<?php echo $producto["clave_unidad"]?>">
											
											
											<input  readonly type="number" min="0" step="any"  name="clave_producto[]" class=" clave_sat conceptos " value="<?php echo $producto["clave_sat"]?>">
											
											
											
											<input name="tipo_impuesto[<?php echo $i;?>][]" class="tipo_impuesto" value="Traslado">
											<input name="impuesto[<?php echo $i;?>][]" class="impuesto" value="002">
											<input name="base[<?php echo $i;?>][]" class="base" value="<?php echo $importe - $producto["cant_descuento"];?>">
											<input name="tasa[<?php echo $i;?>][]" class="tasa" value="0.000000">
											<input name="tipo_factor[<?php echo $i;?>][]" class="tipo_factor"  value="Exento">
											<input name="impuesto_importe[<?php echo $i;?>][]" step="any"  class="impuesto_importe" value="<?php echo $iva;?>">
										</td>
									</tr>
									<?php
									}
								?>
								
							</tbody>
							
							<?php
								$i++;						
							}
						?>
						
					</table>
				</div>
				<div class="row">
					
					<label class="d-none">
						<input type="checkbox" name="modo_pruebas" value="SI"   > MODO PRUEBAS
					</label>
					
					<div class="col-sm-3 offset-sm-7 text-right">
						<label>SUBTOTAL:</label>
					</div>
					<div class="col-sm-2">
						<input readonly  type="number" step="any" class="form-control text-right" name="subtotal" id="subtotal" value="<?php echo Round($subtotal, 2);?>">
					</div>
				</div>
				<div class="row d-none">
					<div class="col-sm-2 offset-sm-7 text-right">
						<label>DESCUENTO:</label>
					</div>
					<div class="col-sm-1">
						<input  type="number" step="any" class="form-control" name="descuento_total" id="descuento_total">
					</div>
				</div>
				<div class="row">
					<div class="col-sm-3 offset-sm-7 text-right">
						<label>TRASLADADOS:</label> 
					</div>
					<div class="col-sm-2">
						<input  readonly type="number" step="any" class="form-control text-right" name="total_traslados" id="total_traslados" value="<?php echo $traslados?>">
					</div>
				</div>
				<div class="row d-none">
					<div class="col-sm-3 offset-sm-7 text-right ">
						<label>RETENIDOS:</label>
					</div>
					<div class="col-sm-2">
						<input readonly  type="number" step="any" class="form-control" name="total_retenciones" id="total_retenciones">
					</div>
				</div>
				<div class="row">
					<div class="col-sm-3 offset-sm-7 text-right">
						<label>TOTAL:</label>
					</div>
					<div class="col-sm-2">
						<input  readonly type="number"  step="any" class="form-control text-right" name="total_pagos" id="total" value="<?php echo $subtotal + $traslados;?>">
					</div>
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
				<div id="descargar" class="alert alert-success d-none">
					<a target="_blank" href="" class="btn btn-success" id="url_pdf"><i class="fas fa-file-pdf"></i> Descargar PDF</a>
					<a target="_blank" href=""  class="btn btn-info"  id="url_xml"><i class="fas fa-code"></i> Descargar XML </a>
				</div>
				
				<div class="row">
					
					<div class="col-sm-6">
						<button type="submit" id="timbrado_sw"  class="btn btn-primary btn-lg float-right fixed-bottom">
							Facturar <i class="fa fa-arrow-right"></i>
						</button>
					</div>
				</div>
				
			</form>
			
			
			
			<script src="js/facturas_nueva.js?v=<?= date("y-m-d-h-i-s")?>"></script>
			
			
		</body>
	</html>																		