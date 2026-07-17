var filtros = $("#form_filtros").serialize();



function buscarCliente(event) {
	var value = $(this).val().toLowerCase();
	$("#lista_facturas tbody tr").filter(function() {
		$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
	});
}
$(document).ready(function() {
	
	
	
	$('#lista_facturas').on("click", ".seleccionar",  contarSeleccionados);
	$('#lista_facturas').on("click", "#check_all",  checkAll);
	
	$('#btn_pagar_varios').click(function(){
		var id_facturas = $("#folios_seleccionados").val();
		var saldo =  $(this).data("saldo");
		
		cargarDctosRelacionados(id_facturas);
		mostrarModalPago(id_facturas, saldo);
		
	});
	
	$("#dctos_relacionados").on("keyup" , ".ImpSaldoAnt, .ImpPagado" , calcularSaldos);
	
	
	
	listarRegistros();
	
	
	$("#abono").keyup( function calculaSaldo(){
		var saldo_anterior = Number($("#saldo_anterior").val());
		var abono = Number($(this).val());
		var saldo_restante = saldo_anterior - abono;
		
		$("#saldo_restante").val(saldo_restante.toFixed(2));
		
	});
	
	$(".filtro").change(function(){
		filtros = $("#form_filtros").serialize();
		listarRegistros();
		
	});
	
	
	//--------FILTRO--------
	
	
	
	$(".exportar").click(function(){
		
		$('#tabla_reporte').tableExport(
			{
				type:'excel',
				tableName:'Reporte', 
				ignoreColumn: [9],
				escape:'false'
			});
	});
	
	
	//-------------FILTROS------------------------------
	
	$('#id_select').change(function seleccionNiveles() {
		var niveles = $('#id_select option:selected').data('id_nivel');
		console.log(niveles);
		filtros['niveles'] = $('#id_select option:selected').data('id_nivel');
		filtros['id_grados'] = $('#id_select option:selected').val();
		
		listarRegistros();
		console.log(filtros);
	});
	
	
	
	$("#form_pago").submit( guardarPago );
	
	
	$("#form_correo").submit(  function enviarCorreo(event){
		console.log(" enviarCorreo()")
		var boton = $(this).find(":submit");
		var icono = boton.find(".fa");
		
		event.preventDefault();
		icono.toggleClass("fa-envelope fa-spinner fa-spin");
		boton.prop('disabled', true);
		
		$.ajax({
			url: 'lib/phpmailer/send_mail.php',
			// dataType: 'JSON',
			method: 'GET',
			data:$("#form_correo").serialize()
			}).done(function(respuesta){
			console.log("Respuesta Correo", respuesta);
			alertify.success("Se ha enviado correctamente"); 
			$("#modal_correo").modal("hide");
			
			}).always(function(){
			icono.toggleClass("fa-envelope fa-spinner fa-spin");
			boton.prop('disabled', false);
			
		});
		
	});
});


function enviarPago(datos_correo){
	console.log(" enviarPago()")
	
	
	return $.ajax({
		url: 'lib/phpmailer/send_mail.php',
		// dataType: 'JSON',
		method: 'GET',
		data: {
			url_pdf :datos_correo["url_pdf"],
			url_xml :datos_correo["url_xml"],
			folio: datos_correo.folio,
			correo : datos_correo.correo
		},
		}).done(function(respuesta){
		
		alertify.success("Correo Enviado"); 
		
		}).always(function(){
		
	});
	
}

