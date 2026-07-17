/*
	function truncate (num, places) {
	return Math.trunc(num * Math.pow(10, places)) / Math.pow(10, places);
	}
	
	Then call it with:
	
	truncate(3.5636232, 2); // returns 3.56  
	truncate(5.4332312, 3); // returns 5.433
	truncate(25.463214, 4); // returns 25.4632
*/

$(document).ready(function(){
	
	$("#datos_factura .next" ).click(function(){
		
		$("#tab_conceptos").tab("show");
	});
	
	$("#ir_paso_1" ).click(function(){
		$("#tab_cliente").tab("show");
	});
	
	$("#ir_paso_2" ).click(function(){
		$("#tab_factura").tab("show");
	});
	
	
	
	
	$('#rfc_clientes').blur( buscarCliente);
	// getEmisor();
	
	//Autocomplete  https://github.com/devbridge/jQuery-Autocomplete
	$("#razon_social_clientes").autocomplete({
		serviceUrl: "consultas/buscar_cliente_autocomplete.php",   
		onSelect: function onSelect(eleccion){
			$.each(eleccion.data, function(key, value){
				$("#"+ key).val(value)
			})
		},
		noCache	:true , 
		autoSelectFirst	:true , 
		showNoSuggestionNotice	:true , 
		noSuggestionNotice	: "Sin Resultados"
	});
	
	
	
	
	$( ".precio_sin_iva" ).each(function(){
		console.log("calcula precio unitario");
		$(this).focus().next().focus();
		sumarImportes();
	})
	$("#metodo_pago").change(cambiarMetodoPago);
	
	
	function truncarNum (num, decimales) {
		
		return Math.trunc(num * Math.pow(10, decimales)) / Math.pow(10, decimales);
	}
	
	
	
	$("#agregar_concepto" ).click( function agregarConcepto(){
		console.log("agregarConcepto()");
		$(".fila_concepto").last().clone(true).appendTo("#div_conceptos");
		// $(".impuestos").last().find(":input").each(function(index, item){
		// console.log("")
		// });
		
		//cambia el indice agregando una posicion 
		//var subname= this.name.split('[').pop().split(']').shift();
		
		
		//var subname= this.name.match(/\[([^[]*)\]/)[1];
		$(".impuestos").last().find(":input").attr("name", function(index, attr){
			console.log("Nombre anterior: ");
			console.log(attr);
			var nombre_anterior = attr.split("[");
			console.log(nombre_anterior);
			console.log(Number(nombre_anterior[1][0]));
			index_nuevo = Number(nombre_anterior[1][0]) + 1;
			nombre_anterior[1] = index_nuevo + "]";
			
			nombre_nuevo = nombre_anterior.join("[");
			
			console.log("nombre_nuevo: " + nombre_nuevo);
			return nombre_nuevo;
		});
		
		sumarImportes();
	});
	
	$(".agregar_impuesto" ).click( function agregarImpuesto(){
		console.log("agregarImpuesto()");
		var $impuestos = $(this).closest(".row").find(".impuestos");
		$(this).closest(".row").find(".fila_impuesto").first().clone(true).appendTo($impuestos );
		
	});
	
	$('.borrar_impuesto').click( function borrarImpuesto(){
		console.log(" borrarImpuesto()");
		// if($(this).closest(".impuestos").find(".fila_"))
		var boton = $(this);
		var icono = boton.find('.fa');
		var fila = boton.closest('.fila_impuesto');
		
		fila.fadeOut(1000);
		fila.remove();
		sumarImportes();
	});	
	
	$('.btn_borrar').click( function borrarConcepto(){
		console.log("borrar");
		if($("#div_conceptos .row").length > 1){
			var boton = $(this);
			var icono = boton.find('.fa');
			var fila = boton.closest('.fila_concepto');
			
			fila.fadeOut(1000);
			fila.remove();
		}
		else{
			alertify.error("Debe haber al menos un concepto");
			return false;
		}
		sumarImportes();
	});	
	
	
	$("#btn_vista_previa" ).click( function vistaPrevia(){
		if($("#id_facturas").val() == ""){
			
			//guardarBorrador()
		}
		
		window.open("facturacion/plantilla_pdf.php?id_facturas="+$("#id_facturas").val());
		
	});
	
	$("#btn_guardar" ).click( function guardarBorrador(){
		var $boton = $(this);
		var $icono = $boton.find(".fa"); 
		
		
		if($("#forma_pago").val() == ""){
			
			alert("Eliga Forma de Pago en el paso 2");
			return false;
		}
		
		$boton.prop('disabled',true);	
		$icono.toggleClass('fa-save fa-spinner fa-spin ');
		
		
		$.ajax({
			url: 'facturacion/guardar_borrador.php',
			method: 'POST',
			data: $('#form_cliente').serialize() + "&" +$("#form_factura").serialize() +"&" +$("#form_conceptos").serialize()
			}).done( function afterGuardarBorrador(respuesta){
			console.log(respuesta);
			
			alertify.success("Factura Guardada");
			
			$("#id_facturas").val(respuesta.id_facturas);
			
			
			// window.location.href="facturas.php";
			
			}).fail(function(xhr, error, ernum){}).always( function(){
			
			$boton.prop('disabled',false);	
			$icono.toggleClass('fa-save fa-spinner fa-spin ');
			
		});
	});
	
	
	
	
	$('.tab .disabled').click(function(event){
		console.log("disabled");
		return false;
	});
	
	
	
	$( ".precio_unitario" ).keyup( function calcularImporte(){
		var precio_unitario = Number($(this).val());
		var cantidad = Number($(this).closest(".row").find(".cantidad").val());
		var descuento = Number($(this).closest(".row").find(".descuento").val());
		var precio_sin_iva = precio_unitario / 1.16;
		var iva_unitario = precio_sin_iva * .16;
		var importe = precio_sin_iva * cantidad;
		var iva = importe * .16;
		var base = importe - descuento;
		var importe_impuesto = base * .16;
		
		console.log("precio_unitario: " + precio_unitario);
		console.log("iva_unitario: " + iva_unitario);
		console.log("precio_sin_iva: " + precio_sin_iva);
		console.log("cantidad: " + cantidad);
		console.log("importe: " + importe);
		
		$(this).closest(".fila_concepto").find(".importe_impuesto").val(importe_impuesto.toFixed(2));
		$(this).closest(".fila_concepto").find(".base").val(base.toFixed(2));
		$(this).closest(".row").find(".importe").val(importe.toFixed(2));
		$(this).closest(".row").find(".precio_sin_iva").val(precio_sin_iva.toFixed(2));
		$(this).closest(".row").find(".iva_unitario").val(iva_unitario.toFixed(2));
		$(this).closest(".row").find(".iva").val(iva.toFixed(2));
		
		sumarImportes();
	});
	
	$( ".precio_sin_iva" ).keyup(calcular_sin_iva );
	$( ".precio_sin_iva" ).blur(calcular_sin_iva );
	
	function calcular_sin_iva(){
		console.log("calcular_sin_iva()");
		var precio_sin_iva = Number($(this).val()); 
		var cantidad = Number($(this).closest(".row").find(".cantidad").val());
		var precio_unitario = precio_sin_iva * 1.16;
		var iva_unitario = precio_sin_iva * .16;
		var importe = precio_sin_iva * cantidad;
		var iva = importe * .16;
		
		console.log("precio_unitario: " + precio_unitario);
		console.log("iva_unitario: " + iva_unitario);
		console.log("precio_sin_iva: " + precio_sin_iva);
		console.log("cantidad: " + cantidad);
		console.log("importe: " + importe);
		
		
		$(this).closest(".fila_concepto").find(".importe_impuesto").val(importe.toFixed(2));
		$(this).closest(".fila_concepto").find(".base").val(importe.toFixed(2));
		$(this).closest(".row").find(".importe").val(importe.toFixed(2));
		$(this).closest(".row").find(".precio_unitario").val(precio_unitario.toFixed(2));
		$(this).closest(".row").find(".iva_unitario").val(iva_unitario.toFixed(2));
		$(this).closest(".row").find(".iva").val(iva.toFixed(2));
		
		sumarImportes();
	}
	
	$( ".cantidad" ).keyup( function calcular_cantidad(){
		console.log("calcular_cantidad");
		var cantidad = Number($(this).val()); 
		var precio_unitario =  Number($(this).closest(".row").find(".iva_unitario").val());;
		var precio_sin_iva = Number($(this).closest(".row").find(".precio_sin_iva").val());
		var iva_unitario = Number($(this).closest(".row").find(".iva_unitario").val());
		var importe = precio_sin_iva * cantidad;
		var iva = importe * .16;
		
		$(this).closest(".row").find(".importe").val(importe.toFixed(3));
		$(this).closest(".row").find(".iva").val(iva.toFixed(3));
		
		sumarImportes();
	});
	
	$( ".descuento" ).keyup( sumarImportes);
	
	$( ".tipo_impuesto" ).change( function calcular_impuesto(){
		console.log("calcular_impuesto()");
		var tasa = Number($(this).closest(".fila_impuesto").find(".tasa").val());
		var base =  Number($(this).closest(".fila_impuesto").find(".base").val());
		var importe_impuesto = base * tasa;
		
		$(this).closest(".fila_impuesto").find(".impuesto_importe").val(truncarString(importe_impuesto.toString(), 2));
		
		sumarImportes();
	});
	
	$( ".tasa" ).change( function calcular_impuesto(){
		console.log("calcular_impuesto()");
		var tasa = Number($(this).val()); 
		var base =  Number($(this).closest(".fila_impuesto").find(".base").val());;
		var importe_impuesto = base * tasa;
		
		$(this).closest(".fila_impuesto").find(".impuesto_importe").val(importe_impuesto.toFixed(6));
		
		sumarImportes();
	});
	
	
	
	function validarPaso($paso){
		
		return true;
	}
	
	$("#paso1 .next" ).click(function(){
		
		if($("#curp").val().trim() == ''){
			// $("#curp").prev().append("<span class='text-danger'>Ingresa una CURP</span>");
			// console.log("completar campos");
			alertify.error("Ingresa la CURP");
			
			
			return false;
		}
		
		//actualizar alumno
		$("#tab_cliente").tab("show");
	});
	
	
	$("#form_cliente" ).submit(  function guardarCliente(event){
		event.preventDefault();
		var boton = $(this).find(":submit");
		var icono = boton.find(".fa");
		
		if($("#rfc_clientes").val().trim() == ''){
			$("#rfc_clientes").focus();
			alertify.error("Ingresa el RFC");
			
			return false;
		}
		//Si no existe cliente insertar sino actualizar
		icono.toggleClass('fa-arrow-right fa-spinner fa-spin ');
		boton.prop('disabled',true);
		
		
		$.ajax({
			url: 'control/guardar_cliente.php',
			method: 'POST',
			data: $("#form_cliente").serialize()
			
			}).done(function(respuesta){
			if(respuesta.status == "success"){
				$("#id_clientes").val(respuesta["id_clientes"]);	
				$("#tab_factura").tab("show");
				$('#tab_factura').closest("li").removeClass("disabled");
			}
			}).fail(function(xhr, error, errnum){
			alertify.error("Error" + error); 
			
			}).always(function(){
			icono.toggleClass('fa-arrow-right fa-spinner fa-spin ');
			boton.prop('disabled',false);
		});
		
		
		
		
		
		
	});
	
	
	// $(".anterior").click(function(){
	var tabs = $(".nav-pills a");
	// var index_activo = $(".nav-pills .active").index();
	
	// var index_anterior = index_activo - 1;
	// $(".nav-pills a").eq(index_anterior).tab("show");
	
	
	// console.log("pils");
	// console.log($(".nav-pills li"));
	
	// console.log("index_activo");
	// console.log(index_activo);	
	// console.log("index_anterior");
	// console.log(index_anterior);
	// });
	
	
	
	
	
	
	$('#form_factura').submit( function submitFactura(event){	
		event.preventDefault();
		$("#tab_conceptos").tab("show");
		
	});
	
	// $('#form_conceptos').submit( facturar);
	
	
	$('.clave_unidad').change( function setNombreUnidad(event){
		var $nombre_unidad = $(this).find("option:selected").text(); 
		$(this).closest(".row").find(".nombre_unidades").val($nombre_unidad);
		console.log("$nombre_unidad" + $nombre_unidad);
	});
	
});


