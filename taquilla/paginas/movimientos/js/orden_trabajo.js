$(document).ready(onLoad);

Date.prototype.addDays = function(days) {
	var date = new Date(this.valueOf());
	date.setDate(date.getDate() + days);
	return date;
}



function calcularFechaFinal(){
	console.log("calcularFechaFinal()")
	// var date = new Date();
	
	// alert(date.addDays(5));
	
	// var today = new Date();
    // var newdate = new Date();
    // newdate.setDate(today.getDate()+29);
    // document.writeln(newdate);
	
	var date_inicial = new Date($("#fecha_inicio").val());
	var date_inicial_parse = Date.parse($("#fecha_inicio").val());
	
	var date_final = date_inicial.addDays(Number($("#dias").val()) + 1);
	
	$("#fecha_fin").val(date_final.toString("yyyy-MM-dd"));
	console.log("fecha_inicio ", $("#fecha_inicio").val())
	console.log("date_inicial_parse ", date_inicial_parse)
	console.log("dias number", Number($("#dias").val()))
	console.log("date locale ",date_inicial.toLocaleDateString('en-US'));
	console.log("date_inicial sec", date_inicial)
	console.log("date_inicial", date_inicial.toString("yyyy-MM-dd"))
	console.log("date_final", date_final.toString("yyyy-MM-dd"))
	
}


$('#num_eco').on('keyup',function(ev){
	if(event.which == 13 || event.which == 0){
		event.preventDefault();
		
		$('#num_eco').blur();
	}
	
} );
$('#num_eco').on('blur',buscarUnidad );

function buscarUnidad(){
	
	var num_eco = $("#num_eco").val();
	if(num_eco == ''){
		alertify.error("Ingrese un Num Eco");
		return false;
	}
	
	console.log("buscarUnidad()")
	$("#num_eco").addClass("cargando");
	$.ajax({
		url: 'control/checar_estatus.php',
		method: 'GET',
		dataType: 'JSON',
		data: {num_eco: num_eco}
		}).done(function(respuesta){
		console.log("buscarUnidad", respuesta) 
		if(respuesta.num_rows == 0){
			alertify.error("No encontrado")
			
			$("#num_eco").focus();
		}
		else{
			
			if(respuesta.filas.estatus_unidades == "Alta"){
				$.each(respuesta.filas, function(name, value){
					console.log("name:", name)
					console.log("value:", value)
					$("#form_edicion #"+name).val(value);
					
					if(name == 'cuenta_derroteros'){
						console.log("cuenta", name)
						$("#saldo_tarjetas").val(value);
					}
				})
			}
			else{
				alertify.error("La Unidad se encuentra Inactiva");
				$('#num_eco').focus();
			}
			
			
		}
		}).always(function(){
		
		$("#num_eco").removeClass("cargando");
	});
	
	
}

function onLoad(){
	
	listarRegistros();
	
	
	
	$("#dias").on('change', calcularFechaFinal)
	$("#fecha_inicio").on('change', calcularFechaFinal)
	
	$(".nuevo").on('click',function(){
		console.log("Nuevo")
		$("#form_edicion")[0].reset();
		$("#modal_edicion .modal-title").text("Nueva Orden de Trabajo");
		$("#modal_edicion").modal("show");
		
	});
	
	$("#form_filtros").on("submit", filtrarRegistros);
	$('#form_edicion').on('submit', guardarRegistro);
	$('#form_reporte').on('submit', guardarReporte);
	
	
	$("#lista_registros").on("click", ".btn_reporte",nuevoReporte)
}



function filtrarRegistros(evt){
	console.log("filtrarRegistros()")
	evt.preventDefault();
	
	listarRegistros();
	
}


function nuevoReporte(){
	console.log("nuevoReporte")
	$("#form_reporte")[0].reset();
	$("#reportes_id_ordenes").val($(this).data("id_registro"))
	
	$("#modal_reporte").modal("show");
	
}


function listarRegistros(){
	console.log("listarRegistros()")
	
	let boton = $("#form_filtros").find(":submit");
	let icono = boton.find(".fas");
	
	boton.prop("disabled", true);
	icono.toggleClass("fa-search fa-spinner fa-spin");
	
	$.ajax({
		url: 'consultas/listar_ordenes.php',
		data: $("#form_filtros").serialize()
		}).done(function(respuesta){
		$("#lista_registros").html(respuesta)
		
		}).always(function(){
		
		boton.prop("disabled", false);
		icono.toggleClass("fa-search fa-spinner fa-spin");
	});
}


function guardarRegistro(event){
	console.log("guardarRegistro()");
	event.preventDefault();
	let form = $(this);
	let boton = form.find(':submit');
	let icono = boton.find('.fa');
	let datos = form.serialize();
	
	
	boton.prop('disabled',true);
	icono.toggleClass('fa-save fa-spinner fa-pulse ');
	
	$.ajax({
		url: 'consultas/guardar_orden.php',
		method: 'POST',
		dataType: 'JSON',
		data: datos
		}).done(function(respuesta){
		if(respuesta.estatus == 'success'){
			
			alertify.success('Se ha guardado correctamente');
			$('#modal_edicion').modal('hide');
			window.open("impresion/imprimir_orden_trabajo.php?id_registro="+respuesta.insert_id )
			listarRegistros();
		}
		else{
			alertify.error('Ocurrio un error');
		}
		}).always(function(){
		boton.prop('disabled',false);
		icono.toggleClass('fa-save fa-spinner fa-pulse');
	});
}

function guardarReporte(event){
	console.log("guardarReporte()");
	event.preventDefault();
	let form = $(this);
	let boton = form.find(':submit');
	let icono = boton.find('.fa');
	let datos = form.serialize();
	
	
	boton.prop('disabled',true);
	icono.toggleClass('fa-save fa-spinner fa-pulse ');
	
	$.ajax({
		url: 'consultas/guardar_reporte.php',
		method: 'POST',
		dataType: 'JSON',
		data: datos
		}).done(function(respuesta){
		if(respuesta.estatus == 'success'){
			
			alertify.success('Se ha guardado correctamente');
			$('#modal_reporte').modal('hide');
			
			listarRegistros();
		}
		else{
			alertify.error('Ocurrio un error');
		}
		}).always(function(){
		boton.prop('disabled',false);
		icono.toggleClass('fa-save fa-spinner fa-pulse');
	});
}
