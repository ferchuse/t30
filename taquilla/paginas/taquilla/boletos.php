<?php
	include("../../paginas/login/login_check.php");
	include('../../conexi.php');
	include('../../funciones/generar_select.php');
	// include_once('../../funciones/dame_permiso.php');
	include("consultas/get_destinos.php");
	include("consultas/get_unidades.php");
	include("consultas/get_clientes.php");
	
	$destinos = getDestinos($link);
	$tipos_unidad = getTiposUnidades($link);
	$clientes_frecuentes = getClientes("Activo");
	
	
	
	
	$link = Conectarse();
	$nombre_pagina = "Boletos";
	
	
?>
<!DOCTYPE html> 
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">
		<title><?php echo $nombre_pagina;?></title>
		<?php include('../../styles.php')?>
		
		
		
		<style>
            #lista_clientes_frecuentes .seleccionar_cliente_frecuente {
                cursor: pointer;
            }
			/**
			* @license
			* Copyright 2019 Google LLC. All Rights Reserved.
			* SPDX-License-Identifier: Apache-2.0
			*/
			/* 
			* Always set the map height explicitly to define the size of the div element
			* that contains the map. 
			*/
			.map_section {
			height : 400px;
			}
			#map {
			height: 400px;
			
			}
			
			/* 
			* Optional: Makes the sample page fill the window. 
			*/
			html,
			body {
			height: 100%;
			margin: 0;
			padding: 0;
			}
			
			#floating-panel {
			position: absolute;
			top: 10px; 
			left: 25%;
			z-index: 5;
			background-color: #fff;
			padding: 5px; 
			border: 1px solid #999;
			text-align: center;
			font-family: "Roboto", "sans-serif";
			line-height: 30px;
			padding-left: 10px;
			}
			
			
			.mayusculas{
			text-transform: uppercase;
			}
			
		</style>
		
	</head>
	<body id="page-top">
		<?php include("../../navbar.php")?>
		<div id="wrapper" class="">
			<?php include("../../menu.php")?>	
			<div id="content-wrapper">	
				
				<div class="row">
					<div class="col-sm-2 ">
						<div class="card card-primary shadow">
							<div class="card-body">
								<div class="slider_form">
									<h4>
										Elige un Destino
									</h4>
									<form id="form_cotizar">
										<div class="form-group d-none">
											<label class="">Tipo de Unidad:</label>
											<select class="form-control" id="tipo_unidad">
												<?php foreach ($tipos_unidad AS $tipo_unidad){?>
													<option data-tarifa="6" data-tipo="sedan" selected >	
														<?php echo $tipo_unidad["tipo_unidad"]?>
													</option>
												<?php }?>
												
											</select>
										</div>
										<div class="form-group">
											<label >Taquilla:</label>
											
											<select class="form-control" id="taquilla" name="taquilla" form="form_vender">
												
												<option value="NACIONAL" SELECTED>
													NACIONAL
												</option>
												<option value="INTERNACIONAL" > 
													INTERNACIONAL
												</option>
												
											</select>
										</div>
										<div class="form-group" id="div_origen" >
											<label id="label_origen">Origen:</label>
											<input class="form-control" id="origen" name="origen" type="text" placeholder="AIFA" readonly value="AIFA" form="form_vender">
										</div>
										<div class="form-group " id="div_destino">
											<label id="label_destino">Destino:</label> 
											
											<button class="btn btn-info btn-sm" type="button" id="intercambiar" title ="Intercambiar Origen y Destino ">
												<i class="fas fa-exchange-alt fa-rotate-90"></i>
											</button >
											
											<select form="form_vender" class="form-control" id="destino" name="destino" required>
												<option value="" required >Elige:</option>
												<?php
													foreach($destinos AS $i=> $destino){
													?>
													<option 
													data-precio="<?php echo $destino["precio"];?>"
													data-precio_ejecutiva="<?php echo $destino["precio_ejecutiva"];?>"
													><?php echo $destino["destino"];?></option>
													<?php
													}
												?>
											</select>
										</div>
										
										<div class="form-group d-none">
											<label class="">Tarifa Base por KM :</label>
											<input class="form-control" id="tarifa" type="number" value="6">
										</div>
										<button id="btn_prueba" type="button" class="btn btn-info"> 
											<i class="fas fa-print"></i> Probar Impresora 
										</button>
										<div class="float-right">
											
											<button id="btn_cotizar" type="button" class="btn btn-success d-none"> 
												<i class="fas fa-arrow-right"></i> Cotizar 
											</button>
										</div>
									</form>
								</div><!-- /.card-body-->
							</div><!-- /.card -->
						</div>
						
					</div>
					<div class="col-sm-5 d-none">
						<div id="map"></div>
						
					</div>
					
					<div class="col-sm-10">
						<form id="form_vender" autocomplete="off" class="was-validated">
							<div class="row">
								<div class="col-sm-6">
									<div class="card shadow mb-3">
										<div class="card-header">
											<h4><i class="fas fa-bus"></i> Datos del Viaje</h4>
											
										</div>
										
										<div class="card-body">
											<!-- ===================== DATOS DEL VIAJE ===================== -->
											
											<div class="form-group">
												<label>Cliente</label>
												<input type="hidden" id="id_cliente_frecuente" name="id_cliente_frecuente">
												<div class="input-group">
													<input class="form-control" id="uuid_cliente" placeholder="Escanea o captura el UUID del QR" autocomplete="off"><div class="input-group-append"><button class="btn btn-primary" type="button" id="btn_buscar_cliente"><i class="fas fa-search"></i> Buscar cliente</button></div>
													
												</div>
                                                <div class="d-flex align-items-center mt-2">
                                                    <div class="alert alert-secondary mb-0 py-2 flex-grow-1" id="cliente_seleccionado">Público en general</div>
                                                    <button class="btn btn-outline-danger btn-sm ml-2 flex-shrink-0 d-none" type="button" id="btn_limpiar_cliente"><i class="fas fa-times" aria-hidden="true"></i> Quitar</button>
                                                </div>
												<div class="text-primary mt-2 d-none" id="cargando_cliente"><i class="fas fa-spinner fa-spin"></i> Buscando cliente...</div>
												<div class="alert alert-info mt-2 mb-0 py-2 d-none" id="programa_puntos">
                                                <div class="small">Puntos disponibles</div>
                                                <div id="saldo_puntos" class="font-weight-bold" style="font-size:2rem;line-height:1.2"></div>
                                                <small id="minimo_canje" class="d-block mt-1"></small>
													<div class="custom-control custom-switch toggle-ios mt-2"><input type="checkbox" class="custom-control-input" id="usar_puntos" name="usar_puntos" value="1"><label class="custom-control-label" for="usar_puntos">Usar puntos disponibles</label></div>
													<small id="detalle_canje"></small><input type="hidden" id="descuento_puntos" name="descuento_puntos" value="0">
												</div>
											</div>
											
											<div class="form-group ">
												<label >Domicilio Completo:</label>
												<textarea  id="domicilio" name="domicilio" class="form-control" required></textarea>
											</div>
											<div class="form-group d-none">
												<label>Destino:</label>
												<h5 id="destino_cotizar"></h5>
												<div class="row">
													<div class="col-sm-6">
														<label>Distancia:</label>
														<h5 id="distancia"></h5>
													</div>
													<div class="col-sm-6">
														<label>Tiempo:</label>
														<h5 id="tiempo"></h5>
													</div>
												</div>
												<input type="number" id="precio_viaje_input" name="precio_viaje_input" class="form-control text-right">
											</div>
											<div class="form-group row">
												<div class="col-sm-6 d-none">
													<label>Casetas:</label>
													<input type="number" id="casetas" name="casetas" class="form-control text-right">
												</div>
												<div class="col-sm-6 form-group">
													
													<label>Operador:</label>
													<?php echo generar_select($link, "conductores", "id_conductores", "nombre_conductores", false, false, true); ?>
													
												</div>
												<div class="col-sm-6 form-group">
													<label>CP Destino:</label>
													<input type="number" required id="cp_destino" name="cp_destino" class="form-control text-right">
												</div>
											</div>
											
											<div class="form-group row">
												<div class="col-sm-6 form-group">
													<label>Unidad (Num Eco):</label>
													<?php echo generar_select($link, "unidades", "num_eco", "num_eco", false, false, true); ?>
												</div>
												<div class="col-sm-6 form-group">
													<label>Número de Pasajeros:</label>
													<input type="number" required id="pasajeros" name="pasajeros" class="form-control text-right">
												</div>
											</div>
											
											<div class="row">
												<div class="col-sm-6 form-group">
													
													<label>Nombre Pasajero:</label>
													<input type="text" required id="nombre_pasajero" name="nombre_pasajero" class="form-control">
													
												</div>
												<div class="col-sm-6 form-group">
													<label>Teléfono:</label>
													<input type="tel" id="celular" name="celular" class="form-control text-right">
												</div>
											</div>
										</div>
									</div>
								</div>
								
								<div class="col-sm-5">
									<div class="card shadow">
										<div class="card-header">
											<h4><i class="fas fa-credit-card"></i> Datos de Pago</h4>
										</div>
										<div class="card-body">
											
											<div class="row">
												<!-- ===================== COLUMNA IZQUIERDA: FORMA Y MONTOS ===================== -->
												<div class="col-sm-6">
													<div class="form-group">
														<label>Forma de Pago:</label>
														<select class="form-control" id="forma_pago" name="forma_pago" required>
															<option value="">Elige..</option>
															<option value="Efectivo">Efectivo</option>
															<option value="Transferencia">Transferencia</option>
															<option value="Tarjeta">Tarjeta</option>
															<option value="Mixto">Mixto</option>
														</select>
													</div>
													
													
													<div class="form-group">
														<label>Efectivo:</label>
														<input type="number" step="any" id="efectivo" name="efectivo" class="form-control text-right" value="0">
													</div>
													<div class="form-group">
														<label>Tarjeta:</label>
														<input type="number" step="any" id="tarjeta" name="tarjeta" class="form-control text-right" value="0">
													</div>
													<div class="form-group">
														<label>Transferencia:</label>
														<input type="number" step="any" id="transferencia" name="transferencia" class="form-control text-right" value="0">
													</div>
													
													
													<!-- Factura / Botón -->
													<div class="form-group ">
														
														<label>Requiere Factura:</label>
														<select class="form-control" id="facturar" name="facturar" required>
															<option value="">Elige..</option>
															<option>SI</option>
															<option>NO</option>
														</select>
														
													</div>
													
													
												</div>
												
												<!-- ===================== COLUMNA DERECHA: RESUMEN Y DESCUENTOS ===================== -->
												<div class="col-sm-6">
													
													
													<div class="form-group">
														<label>Subtotal:</label>
														<div class="input-group input-group">
															<div class="input-group-prepend">
																<span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
															</div>
															<input readonly type="number" step="any" id="subtotal" name="subtotal" class="form-control text-right" value="0">
														</div>
													</div>
													
													<div class="form-group">
														<label for="cupon">Cupón:</label>
                                                        <div class="input-group"><input class="form-control text-uppercase" id="cupon" maxlength="64" autocomplete="off"><div class="input-group-append"><button type="button" class="btn btn-primary" id="aplicar_cupon">Aplicar</button></div></div>
                                                        <input type="hidden" id="codigo_cupon" name="codigo_cupon"><small id="mensaje_cupon" role="status"></small>
                                                        <button type="button" class="btn btn-link btn-sm px-0 d-none" id="quitar_cupon">Quitar cupón</button>
													</div>
													
													<div class="form-group" id="grupo_descuento">
														<label>Monto Descuento:</label>
														<div class="input-group input-group-lg">
															<div class="input-group-prepend">
																<span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
															</div>
															<input type="number" step="any" id="descuento" class="form-control text-right" value="0" readonly>
														</div>
													</div>
													
													<div class="form-group">
														<label>Total:</label>
														<div class="input-group input-group-lg">
															<div class="input-group-prepend">
																<span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
															</div>
															<input type="number" step="any" id="total" name="total" class="form-control text-right" value="0" readonly>
														</div>
													</div>
													
													<div class="form-group" id="div_terminal" style="display:none;">
														<label>Terminal:</label>
														
														
														<?php echo generar_select($link, "cat_terminales", "id_terminal", "terminal"); ?>
													</div>
													
												</div>
												
											</div>
											<div class="text-right">
												<?php
													if(in_array(dame_permiso("boletos.php", $link) , array('Administrador' , "Supervisor", "Escritura")) ){
													?>
													<button type="submit" class="btn btn-success btn-block btn-lg" id="btn_vender">
														<i class="fas fa-dollar-sign"></i> Vender
													</button>
													<?php
													}
												?>
											</div>
											
											
										</div>
									</div>
								</div>
							</div>
							
							
						</form>
						
					</div><!-- /.col-sm-10 -->
					
				</div><!-- /row-->
				
			</div><!-- /.container-fluid -->
			
			
			
		</div> 
		<!-- /.content-wrapper -->
	</div>
	<!-- /#wrapper -->
	
	<!-- Scroll to Top Button-->
	<a class="scroll-to-top rounded d-print-none" href="#page-top">
		<i class="fas fa-angle-up"></i>
	</a>
	
	<div class="d-print-block p-2" style="max-width:100mm;" hidden id="ticket" >
	</div>
	
	
	<?php include("../../scripts.php")?>
	<?php include("forms/form_cliente.php")?>
