$("#num_eco").select2()
$("#id_conductores").select2()
$("#destino").select2()
$("#form_recoleccion").submit(guardarRecoleccion);



$("#anticipo").keyup(calculaSaldo);


listarRecolecciones();

function calculaSaldo(){
	console.log("calculaSaldo()") 
	let total = Number($("#total").val());
	let anticipo = Number($(this).val());
	
	let restante= total - anticipo;
	$("#restante").val(restante.toFixed(2));
	
	
}



function guardarRecoleccion(event) {
	console.log("guardarRecoleccion();")
	event.preventDefault();
	
	let boton = $(this).find(":submit");
	let icono = boton.find(".fas");
	
	boton.prop("disabled", true);
	icono.toggleClass("fa-save fa-spinner fa-spin");
	
	$.ajax({
		url: "consultas/guardar_recoleccion.php",
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
		
		}).fail(function (xht, error, errnum) {
		
		alertify.error("Error", errnum);
		}).always(function () {
		boton.prop("disabled", false);
		icono.toggleClass("fa-save fa-spinner fa-spin");
		
	});
	
}

function listarRecolecciones(){
	console.log("listarRecolecciones()");
	let boton = $("#form_filtros").find(":submit");
	let icono = boton.find("i");
	
	boton.prop("disabled", true);
	icono.toggleClass("fa-search fa-spinner fa-spin");
	
	$.ajax({
		"url" : "consultas/lista_recolecciones.php",
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