function listarRegistros() {
	var cargador = "<tr><td class='text-center' colspan='10'><i class='fa fa-spinner fa-spin fa-3x'></i></td></tr>";
	$('#lista_facturas').html(cargador);
	$.ajax({
		url: 'control/lista_facturas.php',
		method: 'GET',
		data: $("#form_filtros").serialize()
		}).done(function(respuesta) {
		$('#lista_facturas').html(respuesta);
		
		$('#tabla_reporte').DataTable({
			language: {
				url: '//cdn.datatables.net/plug-ins/2.1.8/i18n/es-MX.json',
			},
		});
		
		$('.btn_cancelar').click(confirmarCancelacion);
		
		$('.btn_eliminar').click(confirmaEliminar);
		$('.btn_pago').click(function(){
			var id_facturas = $(this).data("id_facturas");
			var saldo =  $(this).data("saldo_actual");
			
			mostrarModalPago(id_facturas, saldo);
			
		});
		$("#buscar_cliente").keyup(buscarCliente);
		
		$('.btn_correo').click(function modal_correo() {
			console.log("modal_correo()");
			$("#id_emisores").val($(this).data("id_emisores"));
			$("#folio_facturas").val($(this).data("folio"));
			$("#correo").val($(this).data("correo"));
			$("#url_xml").val($(this).data("url_xml"));
			$("#url_pdf").val($(this).data("url_pdf"));
			
			$("#modal_correo").modal("show");
			
		});
		
	});
}



function checkAll(){
	console.log("checkAll");
	if($(this).prop("checked")){
		$(".seleccionar:visible").prop("checked", true);
	} 
	else{
		
		$(".seleccionar:visible").prop("checked", false);
		
	}
	console.log("Visibles", $(".seleccionar:visible"))
	contarSeleccionados();
	
}

function contarSeleccionados(){
	console.log( ("contarSeleccionados()"));
	
	var suma_saldo= 0;
	var folios = $(".seleccionar:checked").map(function(){
		
		saldo = Number($(this).closest("tr").find(".saldo_actual").val());
		
		suma_saldo+= saldo;
		
		return $(this).val();
	}).get().join(",");
	
	
	$("#folios_seleccionados").val(folios);
	console.log( ("folios") , folios);	
	console.log( ("suma_saldo") , suma_saldo);	
	
	if($(".seleccionar:checked").length > 0 ){
		$("#btn_pagar_varios").prop("disabled", false);
	}
	else{
		$("#btn_pagar_varios").prop("disabled", true);
	}
	
	$("#btn_pagar_varios").data({"saldo" : suma_saldo});
	
	$("#cant_seleccionados").text( suma_saldo.toLocaleString("es-MX", { style: 'currency', currency: 'MXN' }) );
	
}

function confirmaEliminar() {
	var boton = $(this);
	boton.prop('disabled', true);
	icono = boton.find(".fa");
	icono.toggleClass("fa-trash fa-spinner fa-spin ");
	var folio_facturas = boton.data('folio_facturas');
	var id_facturas = boton.data('id_facturas');
	var fila = boton.closest('tr');
	
	alertify.confirm('Confirmacion', '¿Deseas Eliminar esta factura?', eliminarFactura, function(){
		icono.toggleClass("fa-trash fa-spinner fa-spin");
		boton.prop('disabled', false);
	});
	
	function eliminarFactura(evet,value) {
		$.ajax({
			url: 'control/eliminar_factura.php',
			method: 'GET',
			data:{
				folio_facturas: folio_facturas,
				id_facturas: id_facturas
			}
			}).done(function(respuesta){
			if(respuesta.estatus == "success"){
				fila.fadeOut(200)
				alertify.success("Factura Eliminada Correctamente"); 
				
			}
			else{
				alertify.error(respuesta.mensaje); 
				
			}
			
			listarRegistros();
			}).fail(function(xhr, error,errnum ){
			alertify.error("Ocurrio un error" + error);
			}).always(function(){
			icono.toggleClass("fa-trash fa-spinner fa-spin ");
			boton.prop('disabled', false);
		});
	}
}


$("#form_cancelar").submit(cancelarFactura);


function confirmarCancelacion() {
	console.log("confirmarCancelacion")
	var boton = $(this);
	var uuid = boton.data('uuid');
	var id_facturas = boton.data('id_facturas');
	var id_emisores = boton.data('id_emisores');
	
	$("#cancelar_id_emisores").val(id_emisores);
	$("#cancelar_uuid").val(uuid);
	$("#cancelar_id_facturas").val(id_facturas);
	$("#modal_cancelar").modal("show");
	
}