<div class="modal fade" id="modalBuscarCliente" tabindex="-1"><div class="modal-dialog modal-dialog-scrollable"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Buscar cliente frecuente</h5><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div><div class="modal-body"><input type="search" class="form-control mb-3" id="filtro_cliente_frecuente" placeholder="Nombre o teléfono"><div class="list-group" id="lista_clientes_frecuentes"><?php foreach ($clientes_frecuentes as $cliente_frecuente) { ?><button type="button" class="list-group-item list-group-item-action seleccionar_cliente_frecuente" data-uuid="<?= htmlspecialchars($cliente_frecuente['uuid_cliente']) ?>"><strong><?= htmlspecialchars($cliente_frecuente['nombre_completo']) ?></strong><br><small><?= htmlspecialchars($cliente_frecuente['clave_pais_cliente'].' '.$cliente_frecuente['telefono_cliente']) ?></small></button><?php } ?></div><p class="text-muted mt-2 mb-0 d-none" id="sin_clientes_frecuentes">No se encontraron clientes.</p></div></div></div></div>
	<div class="modal fade" id="modalEscanerQr" tabindex="-1" role="dialog" aria-labelledby="tituloEscanerQr" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document"><div class="modal-content">
			<div class="modal-header"><h5 class="modal-title" id="tituloEscanerQr">Escanear tarjeta de lealtad</h5><button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button></div>
			<div class="modal-body"><div id="lector_qr" style="width: 100%"></div><small class="form-text text-muted mt-2">Apunta la cámara al código QR del cliente.</small></div>
		</div></div>
	</div>
	<!-- /.content-wrapper-->
	<script src="../../lib/websocket-printer.js" > </script>
	<script >  var printService = new WebSocketPrinter(); </script>
	
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
	<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
	
	
	<script> 
		$(document).ready(function() {
			$('#destino').select2({"width": "100%",  tags: true});
			$('#num_eco').select2({"width": "100%"});
			$('#id_conductores').select2({"width": "100%"});
			// $("#form_cotizar").submit(cotizarViaje)
			
			// initMap() 
		});
	</script>	
	
	
	<script> 
		
	
		
		$('#descuento').on("input", sumarImportes)
		$('#id_descuento').change(function cambiarDescuento(){
			console.log("cambiarDescuento()")
			
			if($(this).val() == ""){
				
				$("#grupo_descuento").addClass("d-none")
			}
			else{
				$("#grupo_descuento").removeClass("d-none")
			}
			
			
		})
		
		var lectorQr = null;
        var cuponAplicado = null;
        var peticionCupon = null;
        var validandoCupon = false;
        var versionCupon = 0;
        var claveAuto = '';
        var versionAuto = 0;
        function consultarPromocionAutomatica() {
            var cliente = Number($('#id_cliente_frecuente').val()) || 0;
            var subtotal = Number($('#subtotal').val()) || 0;
            var clave = cliente + ':' + subtotal;
            if (clave === claveAuto) { return; }
            claveAuto = clave;
            var version = ++versionAuto;
            if (cuponAplicado && cuponAplicado.automatica) {
                cuponAplicado = null; $('#codigo_cupon, #cupon').val(''); $('#mensaje_cupon').text('');
            }
            if (!cliente || subtotal <= 0 || cuponAplicado || $('#usar_puntos').is(':checked')) { return; }
            $.post('consultas/promocion_automatica.php', {cliente:cliente, subtotal:subtotal}, null, 'json').done(function(r){
                if (version !== versionAuto || cuponAplicado || $('#usar_puntos').is(':checked') || validandoCupon) { return; }
                if (r.ok && r.promocion) {
                    cuponAplicado=r.promocion;
                    $('#codigo_cupon, #cupon').val(r.promocion.codigo);
                    $('#mensaje_cupon').text('Beneficio de primera compra aplicado automáticamente.');
                    sumarImportes();
                }
            });
        }
        function bloquearDescuentoOpuesto() {
            var disponible = configuracionLealtad && configuracionLealtad.activa && puntosCliente >= Number(configuracionLealtad.canje_minimo);
            if (cuponAplicado) { $('#usar_puntos').prop('checked', false); }
            $('#usar_puntos').prop('disabled', !disponible || !!cuponAplicado || validandoCupon);
            var usandoPuntos = $('#usar_puntos').is(':checked');
            $('#cupon, #aplicar_cupon').prop('disabled', usandoPuntos || validandoCupon);
            $('#quitar_cupon').toggleClass('d-none', !cuponAplicado);
        }
        $('#quitar_cupon').click(function(){ $('#cupon').val('').trigger('input'); });
        $('#cupon').on('input', function(){
            versionAuto++;
            versionCupon++; validandoCupon = false;
            if (peticionCupon) { peticionCupon.abort(); }
            cuponAplicado = null; $('#codigo_cupon').val(''); $('#mensaje_cupon').text(''); sumarImportes();
        });
        $('#aplicar_cupon').click(function(){
            versionAuto++;
            if ($('#usar_puntos').is(':checked') || validandoCupon) { return; }
            var codigo = $.trim($('#cupon').val()).toUpperCase();
            if (peticionCupon) { peticionCupon.abort(); }
            cuponAplicado = null; $('#codigo_cupon').val(''); sumarImportes();
            if (!codigo) { $('#mensaje_cupon').text('Captura un cupón.'); return; }
            var version = ++versionCupon;
            validandoCupon = true; bloquearDescuentoOpuesto();
            peticionCupon = $.post('consultas/validar_cupon.php', {codigo:codigo, subtotal:$('#subtotal').val(), cliente:$('#id_cliente_frecuente').val()}, null, 'json').done(function(r){
                if (version !== versionCupon) { return; }
                if (r.ok) { cuponAplicado = r; $('#codigo_cupon').val(r.codigo); $('#mensaje_cupon').text('Cupón aplicado.'); }
                else { $('#mensaje_cupon').text(r.mensaje); }
                sumarImportes();
            }).fail(function(xhr, estado){ if (version === versionCupon && estado !== 'abort') { $('#mensaje_cupon').text('No se pudo validar el cupón.'); } }).always(function(){ if (version === versionCupon) { validandoCupon = false; bloquearDescuentoOpuesto(); } });
        });
		var puntosCliente = 0;
		var configuracionLealtad = null;

		function limpiarCliente() {
            claveAuto = ''; versionAuto++;
            if (cuponAplicado && cuponAplicado.automatica) { cuponAplicado=null; $('#codigo_cupon, #cupon').val(''); $('#mensaje_cupon').text(''); }
            $('#btn_limpiar_cliente').addClass('d-none');
			$("#id_cliente_frecuente, #uuid_cliente, #nombre_pasajero, #celular").val("");
			$("#cliente_seleccionado").removeClass("alert-success alert-danger").addClass("alert-secondary").text("Público en general");
			puntosCliente = 0;
			configuracionLealtad = null;
			$("#usar_puntos").prop("checked", false).prop("disabled", true);
			$("#programa_puntos").addClass("d-none");
			$("#descuento_puntos").val(0);
			sumarImportes();
		}

		function actualizarCanjePuntos() {
			var descuentoPuntos = 0;
			if ($("#usar_puntos").is(":checked") && configuracionLealtad && configuracionLealtad.activa && puntosCliente >= configuracionLealtad.canje_minimo) {
				descuentoPuntos = puntosCliente * configuracionLealtad.valor_punto;
				$("#detalle_canje").text("Descuento por puntos: $" + descuentoPuntos.toFixed(2));
			} else {
				$("#detalle_canje").text("");
			}
			$("#descuento_puntos").val(descuentoPuntos.toFixed(2));
			sumarImportes();
		}

		function cargarClientePorUuid(uuid) {
			uuid = $.trim(uuid);
			if (!uuid) { limpiarCliente(); return; }
			$("#cargando_cliente").removeClass("d-none");
			$.ajax({ url: "consultas/buscar_cliente_uuid.php", method: "POST", dataType: "json", data: { uuid: uuid } })
			.done(function(respuesta) {
				if (!respuesta.ok) {
					limpiarCliente();
					$("#uuid_cliente").val(uuid);
					$("#cliente_seleccionado").removeClass("alert-secondary alert-success").addClass("alert-danger").text(respuesta.mensaje || "Cliente no encontrado");
					return;
				}
				$("#id_cliente_frecuente").val(respuesta.cliente.id);
                $('#btn_limpiar_cliente').removeClass('d-none');
				$("#uuid_cliente").val(respuesta.cliente.uuid);
				$("#nombre_pasajero").val(respuesta.cliente.nombre);
				$("#celular").val(respuesta.cliente.telefono);
				$("#cliente_seleccionado").removeClass("alert-secondary alert-danger").addClass("alert-success").text(respuesta.cliente.nombre + " · " + respuesta.cliente.telefono);
				puntosCliente = Number(respuesta.cliente.puntos || 0);
				configuracionLealtad = respuesta.lealtad || null;
				if (configuracionLealtad && configuracionLealtad.activa) {
					var disponible = puntosCliente >= Number(configuracionLealtad.canje_minimo);
                    $("#saldo_puntos").text(puntosCliente.toLocaleString('es-MX'));
                    $("#minimo_canje").text("Mínimo para canje: " + Number(configuracionLealtad.canje_minimo).toLocaleString('es-MX') + " puntos");
					$("#usar_puntos").prop("checked", false).prop("disabled", !disponible);
					$("#programa_puntos").removeClass("d-none");
				} else { $("#programa_puntos").addClass("d-none"); }
				actualizarCanjePuntos();
			})
			.fail(function() { $("#cliente_seleccionado").removeClass("alert-secondary alert-success").addClass("alert-danger").text("No fue posible consultar al cliente"); })
			.always(function() { $("#cargando_cliente").addClass("d-none"); });
		}

		$("#uuid_cliente").on("change blur", function () { cargarClientePorUuid($(this).val()); });
		$("#btn_limpiar_cliente").click(limpiarCliente);
		$("#btn_buscar_cliente").click(function () { $("#filtro_cliente_frecuente").val(""); $(".seleccionar_cliente_frecuente").show(); $("#sin_clientes_frecuentes").addClass("d-none"); $("#modalBuscarCliente").modal("show"); });
		$("#filtro_cliente_frecuente").on("input", function () { var texto = $.trim($(this).val()).toLowerCase(); var visibles = 0; $(".seleccionar_cliente_frecuente").each(function () { var coincide = $(this).text().toLowerCase().indexOf(texto) !== -1; $(this).toggle(coincide); if (coincide) { visibles++; } }); $("#sin_clientes_frecuentes").toggleClass("d-none", visibles !== 0); });
		$(document).on("click", ".seleccionar_cliente_frecuente", function () { var uuid = $(this).data("uuid"); $("#modalBuscarCliente").modal("hide"); $("#uuid_cliente").val(uuid); cargarClientePorUuid(uuid); });
		$("#usar_puntos").change(actualizarCanjePuntos);
		$("#btn_escanear_qr").click(function () {
			$("#modalEscanerQr").modal("show");
			lectorQr = new Html5Qrcode("lector_qr");
			lectorQr.start({ facingMode: "environment" }, { fps: 10, qrbox: { width: 250, height: 250 } }, function (texto) {
				lectorQr.stop().then(function () { $("#modalEscanerQr").modal("hide"); $("#uuid_cliente").val(texto); cargarClientePorUuid(texto); });
			}).catch(function () { $("#cliente_seleccionado").removeClass("alert-secondary alert-success").addClass("alert-danger").text("No se pudo iniciar la cámara"); });
		});
		$("#modalEscanerQr").on("hidden.bs.modal", function () { if (lectorQr) { lectorQr.stop().catch(function(){}); lectorQr = null; } });
		
		function guardarCliente(){
			console.log("guardarCliente()")
			
			var boton = $("#form_cliente").find("submit");
			var icono = boton.find("i")
			
			boton.prop("disabled", true)
			icono.toggleClass("fa-save  fa-spinner spin")
			
			$.ajax({
				
				url:"consultas/guardar_cliente.php",
				
				type:"POST",
				
				data: $("#form_cliente") .serialize(),
				
				dataType:"json",
				
				success:function(r){
					
					if(r.ok){
						
						var option = new Option(
						respuesta.nombre,
						respuesta.id_cliente,
						true,
						true
						);
						
						$(option)
						.attr("data-nombre", respuesta.nombre)
						.attr("data-telefono", respuesta.telefono);
						
						$("#id_cliente")
						.append(option)
						.trigger("change");
						
						$("#modalCliente").modal("hide");
						
					}
					
				}
				
				}).always(function(){
				
				boton.prop("disabled", true)
				icono.toggleClass("fa-save  fa-spinner spin")
				
			});
			
		}
		$(document).ready(function() {
			$('#destino').select2({"width": "resolve",  tags: true});
			$('#casetas').keyup(sumarImportes);
			$("#forma_pago").change(cambiarFormaPago)
			$("#total").on("input", cambiarFormaPago)
			$("#form_vender").submit(venderBoleto)
			$("#form_vender").keydown(function(event){
				console.log("keydown")
				if(event.key == 'Enter'){
					
					event.preventDefault();
				}
				
			})
			$("#tipo_unidad").change(cambiarUnidad)
			// $("#btn_cotizar").click(cotizarViaje)
			$('#destino').change(cotizarViaje)
			
			// initMap() 
			
			
			
			$('#intercambiar').click(function() {
				console.log("intercambiar")
				// Animación de intercambio
				// $('#div_origen').animate({ top: '50px' }, 1000);
				// $('#div_destino').animate({ top: '-50px' }, 1000);
				
				// Intercambio de campos de texto
				setTimeout(function() {
					
					var $campo1 = $('#div_origen');
					var $campo2 = $('#div_destino');
					
					if ($campo2.next().is($campo1)) {
						// Si destino está debajo de origen, cambia el orden esta es la posicion por default
						$("#label_destino").text("Destino:")
						$("#label_origen").text("Origen:")
						$("#destino").attr("name", "destino")
						$("#origen").attr("name", "origen")
						$campo2.insertAfter($campo1);
						
					} 
					else {
						// Si destino está arriba de origen, cambia el orden
						
						$("#label_destino").text("Origen:")
						$("#label_origen").text("Destino:")
						$("#destino").attr("name", "origen")
						$("#origen").attr("name", "destino")
						
						
						$campo1.insertAfter($campo2);
					}
					
					
					
					// $('#div_origen').insertAfter('#div_destino');
					// $('#div_origen').animate({ top: '0' }, 500);
					// $('#div_destino').animate({ top: '0' }, 500);
				}, 500);
			});
		});
		
		function validarMontos(){
			console.log("validarMontos()")
			var suma_pagos = Number($("#efectivo").val()) + Number($("#tarjeta").val())  + Number($("#transferencia").val());
			var total = Number($("#total").val());
			
			valido = total == suma_pagos ?  true : false;
			
			
			
			return valido;
			
		}
		
		
		function cambiarUnidad(event){ 
			let tarifa = $(this).find("option:selected").data("tarifa") 
			
			$("#tarifa").val(tarifa)
			cotizarViaje();
		}
		
		function cotizarViaje(event){ 
			// event.preventDefault();
			
			
			$.ajax({
				url: 'consultas/cotizar_viaje.php',
				method: 'GET',
				dataType: 'JSON',
				data:{
					"destino": $("#destino").val(),						
				}
				}).done(function(respuesta){
				
				
				if(respuesta.length == 0){
					alert("El destino no se encuentra, cotizar manualmente")
					$("#precio_viaje_input").val(0)
					sumarImportes();
					return;
				}
				
				//si es tarifa normal, precio si es ejecutiva precio_ejecutiva
				if($("#tipo_unidad").find("option:selected").data("tipo") == "sedan"){
					$("#subtotal").val(respuesta[0].precio)
					$("#precio_viaje_input").val(respuesta[0].precio)
					
				}
				else{
					$("#subtotal").val(respuesta[0].precio)
					$("#precio_viaje_input").val(respuesta[0].precio_ejecutiva)
				}
				
				sumarImportes();
				
				
				}).fail(function(xhr, error, errnum){
				
				alertify.error('Ocurrio un error' + errnum);
				
				}).always(function(){
				// boton.prop('disabled',false);
				// icono.toggleClass('fa-dollar-sign fa-spinner fa-spin');
			});
			
			
		}
		
		function cambiarFormaPago(){
			console.log("cambiarFormaPago()");
			
			var forma_pago = $("#forma_pago").val();
			var total = $("#total").val();
			
			var visible = false;
			var requerido = false;
			
			var efectivo = 0;
			var tarjeta = 0;
			var transferencia = 0;
			
			switch(forma_pago){
				
				case "Efectivo":
				efectivo = total;
				break;
				
				case "Tarjeta":
				tarjeta = total;
				
				visible = true;
				requerido = true;
				break;
				
				case "Transferencia":
				transferencia = total;
				break;
				
				case "Mixto":
				efectivo = total;
				visible = true;
				break;
			}
			
			
			$("#efectivo").val(efectivo);
			$("#tarjeta").val(tarjeta);
			$("#transferencia").val(transferencia);
			
			
			// Mostrar u ocultar terminal
			if(visible){
				$("#div_terminal").show();
			}
			else{
				$("#div_terminal").hide();
			}
			
			// Hacer requerido o no requerido
			$("#id_terminal").prop("required", requerido);
		}
		
		/**
			* @license
			* Copyright 2019 Google LLC. All Rights Reserved.
			* SPDX-License-Identifier: Apache-2.0
		*/
		function initMap() {
			const directionsService = new google.maps.DirectionsService();
			const directionsRenderer = new google.maps.DirectionsRenderer();
			const map = new google.maps.Map(document.getElementById("map"), {
				zoom: 13,
				center: { lat: 19.7346514, lng: -99.0132095 }
				//@19.7346514,-99.0132095,13z
				
			});
			
			directionsRenderer.setMap(map);
			
			const onChangeHandler = function () {
				calculateAndDisplayRoute(directionsService, directionsRenderer);
			};
			
			document.getElementById("origen").addEventListener("change", onChangeHandler);
			document.getElementById("destino").addEventListener("change", onChangeHandler);
			document.getElementById("btn_cotizar").addEventListener("click", onChangeHandler);
			
			
		}
		
		function calculateAndDisplayRoute(directionsService, directionsRenderer) {
			console.log("calculateAndDisplayRoute()")
			console.log("directionsService", directionsService)
			console.log("directionsRenderer", directionsRenderer)
			
			if($("#destino").val() == ""){
				
				alert("Elige un destino");
				return false;
			}
			
			var boton = $("#btn_cotizar");
			var icono = boton.find("i")
			
			boton.prop("disabled", true)
			icono.toggleClass("fa-arrow-right  fa-spinner spin")
			
			directionsService
			.route({
				origin: {
					query: document.getElementById("origen").value,
				},
				destination: {
					query: document.getElementById("destino").value,
				},
				travelMode: google.maps.TravelMode.DRIVING,
			})
			.then((response) => {
				console.log("response", response)
				console.log("Distancia", response.routes[0].legs[0].distance.value)
				// alert ("Distancia: ", response.routes[0].legs[0].distance.value)
				window.location.href = "#map";
				
				var distancia = response.routes[0].legs[0].distance;
				var tiempo  =  response.routes[0].legs[0].duration;
				
				
				var casetas = 0;
				var tarifa = Number($("#tarifa").val());
				var precio_viaje = Math.round( Number(100 + ( distancia.value/1000 * tarifa ))) ;
				var precio_competencia = Number($("#destino option:selected").data("precio"));
				var destino = $("#destino option:selected").text();
				
				console.log("precio_viaje", precio_viaje)
				
				$("#link_whatsapp").attr("href", "https://wa.me/+525549050026?text=Hola,%20quiero%20viajar%20a%20"+ destino );
				$("#destino_cotizar").text(destino);
				$("#precio_competencia").text(precio_competencia.toLocaleString('es-MX', { style: 'currency', currency: 'MXN' }));
				$("#precio_viaje_input").val(precio_viaje);
				$("#precio_viaje").text(precio_viaje.toLocaleString('es-MX', { style: 'currency', currency: 'MXN' }));
				$("#distancia").text(distancia.text);
				$("#tiempo").text(tiempo.text);
				
				sumarImportes()
				
				
				directionsRenderer.setDirections(response);  
				
				boton.prop("disabled", false)
				icono.toggleClass("fa-arrow-right  fa-spinner spin")
				
			})
			.catch(function (e){ 
				
				
				console.log("error", e)
				window.alert("Ha fallado la ruta, " + e.message);
				boton.prop("disabled", false)
				icono.toggleClass("fa-arrow-right  fa-spinner spin")
			});
		}
		
		window.initMap = initMap;
		
		function sumarImportes(){
            consultarPromocionAutomatica();
			bloquearDescuentoOpuesto();
			
			let precio_viaje = Number($("#precio_viaje_input").val());
			let casetas = Number($("#casetas").val());
			var base = Math.max(0, Number($('#subtotal').val()) || 0);
            var promocion = cuponAplicado ? Math.min(base, Math.max(0, cuponAplicado.tipo === 'Porcentaje' ? base * cuponAplicado.valor/100 : cuponAplicado.valor)) : 0;
            promocion = Math.round(promocion * 100)/100;
            var puntos = $('#usar_puntos').is(':checked') && configuracionLealtad && configuracionLealtad.activa && puntosCliente >= configuracionLealtad.canje_minimo ? Math.min(base - promocion, Math.round(puntosCliente * configuracionLealtad.valor_punto * 100)/100) : 0;
            $('#descuento_puntos').val(puntos.toFixed(2));
            if ($('#usar_puntos').is(':checked')) { $('#detalle_canje').text('Descuento por puntos: $' + puntos.toFixed(2)); }
            let descuento = promocion + puntos;
            $('#descuento').val(descuento.toFixed(2));
			
			console.log("precio_viaje", precio_viaje);
			console.log("casetas", casetas);
			console.log("descuento", descuento);
			
			// let subtotal = precio_viaje + casetas ;
			let subtotal =  $("#subtotal").val() ;
			console.log("subtotal", subtotal);
			let total = Math.max(0, subtotal - descuento);
			
			console.log("total", total);
			
			// $("#subtotal").val(subtotal.toFixed(2))
			$("#total").val(total.toFixed(2))
			cambiarFormaPago();
		}
		
		function venderBoleto(event){
			event.preventDefault();
			
			
			if(!validarMontos()){
				alert("Los montos del pago no coinciden con el total, favor de verificar")
				return false;
			}
			
			if($("#destino").val() == ""){
				alert("Elige un destino")
				return false;
			}
			
			var boton = $(this).find(":submit");
			var icono = boton.find('i');
			
			
			boton.prop('disabled',true);
			icono.toggleClass('fa-dollar-sign fa-spinner fa-spin');
			
			
			$.ajax({
				url: 'consultas/guardar_venta.php',
				method: 'POST',
				dataType: 'JSON',
				data: $("#form_vender").serialize()
				}).done(function(respuesta){
				if(respuesta.error){
					alert(respuesta.error)
					return
				}
				if(respuesta.estatus == "success"){
					alertify.success('Venta Guardada');
					
					// $("#form_cotizar")[0].reset();
					// $("#form_vender")[0].reset();
					
					imprimirTicket(respuesta.folio).done(function(){
						
						window.location.reload();
					})
					
					}else{
					
					alert(respuesta.error);
				}
				}).fail(function(xhr, error, errnum){
				
				alertify.error('Ocurrio un error' + errnum);
				
				}).always(function(){
				boton.prop('disabled',false);
				icono.toggleClass('fa-dollar-sign fa-spinner fa-spin');
			});
			
			
		}
		
		
		
		$("#btn_prueba").click(imprimirPrueba)
		
		function imprimirPrueba(){
			console.log("imprimirTicket()");
			
			return $.ajax({
				url: "impresion/imprimir_prueba.php" ,
				}).done(function (respuesta){
				
				printService.submit({
					'type': 'LABEL',
					'raw_content': respuesta
				});
				
				
				}).always(function(){
				
			});
		}
		
		function imprimirTicket(folio){
			console.log("imprimirTicket()");
			
			return $.ajax({
				url: "impresion/imprimir_boleto.php" ,
				data:{
					"folio" : folio
				}
				}).done(function (respuesta){
				
				if(window.AppInventor){
					// alert("Android",respuesta )
					window.AppInventor.setWebViewString(atob(respuesta));
				}
				else{
					try{
						printService.submit({
							'type': 'LABEL',
							'raw_content': respuesta
						});
					}
					catch(error){
						alert(error + "Error al imprimir")
					}
				}
				
				
				}).always(function(){
				
			});
		}
		
	</script>
	
	<script
	src="https://maps.googleapis.com/maps/api/js?key=XX&callback=initMap&v=weekly"
	defer
	></script>	
	
</body>
</html>																														