function truncarString(string, decimales) {
	console.log("truncarString("+string+")");
	var punto = string.indexOf(".");
	
	if(punto == -1){
		return (string + ".00");
	}
	
	var numero_truncado = string.substring(0, punto + decimales +1 );
	
	console.log("numero_truncado", numero_truncado);
	return numero_truncado ;
	//return Math.trunc(num * Math.pow(10, decimales)) / Math.pow(10, decimales);
}

function cambiarMetodoPago(evt) {
	if($(this).val() == 'PPD'){
		$("#forma_pago").val("99");
	}
}

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

// function getEmisor(){

// console.log("getEmisor" );
// $.ajax({
// url: 'emisores/get_emisor.php',
// method: 'GET',
// data:{
// "id_emisores": $("#id_emisores").val()

// }
// }).done(function(respuesta){

// $("#serie").val(respuesta.datos.serie)
// $("#folio").val(respuesta.datos.folio)
// });
// }



function sumarImportes(){
	console.log("sumarImportes()");
	var importe_total = 0;
	var subtotal = 0;
	var descuento = 0;
	var total_traslados = 0;
	var total_retenciones = 0;
	var num_impuesto = 0;
	
	// Sumar traslados y retenciones
	$(".tipo_impuesto").each( function(index, element){
		num_impuesto++;
		
		console.log("######################Impuesto #", num_impuesto);
		
		var concepto_importe = Number($(this).closest(".fila_concepto").find(".importe").val());
		$(this).closest(".fila_concepto").find(".importe").val(concepto_importe.toFixed(2));
		var descuento = $(this).closest(".fila_concepto").find(".descuento").val();
		
		var $base = $(this).closest(".fila_impuesto").find(".base");
		var tasa = Number($(this).closest(".fila_impuesto").find(".tasa").val());
		
		base = concepto_importe - descuento;
		$base.val(base.toFixed(2));
		var importe_impuesto =  base * tasa;
		importe_impuesto = Number(importe_impuesto.toFixed(2));
		impuesto = Number(importe_impuesto.toFixed(2));
		
		console.log("concepto_importe", concepto_importe);
		console.log("tasa", tasa);
		console.log("base",base);
		console.log("importe_impuesto", importe_impuesto);
		console.log("total_traslados anterior", total_traslados);
		console.log("total_retenciones: anterior", typeof(total_retenciones));
		
		$(this).closest(".fila_impuesto").find(".impuesto_importe").val(importe_impuesto);
		
		
		if($(this).val() == "Traslado"){
			
			total_traslados+= importe_impuesto;
		}
		else{
			
			total_retenciones+= importe_impuesto;
		}
		
		console.log("total_traslados", total_traslados);
		console.log("total_retenciones", total_retenciones);
	});
	
	//Suma los importes
	$(".importe").each(function(index, elemento){
		subtotal+= Number($(elemento).val()); 
	}); 
	$(".descuento").each(function(index, elemento){
		descuento+= Number($(elemento).val()); 
	}); 
	
	
	importe_total = subtotal + total_traslados - total_retenciones- descuento;
	
	$("#total_traslados").val(total_traslados.toFixed(2));
	$("#total_retenciones").val(total_retenciones.toFixed(2));
	
	$("#subtotal").val(subtotal.toFixed(2));
	$("#descuento_total").val(descuento.toFixed(2));
	$("#total").val((importe_total.toFixed(2))); 
}




