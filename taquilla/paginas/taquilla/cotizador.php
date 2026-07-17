<?php
	include("../../paginas/login/login_check.php");
	include('../../conexi.php');
	include('../../funciones/generar_select.php');
	// include_once('../../funciones/dame_permiso.php');
	include("consultas/get_colonias.php");
	include("consultas/get_api_key.php");
	
	$destinos = getDestinos($link);
	// $destinos = array();
	$api_key_toll_guru = getAPIKey($link, "tollguru");
	$api_key_google = getAPIKey($link, "google");
	
	
	
	
	$link = Conectarse();
	$nombre_pagina = "Cotizador";
	
	
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
			
			
		</style>
		
	</head>
	<body id="page-top">
		<?php include("../../navbar.php")?>
		<div id="wrapper" class="">
			<?php include("../../menu.php")?>	
			<div id="content-wrapper">		
				<div class="container-fluid">		
					
					
					
					<div class="row">
						<div class="col-md-6 col-lg-2">
							<div class="slider_form">
								<form id="form_cotizar">
									<div class="form-group d-none">
										<label class="">Tipo de Unidad:</label>
										<select class="form-control" id="tipo_unidad">
											
											<option data-tarifa="13" data-tipo="sedan" selected >Sedan (4 Pasajeros)</option>
											<option data-tarifa="18" data-tipo="ejecutiva" >Nissan NV350 (12 Pasajeros)</option>
											<option data-tarifa="18" data-tipo="ejecutiva">VW Crafter (18 Pasajeros)</option>
											<option data-tarifa="12" data-tipo="sedan" >Suburvan (7 pasajeros)</option>
											<option data-tarifa="18" data-tipo="ejecutiva" >Sprinter (22 pasajeros)</option>
										</select>
									</div>
									
									<div class="">
										<label class="">Origen:</label>
										<input class="form-control" id="origen" type="text" placeholder="AIFA"  value="AIFA">
									</div>
									<div class="form-group">
										<label class="">Destino:</label>
										<select class="form-control" id="destino" name="destino" required>
											<option value="" required >Elige un destino:</option>
											<?php
												foreach($destinos AS $i=> $destino){
												?>
												<option ><?php echo $destino["destino"];?></option>
												<?php
												}
											?>
										</select>
									</div>
									
									<div class="form-group d-none">
										<label class="">Trafico :</label>
										<select class="form-control" id="trafico">
											
											<option value="bestguess">Trafico Actual</option>
											<option value="pessimistic">Mucho Tráfico</option>
											<option value="optimistic">Poco Tráfico</option>
											
										</select>
									</div>
									<div class="form-group d-none">
										<label class="">
											<input type="checkbox"  id="evitar_peajes" > Evitar Peajes
										</label>
										
									</div>
									<div class="form-group d-none">
										<label class="">Tarifa Base por KM :</label>
										<input class="form-control" id="tarifa" type="number" value="19" >
									</div>
									<div class="fixed-bottom">
										<button id="btn_cotizar" type="button"  class="btn btn-success btn-block"> 
											<i class="fas fa-arrow-right"></i> Cotizar 
										</button>
									</div>
								</form>
							</div>
						</div>
						
						
						<div class="col-lg-6 d-lg-block">
							<div id="map"></div>
							
							
							<label class="">Destino:</label>
							
							<h5 id="destino_cotizar"></h5>
							<div class="row ">
								<div class="col-sm-6 ">
									Distancia:<h5 id="distancia"></h5>
								</div>
								<div class="col-sm-6 ">
									Tiempo:<h5 id="tiempo"></h5>
								</div>
							</div>
							
							
							
							<div class="row ">
								<div class="col-sm-6 text-success">
									Costo:
									<div class="h5">
										<span id="precio_viaje"></span>
									</div>
								</div>
								<div class="col-sm-6 text-success h3">
									<a href="" target="_blank" id="ruta_maps"> Ver Ruta <i class="fas fa-map-marker-alt"></i></a>
								</div>
								<div class="col-sm-6 text-danger d-none">
									Competencia
									<div class="h4">
										<span id="precio_competencia"></span>
									</div>
								</div>
							</div>
							
						</div>
						
						
						
						<div class="col-md-6 col-lg-4 d-none">
							<form id="form_vender" autocomplete="off" class="was-validated">
								
								
								<div class="form-group d-none">
									<label>Precio :</label>
									<input type="number" id="precio_viaje_input" name="precio_viaje_input" class="form-control text-right">
								</div>
								
								<div class="form-group row">
									<div class="col-sm-6">
										<label>Casetas:</label>
										<input type="number" id="casetas" name="casetas" class="form-control text-right">
									</div>
									<div class="col-sm-6">
										<label>CP Destino:</label>
										<input type="number" required id="cp_destino" name="cp_destino" class="form-control text-right">
									</div>
								</div>
								
								
								<div class="form-group">
									<label>Total:</label>
									<input type="number" id="total" name="total" class="form-control text-right" step="any" required min="1">
								</div>
								
								<div class="form-group row">
									<div class="col-sm-6">
										<label>Num Eco:</label>
										<?php 
											echo generar_select($link, "unidades", "num_eco", "num_eco", false, false, true);
										?>
									</div>
									<div class="col-sm-6">
										<label>Num Pasajeros:</label>
										<input type="number" required id="pasajeros" name="pasajeros" class="form-control text-right">
									</div>
								</div>
								<div class="form-group row">
									<div class="col-sm-6">
										<label>Nombre Pasajero:</label>
										<input type="text" required id="nombre_pasajero" name="nombre_pasajero" class="form-control mayus">
									</div>
									<div class="col-sm-6">
										<label>#Teléfono:</label>
										<input type="tel"  id="celular" name="celular" class="form-control">
									</div>
									
								</div>
								
								<div class="row">
									<div class="form-group col-sm-6">
										<label class="">Forma de Pago:</label>
										<select class="form-control" id="forma_pago" name="forma_pago" required>
											<option value="" >Elige..</option>
											<option  >Efectivo</option>
											<option  >Transferencia</option>
											<option >Tarjeta</option>
											<option  >Mixto</option>
										</select>
									</div>
									<div class="form-group col-sm-6">
										<label class="">Operador:</label>
										<?php 
											echo generar_select($link, "conductores", "id_conductores", "nombre_conductores", false, false, true);
										?>
									</div>
								</div>
								<div class="form-group row hidden">
									<div class="col-sm-4">
										<label>Efectivo:</label>
										<input type="number" step="any" id="efectivo" name="efectivo" class="form-control text-right">
									</div>
									<div class="col-sm-4">
										<label>Tarjeta:</label>
										<input type="number"  step="any" id="tarjeta" name="tarjeta" class="form-control text-right">
									</div>
									<div class="col-sm-4">
										<label>Transferencia:</label>
										<input type="number"  step="any" id="transferencia" name="transferencia" class="form-control text-right">
									</div>
								</div>
								
								
								<button class="btn btn-success" id="btn_vender"  >
									<i class="fas fa-dollar-sign"></i>  Vender
								</button>
								
								
							</form>
						</div>
						
					</div>
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
		<!-- /.content-wrapper-->
		<script src="../../lib/websocket-printer.js" > </script>
		<script >  var printService = new WebSocketPrinter(); </script>
		
		<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
		<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
		
		<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
		<script> 
			$(document).ready(function() {
				$('#destino').select2({"width": "resolve",  tags: true});
				$('#num_eco').select2({"width": "resolve",  tags: true});
				$('#id_conductores').select2({"width": "resolve",  tags: true});
				
			// $("#form_cotizar").submit(cotizarViaje)
			
			// initMap() 
	});