function cancelarFactura(event) {
	event.preventDefault();
	var boton = $(this).find(":submit");
	var icono = boton.find(".fa");
	
	boton.prop('disabled', true);
	icono.toggleClass("fa-check fa-spinner fa-spin ");
	
	$.ajax({
		url: 'facturacion/cancelar_factura.php',
		method: 'POST',
		data: $("#form_cancelar").serialize()
		}).done(function(respuesta){
		if(respuesta.timbrado.status == "success"){
			
			
			let uuid_cancelado = $("#cancelar_uuid").val()
			let codigo_cancelacion = respuesta.timbrado.data.uuid[uuid_cancelado.toUpperCase()];
			if(codigo_cancelacion == 201){
				alert("Solicitud de Cancelación Enviada, Verifique estatus en la página del SAT"); 
				$("#modal_cancelar").modal("hide")
				listarRegistros();
			}
			if(codigo_cancelacion ==  202){
				
				alert("Error "+ codigo_cancelacion + " Folio Fiscal Previamente Cancelado	Se considera solicitud de cancelación previamente enviada. Estatus Cancelado ante el SAT.")
			}
			if(codigo_cancelacion == 204){
				alert("Error 204"+ " Folio Fiscal No Aplicable a Cancelación"	 )
			}
			if(codigo_cancelacion == 205){
				alert("Error 205"+ " Folio Fiscal No Existente"	 )
			}
			if(codigo_cancelacion == 207){
				alert("Error 207"+ " No se especificó el motivo de cancelación o el motivo no es valido	El UUID sustitución no existe, está cancelado o tiene una fecha de emisión anterior a la fecha de emisión del comprobante original."	 )
			}
			// alert("Codigo Respuesta: "+ codigo_cancelacion + " Consulte matriz de errores")
			
		}
		else{
			
			alert("Ocurrio un Error"+ JSON.stringify(respuesta.timbrado))
			alert(respuesta.timbrado.message)
			alert(respuesta.timbrado.messageDetail)
			
		}
		
		}).fail(function(xhr, error,errnum ){
		alertify.error("Ocurrio un error" + error);
		}).always(function(){
		icono.toggleClass("fa-check fa-spinner fa-spin ");
		boton.prop('disabled', false);
	});
}
function getEmisor(){
	console.log("getEmisor");
	return $.ajax({
		url: 'emisores/get_emisor.php',
		data: {
			"id_emisores" : $("#id_emisores").val()
		}
	});
}

function mostrarModalPago(id_facturas, saldo){
	
	getEmisor().done(function(respuesta){
		console.log("respuesta", respuesta);
		if(!respuesta.datos.serie_pago){
			$("#serie").val(respuesta.datos.serie_emisores); 
			$("#folio").val(respuesta.datos.folio_emisores);
		}
		else{
			
			$("#serie").val(respuesta.datos.serie_pago); 
			$("#folio").val(respuesta.datos.folio_pago);
			
			console.log("No hay serie de pago usar serie de facturas")
			
		}
	})
	
	$("#modal_pago").modal("show");
	
	
	$("#id_facturas").val(id_facturas);
	$("#saldo_anterior").val(saldo.toFixed(2));
	$("#abono").val(saldo.toFixed(2));
	$("#saldo_restante").val("0");
	
	$("#mensaje_error").addClass('d-none');	
	$("#mensaje_timbrado").addClass('alert-success d-none');	
	$("#mensaje_pdf").addClass('alert-success d-none');	
	
}

function calcularSaldos(){
	console.log("calcularSaldos")
	let abono  = 0;
	let saldo_restante  = 0;
	
	$(".ImpPagado").each(function(){
		ImpSaldoAnt = Number($(this).closest("tr").find(".ImpSaldoAnt").val());
		ImpPagado =  Number($(this).val());
		ImpSaldoInsoluto = ImpSaldoAnt - ImpPagado
		
		
		abono+= Number($(this).val());
		saldo_restante+=  ImpSaldoInsoluto;
		
		$(this).closest("tr").find(".ImpSaldoInsoluto").val(ImpSaldoInsoluto.toFixed(2))
	})
	
	
	
	
	$("#abono").val(abono.toFixed(2))
	$("#saldo_restante").val(saldo_restante.toFixed(2))
}

