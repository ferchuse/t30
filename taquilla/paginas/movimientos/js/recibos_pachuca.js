$(document).ready(function(){ 
	
	listarRegistros();
	
	$('#form_filtro').on('submit', function filtrar(event){
		event.preventDefault();
		
		listarRegistros();
		
	});
	
	
	$('#nuevoSalida').on('click',function(){
		$('#form_salida')[0].reset();
		$('.modal-title').text('Nuevo Recibo Pachuca');
		$('#modal_salida').modal('show');
	}); 
	
	$('#form_salida').on('submit', guardarRecibo)
	
	//=========BUSCAR EMPRESA=========
	$("#numero_conductor").keyup(function filtro_buscar(){
		var indice = $(this).data("indice");
		var valor_filtro = $(this).val();
		var num_rows = buscar(valor_filtro,'tabla_conductores',indice);
		if(num_rows == 0){
			$('#mensaje').html("<div class='alert alert-dark text-center' role='alert'><strong>No se ha encontrado.</strong></div>");
			}else{
			$('#mensaje').html('');
		}
	});
	//=========BUSCAR RECIBO DE SALIDA=========
	$("#nombre_empresa").keyup(function filtro_buscar(){
		var indice = $(this).data("indice");
		var valor_filtro = $(this).val();
		var num_rows = buscar(valor_filtro,'tabla_recibos',indice);
		if(num_rows == 0){
			$('#mensaje').html("<div class='alert alert-dark text-center' role='alert'><strong>No se ha encontrado.</strong></div>");
			}else{
			$('#mensaje').html('');
		}
	});
	$("#nombre_beneficiario").keyup(function filtro_buscar(){
		var indice = $(this).data("indice");
		var valor_filtro = $(this).val();
		var num_rows = buscar(valor_filtro,'tabla_recibos',indice);
		if(num_rows == 0){
			$('#mensaje').html("<div class='alert alert-dark text-center' role='alert'><strong>No se ha encontrado.</strong></div>");
			}else{
			$('#mensaje').html('');
		}
	});
	$("#buscar_salida").keyup(function filtro_buscar(){
		var indice = $(this).data("indice");
		var valor_filtro = $(this).val();
		var num_rows = buscar(valor_filtro,'tabla_recibos',indice);
		if(num_rows == 0){
			$('#mensaje').html("<div class='alert alert-dark text-center' role='alert'><strong>No se ha encontrado.</strong></div>");
			}else{
			$('#mensaje').html('');
		}
	});
	$("#fecha_recibo").change(function filtro_buscar(){
		var indice = $(this).data("indice");
		var valor_filtro = $(this).val();
		console.log(valor_filtro);
		var num_rows = buscar(valor_filtro,'tabla_recibos',indice);
		if(num_rows == 0){
			$('#mensaje').html("<div class='alert alert-dark text-center' role='alert'><strong>No se ha encontrado.</strong></div>");
			}else{
			$('#mensaje').html('');
		}
	});
	
	
	
	
});

function guardarRecibo(event){
	event.preventDefault();
	let form = $(this);
	let boton = form.find(':submit');
	let icono = boton.find('.fa');
	let datos = form.serializeArray();
	let fecha = new Date().toString('yyyy-MM-dd HH:mm:ss')
	
	datos.push({
		name: 'fecha_reciboSalidas',
		value : fecha
		
	});
	datos.push({
		name: 'id_usuarios',
		value : $("#id_usuarios").val()
	});
	
	boton.prop('disabled',true);
	icono.toggleClass('fa-save fa-spinner fa-pulse ');
	
	$.ajax({
		url: 'control/guardar_recibo_pachuca.php',
		method: 'POST',
		dataType: 'JSON',
		data:{
			tabla: 'recibos_pachuca',
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
		url: 'control/lista_recibos_pachuca.php',
		data: $("#form_filtro").serialize()
		}).done(function(respuesta){
		
		$("#tabla_registros").html(respuesta)
		
		$(".imprimir").click(function(){
			imprimirTicket($(this).data("id_registro"))
			
		});
		$(".cancelar").click(confirmaCancelacion);
		
		
		}).always(function(){  
		
		boton.prop('disabled',false);
		icono.toggleClass('fa-search fa-spinner fa-pulse fa-fw');
		
	});
}







function obtenerFecha(){
	let today = new Date();
	let dd = today.getDate();
	
	let mm = today.getMonth()+1; 
	const yyyy = today.getFullYear();
	if(dd<10) 
	{
		dd=`0${dd}`;
	} 
	
	if(mm<10) 
	{
		mm=`0${mm}`;
	} 
	return today = `${yyyy}-${mm}-${dd}`;
}

function imprimirTicket(id_registro){
	var boton = $(this);
	var icono = boton.find("fas");
	
	boton.prop("disabled", true);
	icono.toggleClass("fa-print fa-spinner fa-spin");
	
	$.ajax({
		url: "impresion/imprimir_recibo_pachuca.php",
		data:{
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
			url: "control/cancelar_recibo_pachuca.php",
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