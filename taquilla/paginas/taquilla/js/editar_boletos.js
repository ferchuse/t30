
var printService = new WebSocketPrinter();

var  $select_boletos = "";

$(document).ready(onLoad);

function onLoad(){
	
	
	$("#form_editar_boleto").on("submit", cambiarAsiento);
	$("#lista_boletos").on("click", ".editar_boleto", cargarBoleto);
	
}


function cambiarAsiento(event) {
	
	console.log(" cambiarAsiento()")
	event.preventDefault();
	
	let boton = $(this).find(":submit");
	let icono = boton.find(".fas");

	boton.prop("disabled", true);
	icono.toggleClass("fa-edit fa-spinner fa-spin");
	
	$.ajax({
		url: "control/cambiar_asiento.php",
		method: "POST",
		dataType: "JSON",
		data: $("#form_editar_boleto").serialize()
		
		}).done(function (respuesta) {
		console.log("respuesta", respuesta);
		if (respuesta.estatus == "success") {
			
			alertify.success("Guardado");
			$("#modal_editar_boleto").modal("hide");
			listaBoletos();
			// desactivaAsientosOcupados();
		}
		}).fail(function (xht, error, errnum) {
		
		alertify.error("Error", errnum);
		
		}).always(function () {
		boton.prop("disabled", false);
		icono.toggleClass("fa-edit fa-spinner fa-spin");
		
	});
	
	
}


function cargarBoleto(){
	console.log(" cargarBoleto()")
	let boton = $(this);
	let icono = boton.find(".fas");
	let id_registro = boton.data("id_registro");
	
	boton.prop("disabled", true);
	icono.toggleClass("fa-edit fa-spinner fa-spin");
	
	$.ajax({
		url: "../../funciones/fila_select.php",
		
		dataType: "JSON",
		data: {
			tabla: "boletos",
			id_campo: "id_boletos",
			id_valor: id_registro
			
		}
		
		}).done(function (respuesta) {
		console.log("respuesta", respuesta);
		if (respuesta.encontrado == 1) {
			$.each(respuesta.data, function (name, value) {
				$("#form_editar_boleto input[name=" + name+ "]").val(value);
				$("#form_editar_boleto select[name=" + name+ "]").val(value);
			});
			
			$("#modal_editar_boleto").modal("show");
			
		}
		}).fail(function (xht, error, errnum) {
		
		alertify.error("Error", errnum);
		
		}).always(function () {
		boton.prop("disabled", false);
		icono.toggleClass("fa-edit fa-spinner fa-spin");
		
	});
	
}