function cargarDctosRelacionados(folios){
	console.log("cargarDctosRelacionados")
	
	$.ajax({
		url: 'consultas/dctos_relacionados.php',
		// dataType: 'JSON',
		method: 'GET',
		data: {"folios" : folios}
		}).done(function(respuesta){
		console.log(respuesta)
		
		$("#dctos_relacionados").html(respuesta)
		}).always(function(){
		
	});
	
}

function guardarPago(event){
	
	event.preventDefault();
	
	var boton = $(this).find(":submit");
	var icono = boton.find(".fa");
	
	icono.toggleClass("fa-save fa-spinner fa-spin");
	boton.prop('disabled', true);
	
	
	$("#mensaje_error").html("") ;
	$("#mensaje_timbrado").html("Timbrando") ;
	$("#mensaje_timbrado").removeClass('alert-danger d-none');	
	$("#mensaje_timbrado").find(".fa").removeClass('fa-times');	
	$("#mensaje_timbrado").find(".fa").addClass('fa-spinner fa-spin');	
	
	
	
	
	$.ajax({
		"url": 'facturacion/pago_sw.php',
		dataType: 'JSON',
		method: 'POST',
		data:$("#form_pago").serialize()
		}).done( function afterGuardarPago(respuesta){
		if(respuesta.timbrado.status == "success"){
			$("#mensaje_pdf").removeClass('d-none');	
			$("#mensaje_timbrado").find(".fa").removeClass('fa-spinner fa-spin');	
			$("#mensaje_timbrado").find(".fa").addClass('fa-check');	
			// console.log("id_factura_nueva", respuesta.id_factura_nueva)
			
			
			
			$.ajax({
				url: 'facturacion/generar_pdf.php',
				method: 'GET',
				data: 
				{
					id_facturas :respuesta["id_factura_nueva"]
				}
				
				}).done(function afterGeneraPDF(respuesta){
				console.log(respuesta);
				if(respuesta.estatus_pdf){
					
					
					alertify.success("Se ha guardado correctamente"); 
					
					datos_correo = {
						
						"id_facturas" : respuesta.id_facturas,
						"folio" : respuesta.folio,
						"url_pdf" : respuesta.url_pdf,
						"url_xml" : respuesta.url_xml,
						"correo" :respuesta.correo
						
					};
					
					if(!$("#pago_modo_pruebas").prop("checked")){
					// if($("#pago_modo_pruebas").prop("checked")){
						
						enviarPago(datos_correo).done(function(){
							icono.toggleClass("fa-save fa-spinner fa-spin");
							boton.prop('disabled', false);
							$("#modal_pago").modal("hide");
							
							// window.location.reload();
							
						})
					}
					else{
						
						icono.toggleClass("fa-save fa-spinner fa-spin");
						boton.prop('disabled', false);
					}
					
					
					
				}
				else{
					alertify.error("Ocurrio un error" +  respuesta.result_factura); 
					
				}	
				
				
				
				// $("#mensaje_pdf").find(".fa").toggleClass('fa-spinner fa-spin fa-check');	
				
				
				}).always(function(){
				
				
			});
		}
		else{
			
			icono.toggleClass("fa-save fa-spinner fa-spin");
			boton.prop('disabled', false);
			
			$("#mensaje_timbrado").toggleClass('alert-success alert-danger');	
			$("#mensaje_timbrado").find(".fa").removeClass('fa-spinner fa-spin');	
			$("#mensaje_timbrado").find(".fa").addClass('fa-times');	
			$("#mensaje_timbrado").append(respuesta.timbrado.message );	
			
			alert(respuesta.timbrado.message + respuesta.timbrado.messageDetail);	
			
			
		}
		}).always(function(){
		
	});
	
}

