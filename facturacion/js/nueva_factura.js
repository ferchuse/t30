

$(document).ready(function(){
	$('#form_factura').submit( facturar);
	$('#rfc_clientes').blur( buscarCliente);
	
});


function buscarCliente(){
	event.preventDefault();
	
	$.ajax({
		url: 'control/fila_select.php',
		method: 'GET',
		data: {
			"tabla": "clientes",
			"id_campo": "rfc_clientes",
			"id_valor": $("#rfc_clientes").val()
			
			
		}
		
		
		}).done(function(respuesta){
		if(respuesta["encontrado"] > 0){
			$.each(respuesta.data, function(key, value){
				$("#"+ key).val(value)
			})
		}
	})
	
}

function facturar(event){
	event.preventDefault();
	
	// if($(".concepto").length == 0){
		// alert("Agregue al menos un concepto")
		// return
	// }
	
	var url_xml ;
	var url_pdf ;
	
	$boton = $("#timbrado_sw");
	$icono = $boton.find('i');
	
	$boton.prop('disabled',true);	
	$icono.toggleClass('fa-arrow-right fa-spinner fa-spin ');
	
	$("#mensaje_error").addClass("hidden");
	$("#mensaje_error").html("") ;
	$("#mensaje_timbrado").addClass('alert-success ');	
	$("#mensaje_timbrado").removeClass('alert-danger d-none');	
	$("#mensaje_timbrado").find(".fa").removeClass('fa-times');	
	$("#mensaje_timbrado").find(".fa").addClass('fa-spinner fa-spin');	
	
	$.ajax({
		url: 'facturacion/timbrado_sw_4.php',
		method: 'POST',
		data: $('#form_cliente').serialize() + "&" +$("#form_factura").serialize() +"&" +$("#form_conceptos").serialize()
		}).done(function(respuesta){
		if(respuesta["timbrado"]["status"] == "error"){
			alert(respuesta["timbrado"]["message"] + respuesta["timbrado"]["messageDetail"] )
			$("#mensaje_error").removeClass("d-none");
			$("#mensaje_error").html(respuesta["timbrado"]["message"]+  respuesta["timbrado"]["messageDetail"] ) ;
			$("#mensaje_timbrado").toggleClass('alert-success alert-danger');	
			$("#mensaje_timbrado").find(".fa").toggleClass('fa-spinner fa-spin fa-times');	
			
			$boton.prop('disabled',false);
			$icono.toggleClass('fa-arrow-right fa-spinner fa-spin');
			return false;
		}
		if(respuesta["timbrado"]["error"]){
			alert(respuesta["timbrado"]["mensaje"])
			
			
			$boton.prop('disabled',false);
			$icono.toggleClass('fa-arrow-right fa-spinner fa-spin');
			return false;
		}
		
		
		url_xml = respuesta.url_xml;
		url_pdf = respuesta.url_pdf;
		filename = respuesta.filename;
		
		//generar pdf
		$("#mensaje_timbrado").find(".fa").toggleClass('fa-spinner fa-spin fa-check');	
		$("#mensaje_pdf").removeClass('d-none');	
		$.ajax({
			url: 'facturacion/generar_pdf.php',
			method: 'GET',
			data: 
			{id_facturas :respuesta["id_facturas"]}
			
			}).done(function(respuesta){
			console.log(respuesta);
			
			$("#mensaje_pdf").find(".fa").toggleClass('fa-spinner fa-spin fa-check');	
			
			if($("#enviar_correo").prop("checked")){
				
				//enviar por correo
				$("#mensaje_correo").removeClass('d-none');	
				$("#timbrado_sw").remove();	
				//Vinculos de Descarga
				$("#descargar").removeClass('d-none');	
				$("#url_xml").attr('href' , "facturacion/" + url_xml );	
				$("#url_pdf").attr('href' , "facturacion/" + url_pdf);	
				
				$.ajax({
					url: 'lib/phpmailer/send_mail.php',
					method: 'GET', 
					data: 
					{
						url_pdf : url_pdf,
						url_xml : url_xml,
						id_emisores : $("#id_emisores").val(),
						folio : filename,
						correo : $("#correo_clientes").val()
					}
					}).done(function(respuesta){
					
					
					$("#mensaje_correo").find(".fa").toggleClass('fa-spinner fa-spin fa-check');	
					
					alert("Factura Enviada Correctamente, en caso de no recibirla verifique su carpeta de correo no deseado o SPAM")
					
					window.location.href = "#descargar";
					
					}).fail(function(xhr, error, errnum){
					alertify.error("Error" + error);
					
					}).always(function(){
					$boton.prop('disabled',false);
					$icono.toggleClass('fa-arrow-right fa-spinner fa-spin');
					
				}); 
			}
			
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
	});
}






