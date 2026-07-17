function contarSeleccionados(){
	console.log( ("contarSeleccionados()"));
	$("#cant_seleccionados").text($(".seleccionar:checked").length);
	
	
	var folios = $(".seleccionar:checked").map(function(){
		return $(this).val();
	}).get().join(",");
	
	
	$("#folios_seleccionados").val(folios);
	console.log( ("folios") , folios);	
	
	if($(".seleccionar:checked").length > 0 ){
		$("#imprimir_recibos").prop("disabled", false);
	}
	else{
		$("#imprimir_recibos").prop("disabled", true);
	}
}

function checkAll(){
	console.log("checkAll");
	if($(this).prop("checked")){
		$(".seleccionar").prop("checked", true);
	}
	else{
		
		$(".seleccionar").prop("checked", false);
		
	}
	contarSeleccionados();
	
}


$(document).ready(function(){ 
	
	listarRegistros();
	
	$("#imprimir_recibos").click(function(){
		imprimirTicket($(this).data("id_registro") , $("#folios_seleccionados").val())
		
	});
	
	$('#form_filtro').on('submit', function filtrar(event){
		event.preventDefault();
		
		listarRegistros();
		
	});
	
	
	$('#nuevoSalida').on('click',function(){
		$('#form_salida')[0].reset();
		$('.modal-title').text('Nueva Entrada');
		$('#modal_salida').modal('show');
	}); 
	
	$('#form_salida').on('submit', guardarRecibo)
	
	
	
	
	
});

function guardarRecibo(event){
	event.preventDefault();
	let form = $(this);
	let boton = form.find(':submit');
	let icono = boton.find('.fa');
	let datos = form.serializeArray();
	let fecha = new Date().toString('yyyy-MM-dd HH:mm:ss')
	
	datos.push({
		name: 'fecha_deposito',
		value : fecha
		
	});
	datos.push({
		name: 'id_usuarios',
		value : $("#id_usuarios").val()
	});
	
	boton.prop('disabled',true);
	icono.toggleClass('fa-save fa-spinner fa-pulse ');
	
	$.ajax({
		url: 'control/guardar_entrada.php',
		method: 'POST',
		dataType: 'JSON',
		data:{
			tabla: 'recibos_entradas',
			datos: datos
		}
		}).done(function(respuesta){
		if(respuesta.estatus == 'success'){
			alertify.success('Se ha agregado correctamente');
			$('#modal_salida').modal('hide');
			// imprimirTicket(respuesta.folio)
			listarRegistros();
			}else{
			alertify.error('Ocurrio un error');
		}
		}).always(function(){
		boton.prop('disabled',false);
		icono.toggleClass('fa-save fa-spinner fa-pulse fa-fw');
	});
}



function listarRegistros(){
	console.log("listarRegistros()");
	
	let form = $("#form_filtro");
	let boton = form.find(":submit");
	let icono = boton.find('.fa');
	
	boton.prop('disabled',true);
	icono.toggleClass('fa-search fa-spinner fa-pulse ');
	
	return $.ajax({
		url: 'control/lista_recibos_entrada.php',
		data: $("#form_filtro").serialize()
		}).done(function(respuesta){
		
		$("#tabla_registros").html(respuesta)
		
		$(".imprimir").click(function(){
			imprimirTicket($(this).data("id_registro"))
			
		});
		$(".cancelar").click(confirmaCancelacion);
		
		$("#check_all").change(checkAll);
		
		$(".seleccionar").change(contarSeleccionados)
		
		}).always(function(){  
		
		boton.prop('disabled',false);
		icono.toggleClass('fa-search fa-spinner fa-pulse fa-fw');
		
	});
}

function imprimirTicket(id_registro, folios){
	var boton = $(this);
	var icono = boton.find("fas");
	
	boton.prop("disabled", true);
	icono.toggleClass("fa-print fa-spinner fa-spin");
	
	$.ajax({
		url: "impresion/imprimir_entrada.php",
		data:{
			folios: folios,
			id_registro : id_registro,
			nombre_usuarios : $("#sesion_nombre_usuarios").html()
		}
		}).done(function (respuesta){
		
		$("#impresion").html(respuesta);
		setTimeout( function(){
			window.print();
			
		}, 500)
		}).always(function(){
		
		boton.prop("disabled", false);
		icono.toggleClass("fa-print fa-spinner fa-spin");
		
	});
}	




function confirmaCancelacion(event){
	console.log("confirmaCancelacion()");
	let boton = $(this);
	let icono = boton.find(".fas");
	var id_registro = $(this).data("id_registro");
	var fila = boton.closest('tr');
	
	alertify.confirm('Confirmación', '¿Deseas Cancelar?', cancelarRegistro , function(){});
	
	
	function cancelarRegistro(){
		
		boton.prop("disabled", true);
		icono.toggleClass("fa-times fa-spinner fa-spin");
		
		return $.ajax({ 
			url: "control/cancelar_recibo_entrada.php",
			dataType:"JSON",
			data:{
				id_registro : id_registro,
				nombre_usuarios : $("#sesion_nombre_usuarios").text()
			}
			}).done(function (respuesta){
			if(respuesta.result == "success"){
				alertify.success("Cancelado");
				listarRegistros();
			}
			else{
				alertify.error(respuesta.result);
			}
			
			}).always(function(){
			boton.prop("disabled", false);
			icono.toggleClass("fa-times fa-spinner fa-spin");
			
		});
	}
}


function obtenerSaldo(){
	console.log("obtenerSaldo()")
	
	$.ajax({
		url: "control/obtener_saldo_empresa.php",
		dataType:"JSON",
		data: {
			id_empresas: $("#id_empresas").val()
			
		}
		}).done(function (respuesta){
		if(respuesta.result == "success"){
			$("#saldo_reciboSalidas").val(respuesta.saldo_empresa)
		}
		else{
			alertify.error(respuesta.result);
			
		}
		
		}).always(function(){
		
	});
}