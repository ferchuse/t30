
var printService = new WebSocketPrinter();

$(document).ready(function(){
  
	
	
	$('#lista_pagos').on('click', '.imprimir', imprimirPago );
	
	$("#lista_pagos").on("click", ".cancelar", confirmaCancelarPagos);
	
	$('#form_filtros').on('submit',listarPagos);
	
	
	$('#form_filtros').submit();
	
});


function listarPagos(event){
	event.preventDefault();
	console.log("listaPagos()");
	let boton = $(this).find(":submit");
	let icono = boton.find(".fas");
	
	boton.prop("disabled", true);
	icono.toggleClass("fa-search fa-spinner fa-spin");
	
	
	$.ajax({
		url: 'pagos_taquilla/lista_pagos.php',
		data: $("#form_filtros").serialize()
		}).done(function(respuesta){
		$("#lista_pagos").html(respuesta);
		}).fail().always(function(){
		
		boton.prop("disabled", false);
		icono.toggleClass("fa-search fa-spinner fa-spin");
		
	});
}

function imprimirPago(){
	var id_pagos = $(this).data("id_registro");
	
	$.ajax({
		url: '../taquilla/boletos_iv/imprimir_pago.php',
		
		data: {"id_pagos": id_pagos}
		}).done(function(respuesta){
		
		printService.submit({
			'type': 'LABEL',
			'raw_content': respuesta
		});
		alertify.success('Imprimiendo...');
		
		}).always(function(){
		
	});
	
	
}

function confirmaCancelarPagos(event){
	console.log("confirmaCancelacion()");
	let boton = $(this);
	let icono = boton.find(".fas");
	var id_registro = $(this).data("id_registro");
	var fila = boton.closest('tr');
	
	alertify.prompt()
  .setting({
    'reverseButtons': true,
		'labels' :{ok:"SI", cancel:'NO'},
		'title': "Cancelar Pago" ,
    'message': "Motivo de Cancelación" ,
    'onok':cancelarPagos,
    'oncancel': function(){
			boton.prop('disabled', false);
			
		}
	}).show();
	
	
	function cancelarPagos(evt, motivo){
		if(motivo == ''){
			alertify.error("Escribe un motivo");
			return false;
			
		}
		
		boton.prop("disabled", true);
		icono.toggleClass("fa-times fa-spinner fa-spin");
		
		
		return $.ajax({
			url: "pagos_taquilla/cancelar_pagos.php",
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
				$('#form_filtros').submit();
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
