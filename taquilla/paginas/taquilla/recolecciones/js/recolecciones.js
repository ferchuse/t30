$("#asignar_num_eco").select2({"width":"100%"})
$("#id_conductores").select2({"width":"100%"})
$("#destino").select2({"width":"100%", "tags": true})
$("#editar_destino").select2({"width":"100%", "tags": true})


$("#form_recoleccion").submit(guardarRecoleccion);
$("#form_liquidar").submit(liquidarRecoleccion);
$("#form_asignar").submit(asignarRecoleccion);
$("#form_editar").submit(editarRecoleccion);

$("#form_filtros").submit(function(event){
	event.preventDefault()
	
	listarRecolecciones();
	
})

$("input").on("focus", function(){
	$(this).select();
	
})

$("#total").on("input",calculaSaldo);
$("#anticipo").on("input",calculaSaldo);
$("#editar_total").on("input", editarSaldo);
$("#editar_anticipo").on("input", editarSaldo);

$('#forma_pago').change(cambiarFormaPago);

$("#lista_recolecciones").on("click", ".btn_cancelar", confirmaCancelacion);

$("#lista_recolecciones").on("click", ".btn_asignar", function modalAsignar(){
	
	
	$("#asignar_id_recoleccion").val($(this).data("id_registro"))
	$("#modal_asignar").modal("show")
});

$("#lista_recolecciones").on("click", ".btn_liquidar", function modalLiquidar(){
	
	$("#liquidar_id_recoleccion").val($(this).data("id_registro"))
	$("#liquidar_restante").val($(this).data("restante"))
	$("#modal_liquidar").modal("show")
	
});



$("#lista_recolecciones").on("click", ".btn_editar", function cargarRecoleccion(){
	
	id_registro = $(this).data("id_registro")
	
	$.ajax({
		"url": "recolecciones/consultas/cargar_recoleccion.php", 
		"dataType":"JSON",
		"data":  {
			"id_registro" : id_registro
		}
		}).done(function(respuesta){
		
		$.each(respuesta.fila,function(key,value){
			console.log("key",key)
			console.log("value",value)
			$('#editar_'+key).val(value).change();
		});
		
		$("#modal_editar").modal("show")
		
	}).always();
	
	$("#editar_id_recoleccion").val($(this).data("id_registro"))
	
	
});

listarRecolecciones();


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


