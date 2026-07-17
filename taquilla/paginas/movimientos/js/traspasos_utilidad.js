

$(document).ready(function(){ 
	listarRegistros();
	
	$("#imprimir_recibos").click(function(){
		imprimirTicket($(this).data("id_registro") , $("#folios_seleccionados").val())
		
	});
	
	$('#form_filtro').on('submit', function filtrar(event){
		event.preventDefault();
		
		listarRegistros();
		
	});
	
	$("#btn_agregar").click(agregarUnidad)
	
	
	$('#nuevoSalida').on('click',function(){
		$('#form_salida')[0].reset();
		$('.modal-title').text('Nuevo Traspaso');
		$('#modal_salida').modal('show');
	}); 
	
	$('#form_salida').on('submit', guardarRecibo)
	
	
	
	
	$('.monto').on('keyup', function calculaSaldo(event){
		console.log("calculaSaldo()")
		var monto_total = 0;
		var $fila = $(this).closest(".form-row");
		var monto = Number($fila.find(".monto").val());
		var saldo_anterior = Number($fila.find(".saldo_actual").val());
		$fila.find(".saldo_restante").val(saldo_anterior - monto ); 
		
		$(".monto").each(function sumaMontos(index, item){
			monto_total+= Number($(item).val());
			
		})
		
		$("#importe_traspaso").val(monto_total.toFixed(2));
		
	});
	
	
	$('.num_eco').on('change', function cargarSaldo(event){
		console.log("cargarSaldo()")
		var $fila = $(this).closest(".form-row");
		
		var num_eco = $(this).val();
		
		$.ajax({
			url: 'control/buscar_unidad.php',
			method: 'GET',
			dataType: 'JSON',
			data: {"num_eco": num_eco}
			}).done(function(respuesta){
			
			
			// $.each(respuesta.filas, function(name, value){
			// $fila.find("."+name).val(value);
			
			
			// });
			$fila.find(".saldo_actual").val(Number(respuesta.filas.saldo_actual).toFixed(2));
			
			
			$fila.find(".monto").focus();
			
			
			}).always(function(){
			
			// $input.toggleClass("cargando");
		});
	});
	
	
	
});

function sumarImportes(){
	var monto_total = 0;
	if($("#unidades .form-row").length > 1){
		
		$(this).closest(".form-row").remove();
	}
	
	$(".monto").each(function sumaMontos(index, item){
		monto_total+= Number($(item).val());
	})
	
	$("#importe_traspaso").val(monto_total.toFixed(2));
	
	
}

function agregarUnidad(event){
	console.log("agregarUnidad")
	$("#unidades .form-row:first").clone(true).appendTo("#unidades");
	
	$(".quitar_unidad").click(quitarUnidad);
	
	sumarImportes();
}	

function quitarUnidad(event){
	console.log("quitarUnidad")

	if($("#unidades .form-row").length > 1){
		
		$(this).closest(".form-row").remove();
	}
	
	sumarImportes()
	
}	


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


function guardarRecibo(event){
	event.preventDefault();
	
	
	let form = $(this);
	let boton = form.find(':submit');
	let icono = boton.find('.fa');
	let datos = form.serializeArray();
	
	
	sumarImportes();
	
	boton.prop('disabled',true);
	icono.toggleClass('fa-save fa-spinner fa-pulse ');
	
	$.ajax({
		url: 'consultas/guardar_traspaso.php',
		method: 'POST',
		dataType: 'JSON',
		data:datos
		}).done(function(respuesta){
		if(respuesta.estatus == 'success'){
			alertify.success('Se ha agregado correctamente');
			$('#modal_salida').modal('hide');
			// imprimirTicket(respuesta.folio)
			listarRegistros();
			}else{
			alert(respuesta.error);
		}
		}).always(function(){
		boton.prop('disabled',false);
		icono.toggleClass('fa-save fa-spinner fa-pulse fa-fw');
	});
}


function cargarRegistro() {
	console.log("cargarRegistro()");
	
	$("#form_salida")[0].reset();
	
	var $boton = $(this);
	var id_registro= $(this).data("id_registro");
	
	$boton.prop("disabled", true);
	
	$.ajax({
		url: '../../funciones/fila_select.php',
		method: 'GET',
		data: {
			tabla: "recibos_salidas",
			id_campo: "id_reciboSalidas",
			id_valor: id_registro
			
		}
		}).done(function(respuesta){
		console.log("imprime registros")
		$boton.prop("disabled", false);
		
		$.each(respuesta.data, function(name , value){
			$("#form_salida").find("#"+ name).val(value);
			
		});
		
		$("#modal_salida").modal("show");
		// $('#lista_registros').html(respuesta);
		
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
		url: 'consultas/lista_traspasos.php',
		data: $("#form_filtro").serialize()
		}).done(function(respuesta){
		
		$("#tabla_registros").html(respuesta)
		
		$(".imprimir").click(function(){
			imprimirTicket($(this).data("id_registro"))
			
		});
		$(".cancelar").click(confirmaCancelacion);
		$('.btn_editar').on('click', cargarRegistro);
		$("#check_all").change(checkAll);
		
		$(".seleccionar").change(contarSeleccionados)
		
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

function imprimirTicket(folios){
	var boton = $(this);
	var icono = boton.find("fas");
	
	boton.prop("disabled", true);
	icono.toggleClass("fa-print fa-spinner fa-spin");
	
	$.ajax({
		url: "impresion/imprimir_traspaso.php",
		data:{
			folios: folios,
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
			url: "control/cancelar_traspaso.php",
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