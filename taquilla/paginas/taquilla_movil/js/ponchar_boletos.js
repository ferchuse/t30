

$("#boleto").keypress(function(event){
	
	if(event.keyCode==13){
		agregarBoletoSencillo();
	}
})

$("#tablaboletossencillos").on("click", ".btn_borrar", borrarBoleto);
$("#form_boletos").on("submit", generarRecibo);

$("#modal_ponchar").on('shown.bs.modal', function(){
	$("#boleto").focus();
	// alert('The modal is fully shown.');
});



$('body').on("click", "#btn_ponchar", function cargarPonchados(event){
	
	$("#div_boletos_pochados").html("<i class='fas fa-spinner fa-spin'></i>");
		
	
	$.ajax({
		url: "consultas/lista_ponchados.php",
		type: "GET",
	
		}).done(function(respuesta){
		

		$("#div_boletos_pochados").html(respuesta);
		
		$("#monto_recibo").val($("#suma_boletos").val())
		
		}).always(function(){
		
		// boton.prop("disabled", false)
		// icono.toggleClass("fa-check fa-spinner fa-spin")
		
		
	});
	
	
	$("#modal_ponchar").modal("show");
});



function generarRecibo(event){
	console.log("borrarBoleto()")
	event.preventDefault();
	
	
	if($("#monto_recibo").val() == 0){
		
		alert("El monto no puede ser 0");
		return;
	}
	
	form_data = $(this).serialize();
	boton = $(this).find(":submit")
	icono = boton.find("i")
	
	
	boton.prop("disabled", true)
	icono.toggleClass("fa-check fa-spinner fa-spin")
	
	
	$.ajax({
		url: "consultas/generar_recibo.php",
		type: "POST",
		dataType: "json",
		data: form_data
		}).done(function(respuesta){
		
		imprimirRecibo(respuesta.folio)
		
		// reset formulario vacias lista de boletos ponchados
		$("#form_boletos")[0].reset()
		$("#tablaboletossencillos").html("");
		
		$("#cant_boletos").text(0);
		$("#total_boletos").text(0);
		$("#id_empresas").val("").change();
		$("#id_beneficiarios").val("").change();
		
		$("#monto_recibo").val(0);
		
		//cerrar modal
		
		$("#modal_ponchar").modal("hide")
		
		}).always(function(){
		
		boton.prop("disabled", false)
		icono.toggleClass("fa-check fa-spinner fa-spin")
		
		
	});
	
}

function borrarBoleto(){
	console.log("borrarBoleto()")
	boton = $(this)
	boton.prop("disabled", true)
	
	folio = $(this).data("folio")
	taquilla = $(this).data("taquilla")
	
	$.ajax({
		url: "consultas/reactivar_boleto.php",
		type: "GET",
		// async: false,
		dataType: "json",
		data: {
			"folio": folio,
			"taquilla": taquilla
		}
	})
	.done(function(data) {
		
		boton.closest("tr").remove();
		
		sumarBoletos();
		
		calcularEfectivo();
	});
	
	// sumarBoletos();
}


function agregarBoletoSencillo(){
	console.log("agregarBoletoSencillo")
	$.ajax({
		url: "consultas/buscar_boleto.php",
		type: "POST",
		// async: false,
		dataType: "json",
		data: {
			boleto: $("#boleto").val()
		}
	})
	.done(function(data) {
		if(data.error == 1){
			alert(data.mensaje);
		}
		else{
			alertify.success("Agregado");
			$("#tablaboletossencillos").append(data.html);
		}
		$("#boleto").val("")
		
		listarRegistros();
		sumarBoletos();
		
		// sumarImportes();
	});
	
	
}


function sumarBoletos(){
	// console.log("sumarBoletos")
	var total_boletos  = 0 ;
	var cant_boletos = 0 ;
	const formatter = new Intl.NumberFormat('es-MX', {
		style: 'currency',
		currency: 'MXN'
	});
	
	
	$("#tablaboletossencillos tr").each(function(index, item){
		total_boletos += Number($(this).find(".monto").data("monto"));
		cant_boletos ++ ;
		// console.log("total_boletos",total_boletos)
		// console.log("cant_boletos",cant_boletos)
	});
	
	
	$("#cant_boletos").text(cant_boletos);
	$("#total_boletos").text(formatter.format(total_boletos));
	
	$("#monto_recibo").val(total_boletos);
	
	
}


function imprimirRecibo(folio){
	
	
	return $.ajax({
		url: "../movimientos/impresion/imprimir_salida.php",
		data:{
			id_registro : folio,
			nombre_usuarios : $("#sesion_nombre_usuarios").html()
		}
		}).done(function (respuesta){
		
		$("#impresion").html(respuesta);
		setTimeout( function(){
			window.print();
			
		}, 500)
	})
}
