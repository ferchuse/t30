
$(document).ready(onLoad);


function onLoad(){
	
	listarCaja();
	
}


$("#nueva_caja").click(function nuevo() {
	console.log("nueva_caja()")
	
	$('#form_caja')[0].reset();
	$("#modal_caja").modal("show");
	
});

$("#lista_caja").on("click", ".cancelar", confirmaCancelarCaja);


$('#form_caja').submit(guardarCaja);
$('#form_filtros').submit(function(ev){
	ev.preventDefault();
	listarCaja();
	
});


function listarCaja() {
	
	var boton = $("#form_filtros").find(":submit")
	var icono = boton.find(".fas")
	
	boton.prop("disabled", true);
	icono.toggleClass("fa-search fa-spinner fa-spin");
	
	$.ajax({
		"url": "caja/listar_caja.php",
		data: $("#form_filtros").serialize()
		
		}).done(function alCargar(respuesta) {
		$("#lista_caja").html(respuesta);
		
		}).always(function(){
		
		
		boton.prop("disabled", false);
		icono.toggleClass("fa-search fa-spinner fa-spin"); 
	});
}


function confirmaCancelarCaja(event){
	console.log("confirmaBorrar")
	let $boton = $(this);
	let $fila = $(this).closest('tr');
	let $icono = $(this).find(".fas");
	$boton.prop("disabled", true);
	$icono.toggleClass("fa-trash fa-spinner fa-spin");
	
	if(confirm("¿Estás Seguro?")){
		$.ajax({ 
			"url": "caja/cancelar_caja.php",
			"dataType": "JSON",
			"data": {
				"id_registro": $boton.data("id_registro")
			}
			}).done( function alTerminar (respuesta){
			console.log("respuesta", respuesta);
			
			listarCaja();
			
			}).fail(function(xhr, textEstatus, error){
			console.log("textEstatus", textEstatus);
			console.log("error", error);
			
			}).always(function(){
			
			$boton.prop("disabled", false);
			$icono.toggleClass("fa-trash fa-spinner fa-spin"); 
		});
	}
}		

function guardarCaja(event) {
	
	event.preventDefault();
	
	let boton = $(this).find(":submit");
	let icono = boton.find(".fas");
	
	boton.prop("disabled", true);
	icono.toggleClass("fa-save fa-spinner fa-spin");
	
	$.ajax({
		url: "caja/guardar_caja.php",
		method: "POST",
		dataType: "JSON",
		data: $("#form_caja").serialize() + "&id_corridas="+ $("#form_boletos #id_corridas").val()
		
		}).done(function (respuesta) {
		console.log("respuesta", respuesta);
		if (respuesta.estatus == "success") {
			
			alertify.success(respuesta.mensaje);
			
			$("#modal_caja").modal("hide");
			listarCaja();
			imprimirCaja(respuesta.folio);
		}
		}).fail(function (xht, error, errnum) {
		
		alertify.error("Error", errnum);
		}).always(function () {
		boton.prop("disabled", false);
		icono.toggleClass("fa-save fa-spinner fa-spin");
		
	});
	
}


function imprimirCaja(folio){
	console.log("imprimirCaja()");
	
	
	$.ajax({
		url: "caja/imprimir_caja.php" ,
		data:{
			"folio" : folio
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
