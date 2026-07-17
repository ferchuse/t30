
var printService = new WebSocketPrinter();


$(document).ready(onLoad);

function onLoad(){
	
	listarGastos();
	
	$("#form_filtros").on("input",listarGastos)
	$("#form_filtros").submit(function(event){
		event.preventDefault()
		
		listarGastos();
		
	})
	
	$("#lista_gastos").on("click", ".cancelar_gasto", confirmaCancelarGasto);
	$("#lista_gastos").on("click", ".btn_editar", editarGasto);
	$("#lista_gastos").on("click", ".btn_imprimir", function (){
		
		imprimirGasto($(this).data("id_registro"))
	});
	$('#form_gasto').submit(guardarGasto);
}


$("#nuevo_gasto").click(function nuevo() {
	console.log("nuevo_gasto")
	
	$('#form_gasto')[0].reset();
	$("#modal_gasto").modal("show");
	
});


function editarGasto() {
	console.log("editarGasto()")
	
	var boton = $(this);
	var icono = boton.find("i");
	
	boton.prop("disabled", true);
	icono.toggleClass("fa-edit fa-spinner fa-spin");
	
	id_registro = $(this).data("id_registro")
	$('#form_gasto')[0].reset();
	
	$.ajax({
		"url": "../../funciones/fila_select.php",
		"data": {
			"tabla": "gastos_corrida",
			"id_campo": "id_gastos",
			"id_valor": id_registro
		}
		}).done(function(respuesta){
		
		$('#id_gastos').val(respuesta.data.id_gastos);
		$('#id_boletos').val(respuesta.data.id_boletos);
		$('#importe').val(respuesta.data.importe);
		$('#id_cat_gastos').val(respuesta.data.id_cat_gastos);
		$('#detalles').val(respuesta.data.detalles);
		$('#recibe').val(respuesta.data.recibe);
		
		$("#modal_gasto").modal("show");
		
		}).always(function(){
		
		boton.prop("disabled", false);
		icono.toggleClass("fa-edit fa-spinner fa-spin");
		
	});
	
}


function guardarGasto(event) {
	
	event.preventDefault();
	
	let boton = $(this).find(":submit");
	let icono = boton.find(".fas");
	
	boton.prop("disabled", true);
	icono.toggleClass("fa-save fa-spinner fa-spin");
	
	$.ajax({
		url: "gastos/update_gasto.php",
		method: "POST",
		dataType: "JSON",
		data: $("#form_gasto").serialize() 
		
		}).done(function (respuesta) {
		console.log("respuesta", respuesta);
		if (respuesta.estatus == "success") {
			
			alertify.success(respuesta.mensaje);
			
			$("#modal_gasto").modal("hide");
			listarGastos();
			
		}
		}).fail(function (xht, error, errnum) {
		
		alertify.error("Error", errnum);
		}).always(function () {
		boton.prop("disabled", false);
		icono.toggleClass("fa-save fa-spinner fa-spin");
		
	});
	
}

function listarGastos() {
	console.log("listaBoletos");
	let boton = $("#form_filtros").find(":submit");
	let icono = boton.find("i");
	
	boton.prop("disabled", true);
	icono.toggleClass("fa-search fa-spinner fa-spin");
	
	
	
	$.ajax({
		"url": "gastos/listar_gastos.php",
		data: $("#form_filtros").serialize()
		}).done(function alCargar(respuesta) {
		$("#lista_gastos").html(respuesta);
		
		}).always(function(){
		
		boton.prop("disabled", false);
		icono.toggleClass("fa-search fa-spinner fa-spin");
		
	});
}


function confirmaCancelarGasto(event){
	console.log("confirmaBorrar")
	let $boton = $(this);
	let $fila = $(this).closest('tr');
	let $icono = $(this).find(".fas");
	$boton.prop("disabled", true);
	$icono.toggleClass("fa-times fa-spinner fa-spin");
	
	motivo = window.prompt("Escriba el Motivo");
	
	if(motivo != null){
		$.ajax({  
			"url": "gastos/cancelar_gastos.php",
			"dataType": "JSON",
			"method": "POST",
			"data": {
				"id_registro": $boton.data("id_registro"),
				"motivo": motivo
			}
			}).done( function alTerminar (respuesta){
			console.log("respuesta", respuesta);
			
			listarGastos();
			
			}).fail(function(xhr, textEstatus, error){
			console.log("textEstatus", textEstatus);
			console.log("error", error);
			
			}).always(function(){
			
			
		});
		
	}	
	$boton.prop("disabled", false);
	$icono.toggleClass("fa-times fa-spinner fa-spin"); 
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