function extraerNumeros(string){
	var $numeros = "";
	var $letras = "";
	var serie = {};
	
	for(i = 0; i < string.length ; i++){
		if(!isNaN(string[i])){
			$numeros+= string[i];
		}
		else{
			$letras+= string[i];
		}
		
	}
	
	serie["letras"] = $letras;
	serie["numeros"] = $numeros;
	console.log(serie);
	return serie;
}

$("#timbrado_sw").click(timbrado_sw)

function timbrado_sw(event){
	
	
	var termina_facturar = $.Deferred;
	
	
	if($("#forma_pago").val() == ""){
		
		alert("Eliga Forma de Pago en el paso 2");
		return false;
	}
	
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
	
	//timbrado 
	$.ajax({
		url: 'facturacion/timbrado_sw_4.php',
		method: 'POST',
		data: $('#form_cliente').serialize() + "&" +$("#form_factura").serialize() +"&" +$("#form_conceptos").serialize()
		}).done(function(respuesta){
		if(respuesta["timbrado"]["status"] == "error"){
			alert(respuesta["timbrado"]["message"] + " "+respuesta["timbrado"]["messageDetail"] )
			$("#mensaje_error").removeClass("d-none");
			$("#mensaje_error").html(respuesta["timbrado"]["message"] + " "+respuesta["timbrado"]["messageDetail"]) ;
			$("#mensaje_timbrado").toggleClass('alert-success alert-danger');	
			$("#mensaje_timbrado").find(".fa").toggleClass('fa-spinner fa-spin fa-times');	
			
			$boton.prop('disabled',false);
			$icono.toggleClass('fa-arrow-right fa-spinner fa-spin');
			return false;
		}
		if(respuesta["timbrado"]["status"] == "success"){
			//ACTUALIZA BARRA DE CARGA
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
					$("#mensaje_correo").toggleClass('d-none');	
					
					$.ajax({
						url: 'lib/phpmailer/send_mail.php',
						method: 'GET', 
						data: 
						{
							url_pdf :respuesta["url_pdf"],
							url_xml :respuesta["url_xml"],
							id_emisores : $("#id_emisores").val(),
							folio : $("#serie").val() + $("#folio").val(),
							correo : $("#correo_clientes").val()
						},
						// dataType:"JSON"
						}).done(function(respuesta){
						console.log("Respuesta Correo", respuesta);
						
						
						if(!$("#modo_pruebas").prop("checked")){
							window.location.href = "facturas.php";
						}
						
						
						$("#mensaje_correo").find(".fa").toggleClass('fa-spinner fa-spin fa-check');	
						
						alertify.success("Factura Enviada Correctamente");
						
						}).fail(function(xhr, error, errnum){
						alertify.error("Error" + error);
						
						}).always(function(){
						$boton.prop('disabled',false);
						$icono.toggleClass('fa-arrow-right fa-spinner fa-spin');
						
					}); 
				}
				
				}).fail(function(jqXHR, textStatus, errorThrown){
				alert("Error: " + textStatus + errorThrown);
				
				}).always(function(){
				$boton.prop('disabled',false);
				$icono.toggleClass('fa-arrow-right fa-spinner fa-spin');
			});
			
		}
		else{
			$("#mensaje_error").removeClass("d-none");
			$("#mensaje_error").html(respuesta["timbrado"]["message"]) ;
			$("#mensaje_timbrado").toggleClass('alert-success alert-danger');	
			$("#mensaje_timbrado").find(".fa").toggleClass('fa-spinner fa-spin fa-times');	
			$("#debug").html(respuesta["datos_enviados"]) ;
			alert('Error' + respuesta["timbrado"]["codigo_mf_texto"]);
			$boton.prop('disabled',false);
			$icono.toggleClass('fa-arrow-right fa-spinner fa-spin');
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
		
		}).always(function(){
		$boton.prop('disabled',false);
		$icono.toggleClass('fa-arrow-right fa-spinner fa-spin');
	});
	
	
	return termina_facturar;
}


