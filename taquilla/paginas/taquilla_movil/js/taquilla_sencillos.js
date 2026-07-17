$(document).ready(function(){
	
	
	
	$('#form_boletos').on('submit', guardarBoletos);
	$('#cantidad').on('change', cambiarPrecio);
	$('#cantidad').on('keyup', cambiarPrecio);
	$('#cantidad').on('focus', function(){
		
		$(this).select()
	});
	
	$('#destino').on('change', cambiarPrecio);
	
	
});

function cambiarPrecio(){
	console.log("cambiarPrecio")
	var cantidad = Number($("#cantidad").val());
	var precio = $('#destino').find("option:selected").data("precio")
	
	
	$("#precio").val(precio)
	
	importe = cantidad *precio;
	
	$("#importe").val(importe.toFixed(2))
	
}

function guardarBoletos(event){
	event.preventDefault();
	let form = $(this);
	let boton = form.find(':submit');
	let icono = boton.find('i');
	let datos = form.serialize();
	
	
	boton.prop('disabled',true);
	icono.toggleClass('fa-save fa-spinner fa-pulse ');
	
	
	$.ajax({
		url: 'consultas/guardar_boletos_sencillos.php',
		method: 'POST',
		dataType: 'JSON',
		data: datos
		}).done(function(respuesta){
		
		if(respuesta.estatus == 'error'){
			alert(respuesta.error)
			return
		}
		if(respuesta.estatus == 'success'){
			
			alertify.success('Se ha guardado correctamente');
			imprimirTicket(respuesta.folios).done(function(){
				
				$('#form_boletos')[0].reset();
			});
			
		}
		}).always(function(){
		
		boton.prop('disabled',false);
		icono.toggleClass('fa-save fa-spinner fa-pulse ');
		
		
		}).fail(function(jqXHR, textStatus, errorThrown){
		if (jqXHR.status === 0) {
			
			alert('Falló Internet, Verifique conexión.');
			} else if (jqXHR.status == 404) {
			alert('Página No encontrada');
			} else if (jqXHR.status == 500) {
			alert('Error Interno Codigo 500');
			} else if (textStatus === 'parsererror') {
			alert('Error de JSON.');
			} else if (textStatus === 'timeout') {
			alert('Tiempo de Espera Agotado. Vuelva a intentar');
			} else if (textStatus === 'abort') {
			alert('Conexion Fallida, Vuelva a intentar.');
			} else {
			alert('Error desconocido: ' + jqXHR.responseText);	
		}
		
	});
}




function imprimirTicket(folios){
	console.log("imprimirTicket()");
	
	
	var boton = $(this); 
	var icono = boton.find("i");
	
	boton.prop("disabled", true);
	icono.toggleClass("fa-print fa-spinner fa-spin");
	
	return $.ajax({
		url: "impresion/imprimir_boletos_sencillos.php" ,
		data:{
			folios : folios
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
		
		boton.prop("disabled", false);
		icono.toggleClass("fa-print fa-spinner fa-spin");
		
		}).fail(function(jqXHR, textStatus, errorThrown){
		if (jqXHR.status === 0) {
			
			alert('Falló Internet, Verifique conexión.');
			} else if (jqXHR.status == 404) {
			alert('Página No encontrada');
			} else if (jqXHR.status == 500) {
			alert('Error Interno Codigo 500');
			} else if (textStatus === 'parsererror') {
			alert('Error de JSON.');
			} else if (textStatus === 'timeout') {
			alert('Tiempo de Espera Agotado. Vuelva a intentar');
			} else if (textStatus === 'abort') {
			alert('Conexion Fallida, Vuelva a intentar.');
			} else {
			alert('Error desconocido: ' + jqXHR.responseText);	
		}
		
	});
}