function calculaSaldo(){
	console.log("calculaSaldo()") 
	let total = Number($("#total").val());
	let anticipo = Number($("#anticipo").val());
	
	let restante= total - anticipo;
	$("#restante").val(restante.toFixed(2));
	
}
function editarSaldo(){
	console.log("calculaSaldo()") 
	let total = Number($("#editar_total").val());
	let anticipo = Number($("#editar_anticipo").val());
	
	let restante= total - anticipo;
	$("#editar_restante").val(restante.toFixed(2));
	
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
		'title': "Cancelar Recolección" ,
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
			url: "recolecciones/consultas/cancelar_recoleccion.php",
			method:"POST",
			dataType:"JSON",
			data:{
				id_registro : id_registro,
				motivo : motivo
			}
			}).done(function (respuesta){
			if(respuesta.result == "success"){
				alertify.success("Cancelado");
				listarRecolecciones();
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


function asignarRecoleccion(event) {
	console.log("asignarRecoleccion();")
	event.preventDefault();
	
	let boton = $(this).find(":submit");
	let icono = boton.find(".fas");
	
	boton.prop("disabled", true);
	icono.toggleClass("fa-check fa-spinner fa-spin");
	
	$.ajax({
		url: "recolecciones/consultas/asignar_recoleccion.php",
		method: "POST",
		dataType: "JSON",
		data: $("#form_asignar").serialize()
		
		}).done(function (respuesta) {
		console.log("respuesta", respuesta);
		if (respuesta.estatus == "error") {
			
			alert(respuesta.error)
			return;
		}
		
		
		// calendar.render()
		
		
		listarRecolecciones();
		
		// imprimirLiquidacion(respuesta.folio)
		// imprimirBoleto(respuesta.id_boletos)
		$("#form_asignar")[0].reset();
		$("#modal_asignar").modal("hide")
		
		}).fail(function (xht, error, errnum) {
		
		alertify.error("Error", errnum);
		}).always(function () {
		boton.prop("disabled", false);
		icono.toggleClass("fa-check fa-spinner fa-spin");
		
	});
	
}

function liquidarRecoleccion(event) {
	console.log("guardarRecoleccion();")
	event.preventDefault();
	
	let boton = $(this).find(":submit");
	let icono = boton.find(".fas");
	
	boton.prop("disabled", true);
	icono.toggleClass("fa-check fa-spinner fa-spin");
	
	$.ajax({
		url: "recolecciones/consultas/liquidar_recoleccion.php",
		method: "POST",
		dataType: "JSON",
		data: $("#form_liquidar").serialize()
		
		}).done(function (respuesta) {
		console.log("respuesta", respuesta);
		if (respuesta.estatus == "error") {
			
			alert(respuesta.error)
			return;
		}
		
		
		// calendar.render()
		
		$("#form_liquidar")[0].reset()
		// listarRecolecciones();
		
		// imprimirLiquidacion(respuesta.folio)
		imprimirBoleto(respuesta.id_boletos)
		
		$("#modal_liquidar").modal("hide")
		
		}).fail(function (xht, error, errnum) {
		
		alertify.error("Error", errnum);
		}).always(function () {
		boton.prop("disabled", false);
		icono.toggleClass("fa-check fa-spinner fa-spin");
		
	});
	
}

function editarRecoleccion(event) {
	console.log("editarRecoleccion();")
	event.preventDefault();
	
	let boton = $(this).find(":submit");
	let icono = boton.find(".fas");
	
	boton.prop("disabled", true);
	icono.toggleClass("fa-edit fa-spinner fa-spin");
	
	$.ajax({
		url: "recolecciones/consultas/editar_recoleccion.php",
		method: "POST",
		dataType: "JSON",
		data: $("#form_editar").serialize()
		
		}).done(function (respuesta) {
		console.log("respuesta", respuesta);
		if (respuesta.estatus == "error") {
			
			alert(respuesta.error)
			return;
		}
		
		
		// calendar.render()
		
		$("#form_editar")[0].reset()
		listarRecolecciones();
		
		// imprimirLiquidacion(respuesta.folio)
		// imprimirBoleto(respuesta.id_boletos)
		
		$("#modal_editar").modal("hide")
		
		}).fail(function (xht, error, errnum) {
		
		alertify.error("Error", errnum);
		}).always(function () {
		boton.prop("disabled", false);
		icono.toggleClass("fa-check fa-spinner fa-spin");
		
	});
	
}

function guardarRecoleccion(event) {
	console.log("guardarRecoleccion();")
	event.preventDefault();
	
	let boton = $(this).find(":submit");
	let icono = boton.find(".fas");
	
	boton.prop("disabled", true);
	icono.toggleClass("fa-save fa-spinner fa-spin");
	
	$.ajax({
		url: "recolecciones/consultas/guardar_recoleccion.php",
		method: "POST",
		dataType: "JSON",
		data: $("#form_recoleccion").serialize()
		
		}).done(function (respuesta) {
		console.log("respuesta", respuesta);
		if (respuesta.estatus == "error") {
			
			alert(respuesta.error)
			return;
		}
		
		
		
		listarRecolecciones();
		
		calendar.render()
		
		$("#form_recoleccion")[0].reset();
		
		imprimirTicket(respuesta.folio)
		
		
		
		}).fail(function (xht, error, errnum) {
		
		alertify.error("Error", errnum);
		}).always(function () {
		boton.prop("disabled", false);
		icono.toggleClass("fa-save fa-spinner fa-spin");
		
	});
	
}


function imprimirTicket(folio){
	console.log("imprimirTicket()");
	
	return $.ajax({
		url: "recolecciones/impresion/imprimir_recoleccion.php" ,
		data:{
			"folio" : folio
		}
		}).done(function (respuesta){
		
		try {
			printService.submit({
				'type': 'LABEL',
				'raw_content': respuesta
			});
			
		}
		catch (error) {
			
			console.error("Error al imprimir", error.message);
			alert("Error al imprimir:", error.message);
		}
		finally {
			// Este bloque siempre se ejecuta, ocurra o no un error
			console.log("Finalización del bloque try-catch");
		}
		
		}).always(function(){
		
	});
}
function imprimirBoleto(folio){
	console.log("imprimirTicket()");
	
	return $.ajax({
		url: "recolecciones/impresion/imprimir_boleto.php" ,
		data:{
			"folio" : folio
		}
		}).done(function (respuesta){
		
		try {
			printService.submit({
				'type': 'LABEL',
				'raw_content': respuesta
			});
			
		}
		catch (error) {
			
			console.error("Ocurrió un error:", error.message);
			alert("Error al imprimir:", error.message);
		}
		finally {
			// Este bloque siempre se ejecuta, ocurra o no un error
			console.log("Finalización del bloque try-catch");
		}
		
		}).always(function(){
		
	});
}

function listarRecolecciones(){
	console.log("listarRecolecciones()");
	let boton = $("#form_filtros").find(":submit");
	let icono = boton.find("i");
	
	boton.prop("disabled", true);
	icono.toggleClass("fa-search fa-spinner fa-spin");
	
	$.ajax({
		"url" : "recolecciones/consultas/lista_recolecciones.php",
		"data" :$("#form_filtros").serialize()
		
		}).done(function (respuesta){
		$("#lista_recolecciones").html(respuesta);
		
		
		$('#lista_recolecciones .focusable')
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