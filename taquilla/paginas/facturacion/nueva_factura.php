<?php
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	
	
	include("../login/login_check.php");
	include_once("control/is_selected.php");
	include_once("funciones/funciones_factura.php");
	include("../../conexi.php");
	
	date_default_timezone_set('America/Mexico_City');
	setlocale(LC_ALL,"en_US"); 
	
	
	$link = Conectarse();
	$menu_activo = "facturacion";
	
	
	if(isset( $_GET["id_emisores"])){
		$id_emisores = $_GET["id_emisores"];
	}
	else{
		$id_emisores =1 ;
	}
	
	$productos = [];
	// $factura= [];
	// $factura["conceptos"]= [];
	$venta = [];
	$emisor = getEmisor($link, 	$id_emisores);
	
	
	
	
	
	if(isset($_GET["id_facturas"])){
		$factura = copiarFactura($link, $_GET["id_facturas"]);
		
		if(isset($_GET["copia"])){
			$id_facturas = "";
			$serie = $emisor["datos"]["serie"];
			$folio = $emisor["datos"]["folio"];
		}
		else{
			
			$id_facturas = $_GET["id_facturas"];
			$serie = $factura["serie"];
			$folio = $factura["folio"];
		}
		
	}
	else{
		
		$factura =  facturaVacia();
		
		$id_facturas = "";
		$serie = $emisor["datos"]["serie"];
		$folio = $emisor["datos"]["folio"];
	}
	
	
	
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Nueva Factura</title>
		<?php include("../../styles.php");?>
		<style>
			#form_conceptos div[class*="col-sm"]{
			padding-left: 5px !important;
			padding-right: 5px !important;
			} 
		</style>
	</head>
	<body>
		<body id="page-top">
			<?php include("../../navbar.php")?>
			<div id="wrapper" class="d-print-none">
				<?php include("../../menu.php")?>	
				<div id="content-wrapper">		
					<div class="container-fluid">		
						
						<h4 class="text-center">Nueva Factura</h4>
						
						<?php
							// echo "<pre>";
							// print_r(json_encode($factura));
							// echo "</pre>";
						?>
						
						<div class="container-fluid">
							<div class="row">
								<div class="col-sm-12">
									<ul class="nav nav-pills nav-justified hidden-xs ">
										<li class="nav-item  ">
											<a class="nav-link" data-toggle="tab" id="tab_cliente"  href="#datos_cliente">1-Cliente</a>
										</li>
										<li class="nav-item">
											<a class="nav-link"  data-toggle="tab" id="tab_factura" href="#datos_factura">2-Factura</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" data-toggle="tab" id="tab_conceptos" href="#datos_conceptos">3-Conceptos</a>
										</li>
									</ul>
									
									<div class="tab-content"> 
										<div class="tab-pane fade show  active" id="datos_cliente">
											<form id="form_cliente" class="was-validated">
												<div class="card card-primary">
													
													<div class="card-body">
														<div class="row">
															<div class="col-sm-4 offset-sm-4 ">
																<div class="form-group ">
																	<label for="">Num Cliente</label>
																	<input type="text" readonly id="id_clientes" name="id_clientes" class="form-control" value="<?php echo $factura["id_clientes"]?>">
																	
																</div>
																<div class="form-group">
																	<label for="">RFC: </label>
																	<input type="text" name="rfc_clientes" id="rfc_clientes" class="form-control" required value="<?php echo $factura["rfc_clientes"]?>">
																</div>
																<div class="form-group">
																	<label for="">Razon Social: </label>
																	<input type="text"  name="razon_social_clientes" id="razon_social_clientes" class="form-control" required value="<?php echo $factura["razon_social_clientes"]?>">
																</div>
																
																<div class="form-group">
																	<label for="">Codigo Postal: </label>
																	<input type="number"  name="cp_clientes" id="cp_clientes" class="form-control" required value="<?php echo $factura["cp_clientes"]?>">
																</div>
																<div class="form-group ">
																	<label class="control-label" for="regimen_clientes">
																		Régimen fiscal
																		<span class="requerido">*</span>:
																	</label>
																	<select id="regimen_clientes" required  name="regimen_clientes" class="form-control" >
																		<option  value="">Seleccione...</option>
																		<option <?php echo is_selected($factura["regimen_clientes"], "601")?> value="601">601	General de Ley Personas Morales</option>
																		<option <?php echo is_selected($factura["regimen_clientes"], "603")?> value="603">603	Personas Morales con Fines no Lucrativos</option>
																		<option <?php echo is_selected($factura["regimen_clientes"], "605")?> value="605">605	Sueldos y Salarios e Ingresos Asimilados a Salarios</option>
																		<option <?php echo is_selected($factura["regimen_clientes"], "606")?> value="606">606	Arrendamiento</option>
																		<option <?php echo is_selected($factura["regimen_clientes"], "607")?> value="607">607	Régimen de Enajenación o Adquisición de Bienes</option>
																		<option  <?php echo is_selected($factura["regimen_clientes"], "608")?> value="608">608	Demás ingresos</option>
																		<option  <?php echo is_selected($factura["regimen_clientes"], "609")?> value="609">609	Consolidación</option>
																		<option <?php echo is_selected($factura["regimen_clientes"], "610")?> value="610">610	Residentes en el Extranjero sin Establecimiento Permanente en México</option>
																		<option <?php echo is_selected($factura["regimen_clientes"], "611")?> value="611">611	Ingresos por Dividendos (socios y accionistas)</option>
																		<option <?php echo is_selected($factura["regimen_clientes"], "612")?> value="612">612	Personas Físicas con Actividades Empresariales y Profesionales</option>
																		<option <?php echo is_selected($factura["regimen_clientes"], "614")?> value="614">614	Ingresos por intereses</option>
																		<option  <?php echo is_selected($factura["regimen_clientes"], "615")?> value="615">615	Régimen de los ingresos por obtención de premios</option>
																		<option <?php echo is_selected($factura["regimen_clientes"], "616")?>  value="616">616	Sin obligaciones fiscales</option>
																		<option <?php echo is_selected($factura["regimen_clientes"], "620")?> value="620">620	Sociedades Cooperativas de Producción que optan por diferir sus ingresos</option>
																		<option <?php echo is_selected($factura["regimen_clientes"], "621")?> value="621">621	Incorporación Fiscal</option>
																		<option <?php echo is_selected($factura["regimen_clientes"], "622")?> value="622">622	Actividades Agrícolas, Ganaderas, Silvícolas y Pesqueras</option>
																		<option <?php echo is_selected($factura["regimen_clientes"], "623")?> value="623">623	Opcional para Grupos de Sociedades</option>
																		
																		<option <?php echo is_selected($factura["regimen_clientes"], "624")?> value="624">624	Coordinados</option>
																		<option <?php echo is_selected($factura["regimen_clientes"], "625")?> value="625">625 Régimen de las Actividades Empresariales con ingresos a través de Plataformas Tecnológicas</option>
																		<option <?php echo is_selected($factura["regimen_clientes"], "626")?> value="626">626 Regimen Simplificado de Confianza</option
																		<option  <?php echo is_selected($factura["regimen_clientes"], "628")?> value="628">628	Hidrocarburos</option>
																		<option  <?php echo is_selected($factura["regimen_clientes"], "629")?> value="629">629	De los Regímenes Fiscales Preferentes y de las Empresas Multinacionales</option>
																		<option  <?php echo is_selected($factura["regimen_clientes"], "630")?> value="630">630	Enajenación de acciones en bolsa de valores</option>
																</select>
															</div>
															
															<div class="form-group">
																<label for="enviar_correo">
																	<input type="checkbox" id="enviar_correo" checked>Enviar Correo: 
																</label>
																<input type="email" name="correo_clientes" id="correo_clientes" class="form-control minus"  value="<?php echo $factura["correo_clientes"]?>" required>
															</div>
															
															
															
														</div>
													</div>
													<div class="row">
														<div class="col-sm-12">
															<button type="submit"  class="btn btn-success btn-lg float-right next">
																Siguiente <i class="fa fa-arrow-right"></i>
															</button>
														</div>
													</div>
													
												</div>
											</div>
										</form>
									</div>
									
									
									<div class="tab-pane fade" id="datos_factura">
										<form id="form_factura" class="was-validated">
											<input id="id_facturas" name="id_facturas" type="hidden" value="<?php echo $id_facturas?>">
											<div class="card card-primary">
												
												<div class="card-body">
													
													<div class="row">
														<div class="col-sm-4 offset-sm-4 ">
															<div class="row">
																<div class="col-sm-6">
																	
																	<div class="form-group ">
																		<label class="control-label" for="forma_pago">Serie:</label>
																		<input type="text" name="serie" id="serie" class="form-control" value="<?php echo $serie?>" readonly >
																	</div>
																	<div class="form-group ">
																		<label class="control-label" for="forma_pago">Folio:</label>
																		<input type="text" name="folio" id="folio" class="form-control" value="<?php echo $folio?>" readonly >
																	</div>
																	<div class="form-group">
																		<label for="">Metodo de pago</label>
																		<select id="metodo_pago" name="metodo_pago" class="form-control" >
																			<option value="">Seleccione...</option>
																			<option <?php echo is_selected($factura["metodo_pago"], "PUE")?>  value="PUE">Pago en una sola exhibición</option>
																			<option <?php echo is_selected($factura["metodo_pago"], "PPD")?> value="PPD" >Pago en parcialidades o diferido</option>
																			<option <?php echo is_selected($factura["metodo_pago"], "PIP")?> value="PIP" >Pago Inicial y parcialidades</option>
																		</select>
																	</div>
																	
																	<div class="form-group ">
																		<label class="control-label" for="forma_pago">Forma de Pago:</label>
																		<select required id="forma_pago" name="forma_pago" class="form-control" >
																			<option  value="">Seleccione...</option>
																			<option <?php echo is_selected($factura["forma_pago"], "01")?> value="01" >01 Efectivo</option>
																			<option <?php echo is_selected($factura["forma_pago"], "02")?> value="02">02 Cheque nominativo</option>
																			<option <?php echo is_selected($factura["forma_pago"], "03")?> value="03" >03 Transferencia electrónica de fondos</option>
																			<option <?php echo is_selected($factura["forma_pago"], "04")?> value="04">04 Tarjeta de crédito</option>
																			<option <?php echo is_selected($factura["forma_pago"], "28")?>  value="28" >28 Tarjeta de débito</option>
																			<option <?php echo is_selected($factura["forma_pago"], "29")?> value="29" >29 Tarjeta de servicios</option>
																			<option <?php echo is_selected($factura["forma_pago"], "99")?>  value="31" >31 Intermediario de Pagos</option>
																			<option <?php echo is_selected($factura["forma_pago"], "99")?>  value="99" >99 Por definir</option>
																		</select>
																	</div>
																	
																	<div class="form-group">
																		<label >Observaciones: </label>
																		<textarea rows="2" cols="8" class="form-control" name="observaciones" id="observaciones"><?php echo $factura["observaciones"]?>
																		</textarea>
																	</div>
																	
																</div>
																<div class="col-sm-6 ">
																	
																	<div class="form-group d-none">
																		<label for="">ID</label>
																		<input type="text" name="id_emisores" id="id_emisores" class="form-control" value="<?php echo $id_emisores;?>" readonly required>
																	</div>
																	
																	<div class="form-group d-none">
																		<label for="">Tipo de Comprobante</label>
																		<select id="tipo_comprobante" name="tipo_comprobante" class="form-control" >
																			<option value="">Seleccione...</option>
																			<option value="E">E Egreso</option>
																			<option selected value="I">I Ingreso</option>
																			<option value="N">N Nómina</option>
																			<option value="P">P Pago</option>
																			<option value="T">T Traslado</option>
																		</select>
																	</div>
																	<div class="form-group ">
																		<label for="">Uso CFDI</label>
																		<select id="uso_cfdi" name="uso_cfdi" class="form-control" >
																			<option <?php echo is_selected($factura["uso_cfdi"], "G01")?> value="G01">G01 Adquisición de mercancias</option>
																			<option value="G02">G02 Devoluciones, descuentos o bonificaciones</option>
																			<option <?php echo is_selected($factura["uso_cfdi"], "G03")?> value="G03">G03 Gastos en general</option>
																			<option value="I01">I01 Construcciones</option>
																			<option value="I02">I02 Mobilario y equipo de oficina por inversiones</option>
																			<option value="I03">I03 Equipo de transporte</option>
																			<option value="I04">I04 Equipo de computo y accesorios</option>
																			<option value="I05">I05 Dados, troqueles, moldes, matrices y herramental</option>
																			<option value="I06">I06 Comunicaciones telefónicas</option>
																			<option value="I07">I07 Comunicaciones satelitales</option>
																			<option value="I08">I08 Otra maquinaria y equipo</option>
																			<option value="D01">D01 Honorarios médicos, dentales y gastos hospitalarios.</option>
																			<option value="D02">D02 Gastos médicos por incapacidad o discapacidad</option>
																			<option value="D03.">D03 Gastos funerales.</option>
																			<option value="D04.">D04 Donativos.</option>
																			<option value="D05">D05 Intereses reales efectivamente pagados por créditos hipotecarios (casa habitación).</option>
																			<option value="D06">D06 Aportaciones voluntarias al SAR.</option>
																			<option value="D07">D07 Primas por seguros de gastos médicos.</option>
																			<option value="D08">D08 Gastos de transportación escolar obligatoria.</option>
																			<option value="D09">D09 Depósitos en cuentas para el ahorro, primas que tengan como base planes de pensiones.</option>
																			<option value="D10">D10 Pagos por servicios educativos (colegiaturas)</option>
																			<option value="P01">P01 Por definir</option>
																			<option <?php echo is_selected($factura["uso_cfdi"], "S01")?> value="S01">S01- Sin Obligaciones Fiscales</option>
																			
																		</select>
																	</div>
																	
																	
																	<br>
																	
																	<label  > 
																		<input value="SI"  type="checkbox" name="factura_global" id="factura_global">
																		Factura Global:
																	</label>
																	<br>
																	<div class="form-group">
																		<label for="" class="text-center">Periodicidad:</label>
																		<select id="periodicidad" name="periodicidad" class="form-control" >
																			<option value="">Seleccione...</option>
																			<option value="01">Diario</option>
																			<option  value="02">Semanal</option>
																			<option  value="03">Quincenal</option>
																			<option selected value="04">Mensual</option>
																			<option value="05">Bimestral</option>
																			
																		</select> 
																	</div> 
																	
																	<div class="form-group">
																		<label for="year" class="text-center">Año:</label>
																		<select id="year" name="year"  class="form-control">
																			<option <?php echo is_selected(date("Y"), "2023")?> value="2023">2023</option>
																			<option <?php echo is_selected(date("Y"), "2024")?> value="2024">2024</option>
																			<option <?php echo is_selected(date("Y"), "2025")?> value="2025">2025</option>
																			<option <?php echo is_selected(date("Y"), "2026")?>  value="2026">2026</option>
																			<option <?php echo is_selected(date("Y"), "2027")?> value="2027">2027</option>
																			<option <?php echo is_selected(date("Y"), "2028")?> value="2028">2028</option>
																			<option <?php echo is_selected(date("Y"), "2029")?> value="2029">2029</option>
																			<option  <?php echo is_selected(date("Y"), "2030")?> value="2030">2030</option>
																		</select>
																	</div> 
																	
																	
																	<div class="form-group">
																		<label for="mes" class="text-center">Mes:</label>
																		<select id="mes" name="mes" class="form-control">
																			<option <?php echo is_selected(date("m"), "01")?> value="01">Enero</option>
																			<option <?php echo is_selected(date("m"), "02")?>  value="02">Febrero</option>
																			<option <?php echo is_selected(date("m"), "03")?> value="03">Marzo</option>
																			<option <?php echo is_selected(date("m"), "04")?> value="04">Abril</option>
																			<option <?php echo is_selected(date("m"), "05")?>  value="05">Mayo</option>
																			<option <?php echo is_selected(date("m"), "06")?>  value="06">Junio</option>
																			<option <?php echo is_selected(date("m"), "07")?>  value="07">Julio</option>
																			<option <?php echo is_selected(date("m"), "08")?>  value="08">Agosto</option>
																			<option <?php echo is_selected(date("m"), "09")?>  value="09">Septiembre</option>
																			<option <?php echo is_selected(date("m"), "10")?>  value="10">Octubre</option>
																			<option <?php echo is_selected(date("m"), "11")?>  value="11">Noviembre</option>
																			<option <?php echo is_selected(date("m"), "12")?>  value="12">Diciembre</option>
																		</select>
																	</div> 
																</div>
															</div>
															
															
															<div class="row">
																<div class="col-sm-6">
																	<input name="id_ventas" type="hidden" id="id_ventas" value="">
																</div>
															</div>
															
															<div class="row">	
																<div class="col-sm-12">	
																	
																	<a id="ir_paso_1"  type="button"  class="btn btn-success btn-lg float-left text-white anterior ">
																		Anterior <i class="fa fa-arrow-left"></i>
																	</a>
																	<button   type="submit"  class="btn btn-success btn-lg float-right next">
																		Siguiente <i class="fa fa-arrow-right"></i>
																	</button>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</form>
									</div> 
									
									
									<div class="tab-pane fade" id="datos_conceptos">
										<form id="form_conceptos">
											<div class="card card-primary ">
												<div class="card-heading">
													<h4>
														2-Conceptos
														<button type="button" class="btn btn-success float-right" id="agregar_concepto">
															<i class="fa fa-plus"></i> Agregar Concepto
														</button>
													</h4>
												</div>
												<div class="card-body " >
													<div class="row">
														<div class="col-sm-1" >
															<label>CANTIDAD</label>
														</div>
														<div class="col-sm-1 hidden" >
															<label>UNIDAD</label>
														</div>
														<div class="col-sm-2 hidden" >
															<label>CLAVE</label>
														</div>
														<div class="col-sm-4" >
															<label>DESCRIPCIÓN</label>
														</div>
														<div class="col-sm-1 " hidden >
															<label>PRECIO UNITARIO C/IVA</label>
														</div>
														<div class="col-sm-1" >
															<label>PRECIO</label>
														</div>
														<div class="col-sm-1 hidden d-none" >
															<label>IVA</label>
														</div>
														<div class="col-sm-1" >
															<label>IMPORTE</label>
														</div>
														<div class="col-sm-1 " hidden >
															<label>DESCUENTO</label>
														</div>
														
													</div>
													<hr>
													
													<div id="div_conceptos">
														<?php 
															$traslados = 0;
															$subtotal = 0;
															$partida = 0;
															$i = 0;
															// echo "conceptos";
															// print_r($factura["conceptos"]);
															// echo count($factura["conceptos"]);
															
															foreach ($factura["conceptos"] as $partida => $concepto){
																
																// $iva = round($concepto["precio"] * .16, 2); 
																// $iva = Round($concepto["precio"] * $concepto["tasa_iva"], 2); 
																$importe = Round($concepto["precio"] * $concepto["cantidad"], 2);
																$subtotal+= $importe;
																// $traslados+= $iva;
																
																
																
															?>
															
															<div class="fila_concepto <?php echo "partida_$i";?>">
																<div class="row ">
																	<div class="col-sm-1">
																		<input required type="number" min="0" step="any"  name="cantidad[]" class="form-control cantidad conceptos" value="<?php echo $concepto["cantidad"]?>">
																	</div>	
																	<div class="col-sm-1 ">
																		<select required  name="clave_unidad[] " class="form-control clave_unidad conceptos">
																			<option value="">Elige...</option>
																			<?php echo  getUnidades($link,$id_emisores, $concepto["clave_unidad"] );?>
																		</select>
																		
																		<input type="hidden" class="nombre_unidades hidden" name="nombre_unidades[]" value="Servicio" >
																		
																	</div>	
																	<div class="col-sm-2 ">
																		<select required  name="clave_producto[]" class="form-control  conceptos">
																			<option value="">Elige...</option>
																			<?php echo  getProductos($link,$id_emisores,  $concepto["clave_productos"] , "78111804");?>
																		</select>
																	</div>
																	<div class="form-group col-sm-4">
																		<textarea required cols="4"  rows="2" value="" placeholder=""  name="descripcion[]" class="form-control conceptos"><?php echo $concepto["descripcion"]?></textarea>
																	</div>
																	<div class="col-sm-1 d-none" hidden>
																		<input  type="number" min="0" step="any"  name="" class="form-control precio_unitario conceptos">
																	</div>
																	<div class="col-sm-1">
																		<input   type="number" min="0" step="any" name="precio_unitario[]" class="form-control conceptos precio_sin_iva" value="<?php echo Round($concepto["precio"], 2)?>">
																	</div>
																	<div class="col-sm-1 d-none" >
																		<input   type="number" min="0" step="any"  class="form-control iva_unitario conceptos" value="<?php echo $iva;?>">
																	</div> 
																	<div class=" col-sm-1">
																		<input required  type="number" min="0" step="any"  name="importe[]" class="form-control importe conceptos" value="<?php echo $importe;?>">
																	</div>
																	<div class="col-sm-1 d-none" hidden>
																		<input  type="number" step="any"  name="descuento[]" class="form-control descuento conceptos" value="<?= $concepto["cant_descuento"]?>">
																	</div>
																	<div class="col-sm-1 d-none" hidden>
																		<input  type="number" min="0" step="any"  name="iva[]" class="form-control iva conceptos" value="<?php echo $iva;?>">
																	</div>
																	<div class="col-sm-1">
																		<button type="button" class="btn btn-danger btn_borrar" title="Eliminar">
																			<i class="fa fa-times"></i>
																		</button>
																	</div> 
																</div>
																
																<?php 	foreach ($concepto["impuestos"] as $i_impuesto => $impuesto){?>
																	<div class="row" >
																		<div class="col-sm-2 offset-sm-1 ">
																			<h4>
																				Impuestos
																				<button type="button" class="btn btn-success agregar_impuesto" title="Agregar Impuesto">
																					<i class="fa fa-plus"></i>
																				</button>
																			</h4>
																		</div>
																		<div class="impuestos col-sm-8">
																			<div class="fila_impuesto row">
																				<div class="col-sm-2 ">
																					<label>Tipo Impuesto</label>
																					<select name="tipo_impuesto[<?php echo $i;?>][]" class="form-control tipo_impuesto">
																						<option <?php echo is_selected($impuesto["tipo_impuesto"], "Traslado")?> value="Traslado"> Traslado</option>
																						<option  <?php echo is_selected($impuesto["tipo_impuesto"], "Retención")?> value="Retención"> Retención</option>
																						
																					</select>
																				</div>	
																				<div class="col-sm-1 ">
																					<label>Impuesto</label>
																					<select required  name="impuesto[<?php echo $i;?>][]" class="form-control ">
																						<option value="">Elige...</option>
																						<option <?php echo is_selected($impuesto["impuesto"], "001")?> value="001">ISR</option>
																						<option <?php echo is_selected($impuesto["impuesto"], "002")?> value="002" selected>IVA</option>
																					</select>
																					
																				</div>	
																				<div class="form-group col-sm-2">
																					<label>Base:</label>
																					<input  name="base[<?php echo $i;?>][]" value="0" type="number" min="0" step="any"  class="form-control base" value="<?php echo $impuesto["base"];?>">
																				</div>
																				<div class="form-group col-sm-2 ">
																					<label>Tasa:</label>
																					<select required  name="tasa[<?php echo $i;?>][]" class="form-control tasa">
																						<option value="">Elige...</option>
																						<option <?php echo is_selected($impuesto["tasa"], "0.000000")?> value="0.000000">0%</option>
																						<option <?php echo is_selected($impuesto["tasa"], "0.160000")?> value="0.160000">16%</option>
																						<option <?php echo is_selected($impuesto["tasa"], "0.106666")?> value="0.106666" >10.66%</option>
																						<option <?php echo is_selected($impuesto["tasa"], "0.100000")?> value="0.100000" >10%</option>
																					</select>
																				</div>
																				<div class="form-group col-sm-2 ">
																					<label>Tipo Factor:</label>
																					<select name="tipo_factor[<?php echo $i;?>][]" class="form-control tipo_factor">
																						<option <?php echo is_selected($impuesto["tipo_factor"], "Tasa")?> value="Tasa"> Tasa</option>
																						<option  <?php echo is_selected($impuesto["tipo_factor"], "Cuota")?> value="Cuota"> Cuota</option>
																						<option  <?php echo is_selected($impuesto["tipo_factor"], "Exento")?> value="Exento"> Exento</option>
																					</select>
																				</div>
																				<div class="form-group col-sm-2">
																					<label>Importe:</label>
																					<input name="impuesto_importe[<?php echo $i;?>][]" value="0" type="number" min="0" step="any"  class="form-control impuesto_importe" value="<?php echo $impuesto["impuesto_importe"];?>">
																					
																				</div>
																				<div class="form-group col-sm-1">
																					<label>Eliminar:</label>
																					<button type="button" name="eliminar[<?php echo $i;?>][]" class="btn btn-danger borrar_impuesto float-right" title="Eliminar">
																						<i class="fa fa-times"></i>
																					</button>
																				</div>
																				
																			</div>
																		</div>
																	</div>
																	<?php
																	}
																?>
																
																<hr>
															</div>
															
															<?php
																$i++;
															}
															
														?>
														
														
													</div>
													<div class="row">
														<div class="col-sm-3 offset-sm-7 text-right">
															<label>SUBTOTAL:</label>
														</div>
														<div class="col-sm-1">
															<input required  type="number" step="any" class="form-control" name="subtotal" id="subtotal" value="<?php echo Round($subtotal, 2);?>">
														</div>
													</div>
													<div class="row">
														<div class="col-sm-3 offset-sm-7 text-right">
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
														<div class="col-sm-1">
															<input  type="number" step="any" class="form-control" name="total_traslados" id="total_traslados" value="<?php echo $traslados?>">
														</div>
													</div>
													<div class="row">
														<div class="col-sm-3 offset-sm-7 text-right">
															<label>RETENIDOS:</label>
														</div>
														<div class="col-sm-1">
															<input required  type="number" step="any" class="form-control" name="total_retenciones" id="total_retenciones">
														</div>
													</div>
													<div class="row">
														<div class="col-sm-3 offset-sm-7 text-right">
															<label>TOTAL:</label>
														</div>
														<div class="col-sm-1">
															<input required  type="number"  step="any" class="form-control" name="total_pagos" id="total" value="<?php echo $subtotal + $traslados;?>">
														</div>
													</div>
													<hr> 
													
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
													<pre id="debug" >
													</pre> 
													<a   type="button" id="ir_paso_2" class="btn btn-success btn-lg float-left anterior text-white">
														Anterior <i class="fa fa-arrow-left"></i>
													</a>
													
													<button type="button" id="timbrado_sw"  class="btn btn-primary btn-lg float-right ">
														Timbrar <i class="fa fa-arrow-right"></i>
													</button>
													<button  type="button" id="btn_guardar"  class="btn btn-warning btn-lg float-right">
														Guardar Borrador <i class="fa fa-save"></i>
													</button>
													<button type="button" id="btn_vista_previa"  class="btn btn-success btn-lg float-right ">
														Vista Previa <i class="fa fa-eye"></i>
													</button>
													
													<label class="float-right">
														<input  type="checkbox" name="modo_pruebas"  id="modo_pruebas" value="SI"  > MODO PRUEBAS
													</label>
													
													
													
													
												</div>
											</div>
										</form>
									</div><!--/tab-pane -->
								</div><!--/tab-content -->
							</div><!--/col-sm-12-->
						</div><!--/row-->
						
					</div><!--/container-->
				</div><!--/container-->
			</div><!--/container-->
		</div><!--/container-->
		
		
		<?php include("../../scripts.php");?>
		<script src="js/facturas_nueva.js?v=<?= date("y-m-d-h-i-s")?>"></script>
		
		
	</body>
</html>									