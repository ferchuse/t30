
var printService = new WebSocketPrinter();

var  $select_boletos = "";

$(document).ready(onLoad);

function onLoad(){
	
	listaBoletos();
	
	$("#form_filtros").on("input", "select", listaBoletos)
	$("#form_filtros").submit(function(event){
		event.preventDefault()
		
		listaBoletos();
		
	})
	
	
	$("#lista_boletos").on("click", ".cancelar", confirmaCancelacion);
	$("#lista_boletos").on("click", ".btn_imprimir", imprimirTicket);
	$("#lista_boletos").on("click", ".btn_gasto", nuevoGasto);
	$("#lista_boletos").on("click", ".btn_editar", editarBoleto);
	$("#lista_boletos").on("click", ".btn_salida", marcarSalida);
	$("#lista_boletos").on("click", ".btn_historial", historialBoleto);
	
	
	$('#form_gasto').submit(guardarGasto);
	$('#forma_pago').change(cambiarFormaPago);
	
	$("#form_editar_boleto").submit(guardarBoleto)
	
	$("#btn_exportar").click(function(event){
		window.open("consultas/exportar_boletos.php?"+ $("#form_filtros").serialize())
	});
	
	$("#btn_exportar_aifa").click(function(event){
		window.open("consultas/exportar_reporte_aifa.php?"+ $("#form_filtros").serialize())
	});
	
	
	
}

function validarMontos(){
	console.log("validarMontos()")
	var suma_pagos = Number($("#efectivo").val()) + Number($("#tarjeta").val())  + Number($("#transferencia").val());
	var total = Number($("#total").val());
	
	valido = total == suma_pagos ?  true : false;
	
	
	
	return valido;
	
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
	var id_terminal = "";
	
	
	switch(forma_pago){
		
		case "Efectivo":
		efectivo = total;
		break;
		
		case "Tarjeta":
		tarjeta = total;
		visible = true;
		requerido = true;
		var id_terminal = $("#id_terminal").val();
		break;
		
		case "Transferencia":
		transferencia = total;
		break;
		
		case "Mixto":
		efectivo = total;
		visible = true;
		requerido = false;
		var id_terminal = $("#id_terminal").val();
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
		$("#id_terminal").val("")
	}
	
	// Hacer requerido o no requerido
	$("#id_terminal").prop("required", requerido);
}

function historialBoleto() {
	console.log("historialBoleto()")
	
	var boton = $(this);
	var icono = boton.find("i");
	
	boton.prop("disabled", true);
	icono.toggleClass("fa-clock fa-spinner fa-spin");
	
	folio = $(this).data("folio")
	
	$.ajax({
		"url": "consultas/lista_historial.php",
		"data": {
			"folio": folio
		}
		}).done(function(respuesta){
		
		$('#folio_historial').html(folio);
		$('#modal_historial .modal-body').html(respuesta);
		
		$("#modal_historial").modal("show");
		
		}).always(function(){
		
		boton.prop("disabled", false);
		icono.toggleClass("fa-clock fa-spinner fa-spin");
		
	});
	
}

function editarBoleto() {
	console.log("editarBoleto()")
	
	
	var boton = $(this);
	var icono = boton.find("i");
	
	boton.prop("disabled", true);
	icono.toggleClass("fa-edit fa-spinner fa-spin");
	
	id_registro = $(this).data("id_registro")
	$('#form_editar_boleto')[0].reset();
	
	
	
	$.ajax({
		"url": "../../funciones/fila_select.php",
		"data": {
			"tabla": "boletos",
			"id_campo": "id_boletos",
			"id_valor": id_registro
		}
		}).done(function(respuesta){
		
		$('#boletos_id_boletos').val(respuesta.data.id_boletos);
		$('#forma_pago').val(respuesta.data.forma_pago);
		$('#efectivo').val(respuesta.data.efectivo);
		$('#tarjeta').val(respuesta.data.tarjeta);
		$('#transferencia').val(respuesta.data.transferencia);
		$('#total').val(respuesta.data.total);
		$('#boletos_num_eco').val(respuesta.data.num_eco);
		$('#boletos_id_usuarios').val(respuesta.data.id_usuarios);
		$('#id_conductores').val(respuesta.data.id_conductores);
		$('#destino').val(respuesta.data.destino);
		$('#origen').val(respuesta.data.origen);
		$('#facturar').val(respuesta.data.facturar);
		$('#nombre_pasajero').val(respuesta.data.nombre_pasajero);
		$('#id_terminal').val(respuesta.data.id_terminal);
		$('#pasajeros').val(respuesta.data.pasajeros);
		
		$("#modal_editar_boleto").modal("show");
		
		cambiarFormaPago();
		
		}).always(function(){
		
		boton.prop("disabled", false);
		icono.toggleClass("fa-edit fa-spinner fa-spin");
		
	});
	
}

