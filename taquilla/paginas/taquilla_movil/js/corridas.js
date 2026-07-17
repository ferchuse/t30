$(document).ready(function(){
	
	listarRegistros();
	
	
	$(".nuevo").on('click',function(){
		console.log("Nuevo")
		$("#form_edicion")[0].reset();
		$(".modal-title").text("Nueva Corrida");
		$("#modal_edicion").modal("show");
		
	});
	
	
	$('#form_edicion').on('keypress', function (event){
		if(event.which == 13){
			return false;
		}
	});
	
	
	$('#num_eco').on('keyup', buscarUnidad);
	$('#num_eco').on('blur', buscarUnidad);
	
	
	$('form').on('keydown',function( event){
		
		if(event.which == 13){
			event.preventDefault();
			console.log("Enter, no enviar");
			return false;
		}
	});
	
	function buscarUnidad(event){
		event.preventDefault();
		console.log("event ", event)
		var num_eco = $(this).val();
		if(num_eco == ''){
			alertify.error("Ingrese un Num Eco");
			return false;
		}
		console.log("Buscar Unidad", event.which )
		
		if(event.type == 'blur'){
			cargarUnidad(num_eco);
			return false;
		}
		if(event.which == 13){
			cargarUnidad(num_eco);
			event.preventDefault();
		}
	}
	
	function cargarUnidad(num_eco){
		$("#num_eco").toggleClass("cargando");
		$.ajax({
			url: '../../funciones/fila_select.php',
			method: 'GET',
			dataType: 'JSON',
			data: {
				tabla: "unidades",
				id_campo: "num_eco",
				id_valor:num_eco
			}
			}).done(function(respuesta){
			console.log("buscarUnidad", respuesta) 
			if(respuesta.count_rows == 0){
				alertify.error("No encontrado")
			}
			else{
				$.each(respuesta.data, function(name, value){
					
					$("#"+name).val(value);
					
				})
				
				
			}
			}).always(function(){
			
			$("#num_eco").toggleClass("cargando");
		});
		
		
	}
	
	//==========GUARDAR============
	$('#form_edicion').on('submit',function(event){
		event.preventDefault();
		let form = $(this);
		let boton = form.find(':submit');
		let icono = boton.find('.fa');
		let datos = form.serialize();
		
		datos+="&id_usuarios="+ $("#id_usuarios").val();
		
		boton.prop('disabled',true);
		icono.toggleClass('fa-save fa-spinner fa-pulse ');
		$.ajax({
			url: 'control/guardar_corridas.php',
			method: 'POST',
			dataType: 'JSON',
			data: datos
			}).done(function(respuesta){
			if(respuesta.estatus == 'success'){
				
				alertify.success('Se ha guardado correctamente');
				$('#modal_edicion').modal('hide');
				
				listarRegistros();
				}else{
				alertify.error('Ocurrio un error');
			}
			}).always(function(){
			boton.prop('disabled',false);
			icono.toggleClass('fa-save fa-spinner fa-pulse ');
		});
	});
	
	
});


//========funcion de listar============
function listarRegistros(){
	console.log("listarRegistros()")
	$.ajax({
		url: 'control/lista_corridas.php',
		method: 'POST',
		data:{}
	}).done(
	function(respuesta){
		$("#lista_registros").html(respuesta)
		$('.imprimir').on('click',imprimirTicket)
		$(".cancelar").click(confirmaCancelacion);
		
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
			url: "control/cancelar_corridas.php",
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

function imprimirTicket(event){
	console.log("imprimirTicket()");
	var id_registro = $(this).data("id_registro");
	// var url = $(this).data("url");
	var boton = $(this); 
	var icono = boton.find("fas");
	
	boton.prop("disabled", true);
	icono.toggleClass("fa-print fa-spinner fa-spin");
	
	$.ajax({
		url: "impresion/imprimir_boletaje.php" ,
		data:{
			id_registro : id_registro
		}
		}).done(function (respuesta){
		
		$("#ticket").html(respuesta); 
		window.print();
		}).always(function(){
		
		boton.prop("disabled", false);
		icono.toggleClass("fa-print fa-spinner fa-spin");
		
	});
}