</script>	

<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>

<script
src="https://maps.googleapis.com/maps/api/js?key=<?php echo $api_key_google["api_key"];?>&callback=initMap&v=weekly"
defer
></script>	
<script> 
	$(document).ready(function() {
		$('#destino').select2({"width": "resolve",  tags: true});
		$('#casetas').keyup(sumarImportes);
		
		$('#btn_calcular').click(calcularDistancia);
		
		$("#forma_pago").change(cambiarFormaPago)
		$("#form_vender").submit(venderBoleto)
		$("#form_vender").keydown(function(event){
			console.log("keydown")
			if(event.key == 'Enter'){
				
				event.preventDefault();
			}
			
		})
		$("#tipo_unidad").change(cambiarUnidad)
		$("#total").keyup(cambiarFormaPago)
		// $("#btn_cotizar").click(cotizarViaje)
		// $('#destino').change(cotizarViaje)
		
		// initMap() 
	});
	
	
	function obtenerCodigoPostal(destino) {
		var geocoder = new google.maps.Geocoder();
		
		geocoder.geocode({ address: destino }, function (results, status) {
			if (status === google.maps.GeocoderStatus.OK) {
				if (results[0].address_components) {
					for (var i = 0; i < results[0].address_components.length; i++) {
						var addressComponent = results[0].address_components[i];
						if (addressComponent.types.includes("postal_code")) {
							var codigoPostal = addressComponent.long_name;
							console.log("Código Postal:", codigoPostal);
							$("#cp_destino").val(codigoPostal)
							return codigoPostal;
						}
					}
				}
				} else {
				console.log("Geocoding falló debido a: " + status);
				
			}
		});
	}
	
	
	
	
	
	
	
	function cambiarUnidad(event){ 
		let tarifa = $(this).find("option:selected").data("tarifa") 
		
		$("#tarifa").val(tarifa)
		cotizarViaje();
	}
	
	function calcularDistancia(event){
		
		if($("#destino").val() == ""){
			
			alert("Elige un destino");
			return false;
		}
		
		var origin = $("#origen").val();
		var destination = $("#destino").val();
		var departure_time = 'now'; // Otra opción puede ser una marca de tiempo en milisegundos
		
		var url = 'https://maps.googleapis.com/maps/api/directions/json?origin=' + origin +
		'&destination=' + destination +
		'&departure_time=' + departure_time +
		'&key=<?php echo $api_key_google["api_key"];?>';
		
		$.ajax({
			url: url,
			method: 'GET',
			dataType: 'JSON',
			
			}).done(function(respuesta){
			console.log("calcularDistancia()")
			console.log(respuesta)
		})
	}
	
	
	function cotizarViaje(event){
		console.log("cotizarViaje()")
		
		// obtenerCodigoPostal($("#destino").val())
		
		$.ajax({
			url: 'consultas/cotizar_viaje.php',
			method: 'GET',
			dataType: 'JSON',
			data:{
				"destino": $("#destino").val(),						
			}
			}).done(function(respuesta){
			
			
			// if(respuesta.length == 0){
			// alert("El destino no se encuentra, cotizar manualmente")
			// $("#precio_viaje_input").val(0)
			// sumarImportes();
			// return;
			// }
			
			//si es tarifa normal, precio si es ejecutiva precio_ejecutiva
			// if($("#tipo_unidad").find("option:selected").data("tipo") == "sedan"){
			
			$("#precio_viaje_input").val(respuesta[0].precio)
			
			ruta_maps = "https://www.google.com/maps/dir/"+origin+ "/"+ destination ;
			
			$("#ruta_maps").attr("href", ruta_maps);
			// }
			// else{
			// $("#precio_viaje_input").val(respuesta[0].precio_ejecutiva)
			// }
			
			sumarImportes();
			
			
			}).fail(function(xhr, error, errnum){
			
			alertify.error('Ocurrio un error' + errnum);
			
			}).always(function(){
			// boton.prop('disabled',false);
			// icono.toggleClass('fa-dollar-sign fa-spinner fa-spin');
		});
		
		
	}
	
	/**
		* @license
		* Copyright 2019 Google LLC. All Rights Reserved.
		* SPDX-License-Identifier: Apache-2.0
	*/
	
	var map;
	function initMap() {
		const directionsService = new google.maps.DirectionsService();
		const directionsRenderer = new google.maps.DirectionsRenderer({
			suppressMarkers: true
		});
		map = new google.maps.Map(document.getElementById("map"), {
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
			provideRouteAlternatives: true,
			origin: {
				query: document.getElementById("origen").value,
			},
			destination: {
				query: document.getElementById("destino").value,
			},
			travelMode: google.maps.TravelMode.DRIVING,
			"drivingOptions": {
				"departureTime" : new Date(),
				"trafficModel" : $("#trafico").val()
			},
			avoidTolls: $("#evitar_peajes").prop("checked")
		})
		.then((response) => {
			console.log("response", response)
			console.log("Distancia", response.routes[0].legs[0].distance.value)
			// alert ("Distancia: ", response.routes[0].legs[0].distance.value)
			window.location.href = "#map";
			
			var distancia = response.routes[0].legs[0].distance;
			var tiempo  =  response.routes[0].legs[0].duration;
			var trafico  =  response.routes[0].legs[0].duration_in_traffic;
			var polyline  =  response.routes[0].overview_polyline;
			var waypoints  =  response.geocoded_waypoints;
			
			
			var casetas = 0;
			var tarifa = Number($("#tarifa").val());
			var precio_viaje = Math.round( Number( ( distancia.value/1000 * tarifa ))) ;
			var precio_competencia = Number($("#destino option:selected").data("precio"));
			var destino = $("#destino option:selected").text();
			
			console.log("precio_viaje", precio_viaje)
			
			$("#link_whatsapp").attr("href", "https://wa.me/+525549050026?text=Hola,%20quiero%20viajar%20a%20"+ destino );
			$("#destino_cotizar").text(destino);
			$("#precio_competencia").text(precio_competencia.toLocaleString('es-MX', { style: 'currency', currency: 'MXN' }));
			$("#precio_viaje_input").val(precio_viaje);
			$("#precio_viaje").text(precio_viaje.toLocaleString('es-MX', { style: 'currency', currency: 'MXN' }));
			$("#distancia").text(distancia.text);
			$("#tiempo").text(trafico.text);
			
			ruta_maps = "https://www.google.com/maps/dir/"+$("#origen").val()+ "/"+ $("#destino").val() ;
			
			$("#ruta_maps").attr("href", ruta_maps);
			
			sumarImportes();
			
			// calcularCasetas(polyline);
			// calcularCasetasByID(waypoints);
			
			var routes = response.routes;
			/*
				// Recorrer las rutas alternativas y configurar un DirectionsRenderer para cada una
				for (var i = 0; i < routes.length; i++) {
				var route = routes[i];
				console.log("Ruta " + i + " " + route.summary)
				// Crear un nuevo DirectionsRenderer para cada ruta
				var renderer = new google.maps.DirectionsRenderer();
				
				// Configurar el DirectionsRenderer en el mapa
				renderer.setMap(map);
				
				// Asignar la ruta a mostrar al DirectionsRenderer
				renderer.setDirections(response);
				
				// Asignar opciones adicionales al DirectionsRenderer si es necesario
				// renderer.setOptions({ suppressMarkers: true, polylineOptions: { strokeColor: "red" } });
				}
			*/
			
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
		
		let precio_viaje = Number($("#precio_viaje_input").val());
		let casetas = Number($("#casetas").val());
		
		let total = precio_viaje + casetas;
		
		
		
		$("#total").val(total.toFixed(2))
		$("#forma_pago").change()
		
	}
	
	function cambiarFormaPago(){
		console.log("cambiarFormaPago()")
		var forma_pago = $("#forma_pago").val()
		var total = $("#total").val();
		
		switch(forma_pago){
			
			case "Efectivo":
			var efectivo = total;
			var tarjeta = 0;
			var transferencia = 0;
			
			break;
			
			case "Tarjeta":
			var tarjeta = total;
			var efectivo = 0;
			var transferencia = 0;
			break;
			
			case "Transferencia":
			var transferencia = total;
			var efectivo = 0;
			var tarjeta = 0;
			break;
			
			case "Mixto":
			
			var efectivo = total;
			var tarjeta = 0;
			var transferencia = 0;
			
			break;
			
		}
		
		
		$("#efectivo").val(efectivo)
		$("#tarjeta").val(tarjeta)
		$("#transferencia").val(transferencia)
		
	}
	
	function venderBoleto(event){
		event.preventDefault();
		
		if($("#destino").val() == ""){
			
			alert("Elige un destino")
			$("#destino").focus()
			
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
			data:{
				"origen": $("#origen").val(),
				"destino": $("#destino").val(),
				"cp_destino": $("#cp_destino").val(),
				"forma_pago": $("#forma_pago").val(),
				"pasajeros": $("#pasajeros").val(),
				"celular": $("#celular").val(),
				"efectivo": $("#efectivo").val(),
				"transferencia": $("#transferencia").val(),
				"tarjeta": $("#tarjeta").val(),
				"nombre_pasajero": $("#nombre_pasajero").val(),
				"id_conductores": $("#id_conductores").val(),
				"total": $("#total").val(),
				"num_eco": $("#num_eco").val()
				
			}
			}).done(function(respuesta){
			if(respuesta.estatus == "success"){
				alertify.success('Venta Guardada');
				
				// $("#form_cotizar")[0].reset();
				// $("#form_vender")[0].reset();
				
				// if(window.AppInventor){
				// window.AppInventor.setWebViewString(atob(respuesta));
				// }
				
				
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
	
	function calcularCasetasByID(waypoints){
		console.log("calcularCasetasByID()")
		
		const json_data = {
			"serviceProvider" : "google",
			"from": {
				"place_id":  waypoints[0].place_id
			},
			"to": {
				"place_id":  waypoints[1].place_id
			}
		} ;
		
		const settings = {
			"async": true,
			"crossDomain": true,
			"url": "https://dev.TollGuru.com/v1/calc/gmaps",
			"method": "POST",
			"headers": {
				"content-type": "application/json",
				"x-api-key": "<?php echo $api_key_toll_guru["api_key"]?>"
				
			},
			"processData": false,
			"data": JSON.stringify(json_data)
		};
		
		$.ajax(settings).done(function (response) {
			console.log(response);
			if(response.status == "OK"){
				var rutas = response.routes;
				
				$.each(rutas, function(i, ruta){
					//si es la mas barata
					if(ruta.summary.diffs.cheapest == 0){
						$("#casetas").val((ruta.costs.cash * 2).toFixed(0))
						sumarImportes();
					}
					
				})
				
				
				
			}
		});
	}
	
	function calcularCasetas(polyline){
		console.log("calcularCasetas()")
		
		const json_data = {
			"serviceProvider" : "google",
			"polyline": polyline
		} ;
		
		const settings = {
			"async": true,
			"crossDomain": true,
			"url": "https://dev.tollguru.com/v1/calc/route",
			"method": "POST",
			"headers": {
				"content-type": "application/json",
				"x-api-key": "8THMmf37gqbpD88P4bTLfL94TQPLqpr2"
				
			},
			"processData": false,
			"data": JSON.stringify(json_data)
		};
		
		$.ajax(settings).done(function (response) {
			console.log(response);
			
			if(response.status == "OK"){
				$("#casetas").val(response.route.costs.cash)
				sumarImportes();
			}
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
				window.AppInventor.setWebViewString(atob(respuesta));
			}
			else{
				
				printService.submit({
					'type': 'LABEL',
					'raw_content': respuesta
				});
			}
			
			
			
			
			
			}).always(function(){
			
		});
	}
	
</script>



</body>
</html>																															