function guardarBoleto(event) {
	
	event.preventDefault();
	
	if(!validarMontos()){
		alert("Los montos del pago no coinciden con el total, favor de verificar")
		return false;
	}
	
	let boton = $(this).find(":submit");
	let icono = boton.find(".fas");
	
	boton.prop("disabled", true);
	icono.toggleClass("fa-save fa-spinner fa-spin");
	
	$.ajax({
		url: "consultas/update_boleto.php",
		method: "POST",
		dataType: "JSON",
		data: $("#form_editar_boleto").serialize()
		
		}).done(function (respuesta) {
		console.log("respuesta", respuesta);
		if (respuesta.estatus == "success") {
			
			alertify.success(respuesta.mensaje);
			
			$("#modal_editar_boleto").modal("hide");
			listaBoletos();
		}
		else{
			
			alert(respuesta.error)
		}
		}).fail(function (xht, error, errnum) {
		
		alertify.error("Error", errnum);
		}).always(function () {
		boton.prop("disabled", false);
		icono.toggleClass("fa-save fa-spinner fa-spin");
		
	});
	
}


function listaBoletos(){
	console.log("listaBoletos");
	let boton = $("#form_filtros").find(":submit");
	let icono = boton.find("i");
	
	boton.prop("disabled", true);
	icono.toggleClass("fa-search fa-spinner fa-spin");
	
	$.ajax({
		"url" : "consultas/lista_boletos_vendidos.php",
		"data" :$("#form_filtros").serialize()
		
		}).done(function (respuesta){
		$("#lista_boletos").html(respuesta);
		
		
		$('#lista_boletos .focusable')
		.SpatialNavigation()
		.focus(function() {
			$(this).addClass("selected");
			// $(this).find('input:radio').prop('checked', true);
			selected = $(this);
		})
		.blur(function() {
			$(this).removeClass('selected');
			$(this).find('input:radio').prop('checked', false);
		})
		.first()
		.focus();
		
		}).always(function(){
		
		boton.prop("disabled", false);
		icono.toggleClass("fa-search fa-spinner fa-spin");
		
	});
	
}


function nuevoGasto() {
	console.log("nuevoGasto")
	
	$('#form_gasto')[0].reset();
	
	$('#id_boletos').val($(this).data("id_registro"));
	$('#recibe').val($(this).data("recibe"));
	$("#modal_gasto").modal("show");
	
}

function guardarGasto(event) {
	
	event.preventDefault();
	
	let boton = $(this).find(":submit");
	let icono = boton.find(".fas");
	
	boton.prop("disabled", true);
	icono.toggleClass("fa-save fa-spinner fa-spin");
	
	$.ajax({
		url: "gastos/guardar.php",
		method: "POST",
		dataType: "JSON",
		data: $("#form_gasto").serialize() + "&id_corridas="+ $("#form_boletos #id_corridas").val()
		
		}).done(function (respuesta) {
		console.log("respuesta", respuesta);
		if (respuesta.estatus == "success") {
			
			alertify.success(respuesta.mensaje);
			
			$("#modal_gasto").modal("hide");
			// listarGastos();
			imprimirGasto(respuesta.folio);
		}
		}).fail(function (xht, error, errnum) {
		
		alertify.error("Error", errnum);
		}).always(function () {
		boton.prop("disabled", false);
		icono.toggleClass("fa-save fa-spinner fa-spin");
		
	});
	
}

