listarPaquetes();

$("#lista_paquetes").on("click", ".btn_cancelar", confirmarCancelarPaquete);

$("#nuevo_paquete").click(function nuevo() {
	console.log("nuevo_paquete")
	
	$('#form_paquetes')[0].reset();
	$("#modal_paquetes").modal("show");
	
});


$('#tipo_paquete').change(eligePaquete);

$('#form_paquetes').submit(guardarPaquete);

function eligePaquete() {
	console.log("eligePaquete")
	
	opcion = $(this).find("option:selected")
	
	$("#costo").val(opcion.data("precio"));
	
	if(opcion.data("modificable")){
		$("#costo").prop("readonly", false)
		
	}
	else{
		
		$("#costo").prop("readonly", true)
	}
	
}
function listarPaquetes() {
	
	$.ajax({
		"url": "paquetes/listar_paquetes.php",
		data:{
			"fecha": $("#fecha").val(),
			"id_usuarios": $("#filtro_usuarios").val()
		}
		}).done(function alCargar(respuesta) {
		$("#lista_paquetes").html(respuesta);
		
	});
}


function guardarPaquete(event) {
	
	event.preventDefault();
	
	let boton = $(this).find(":submit");
	let icono = boton.find(".fas");
	
	boton.prop("disabled", true);
	icono.toggleClass("fa-save fa-spinner fa-spin");
	
	$.ajax({
		url: "paquetes/guardar.php",
		method: "POST",
		dataType: "JSON",
		data: $("#form_paquetes").serialize()
		
		}).done(function (respuesta) {
		console.log("respuesta", respuesta);
		if (respuesta.estatus == "success") {
			
			alertify.success(respuesta.mensaje);
			
			$("#modal_paquetes").modal("hide");
			listarPaquetes();
			imprimirPaquete(respuesta.folio);
		}
		}).fail(function (xht, error, errnum) {
		
		alertify.error("Error", errnum);
		}).always(function () {
		boton.prop("disabled", false);
		icono.toggleClass("fa-save fa-spinner fa-spin");
		
	});
	
}



function imprimirPaquete(id_paquetes){
	console.log("imprimirPaquete()");
	
	
	$.ajax({
		url: "paquetes/imprimir_paquetes.php" ,
		data:{
			"id_paquetes" : id_paquetes
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


function confirmarCancelarPaquete(event){
	console.log("confirmarCancelarPaquete()")
	let $boton = $(this);
	let $fila = $(this).closest('tr');
	let $icono = $(this).find(".fas");
	let id_registro = $(this).data("id_registro");
	
	
	if(confirm("¿Estás Seguro?")){
		
		$boton.prop("disabled", true);
		$icono.toggleClass("fa-times fa-spinner fa-spin");
		
		$.ajax({ 
			"url": "paquetes/cancelar_paquete.php",
			"dataType": "JSON",
			"method": "POST",
			"data": {
				"id_registro": id_registro
				
			}
			}).done( function alTerminar (respuesta){
			console.log("respuesta", respuesta);
			
			listarPaquetes();
			
			}).fail(function(xhr, textEstatus, error){
			console.log("textEstatus", textEstatus);
			console.log("error", error);
			
			}).always(function(){
			
			$boton.prop("disabled", false);
			$icono.toggleClass("fa-times fa-spinner fa-spin"); 
		});
	}
}		