function imprimirGasto(id_gasto){
	console.log("imprimirGasto()");
	
	
	$.ajax({
		url: "impresion/imprimir_gasto.php" ,
		data:{
			"id_gasto" : id_gasto
		}
		}).done(function (respuesta){
		
		
		if(window.AppInventor){
			window.AppInventor.setWebViewString(atob(respuesta));
		}
		
		printService.submit({
			'type': 'LABEL',
			'raw_content': respuesta
		});
		}).always(function(){
		
		
	});
}



function marcarSalida(){
	console.log("marcarSalida()");
	var folio = $(this).data("folio");
	var campo = $(this).data("campo");
	
	
	var boton = $(this); 
	var icono = boton.find("i");
	
	boton.prop("disabled", true);
	icono.toggleClass("fa-arrival fa-spinner fa-spin");
	
	$.ajax({
		url: "consultas/marcar_salida.php" ,
		data:{
			"folio" : folio,
			"campo" : campo
		}
		}).done(function (respuesta){
		
		listaBoletos();
		}).always(function(){
		
		boton.prop("disabled", false);
		icono.toggleClass("fa-arrival fa-spinner fa-spin");
		
	});
}

function imprimirTicket(){
	console.log("imprimirTicket()");
	console.log($(this).data("tipo_ticket"));
	
	var id_registro = $(this).data("id_registro");
	// var url = $(this).data("url");
	var boton = $(this); 
	var icono = boton.find("fas");
	
	boton.prop("disabled", true);
	icono.toggleClass("fa-print fa-spinner fa-spin");
	
	folio = $(this).data("id_registro")
	
	
	
	
	tipo_ticket = $(this).data("tipo_ticket");
	tipo_ticket = null;
	
	form_data = {
		"folio" : folio
	};
	
	if($(this).data("tipo_ticket")){
		tipo_ticket = "operador";
		
		form_data = {
			"folio" : folio,
			"tipo_ticket" : tipo_ticket
		};
	}
	
	
	
	
	
	
	$.ajax({
		url: "impresion/imprimir_boleto.php" ,
		data: form_data
		}).done(function (respuesta){
		
		$("#ticket").html(respuesta); 
		printService.submit({
			'type': 'LABEL',
			'raw_content': respuesta
		});
		}).always(function(){
		
		boton.prop("disabled", false);
		icono.toggleClass("fa-print fa-spinner fa-spin");
		
	});
}


function confirmaCancelacion(event){
	console.log("confirmaCancelacion()");
	let boton = $(this);
	let icono = boton.find(".fas");
	var id_registro = $(this).data("id_registro");
	var fila = boton.closest('tr');
	
	alertify.prompt()
	.setting({
		'reverseButtons': true,
		'labels' :{ok:"SI", cancel:'NO'},
		'title': "Cancelar Boleto" ,
		'message': "Motivo de Cancelación" ,
		'onok':cancelarBoleto,
		'oncancel': function(){
			boton.prop('disabled', false);
			
		}
	}).show();
	
	
	function cancelarBoleto(evt, motivo){
		if(motivo == ''){
			console.log("Escribe un motivo");
			alertify.error("Escribe un motivo");
			return false;
			
		}
		
		boton.prop("disabled", true);
		icono.toggleClass("fa-times fa-spinner fa-spin");
		
		
		return $.ajax({
			url: "boletos_iv/cancelar_boleto.php",
			method:"POST",
			dataType:"JSON",
			data:{
				id_registro : id_registro,
				nombre_usuarios : $("#sesion_nombre_usuarios").text(),
				motivo : motivo
			}
			}).done(function (respuesta){
			if(respuesta.result == "success"){
				alertify.success("Cancelado");
				listaBoletos();
			}
			else{
				alertify.error(respuesta.result);
				
			}
			
			}).always(function(){
			boton.prop("disabled", false);
			icono.toggleClass("fa-times fa-spinner fa-spin");
			
		});
	}
}
