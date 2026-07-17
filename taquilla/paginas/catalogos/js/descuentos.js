$(document).ready(function(){
	
	listarRegistros();
	
	
	$("#form_filtros").submit(function(event){
		event.preventDefault();
		
		boton = $(this).find(":submit")
		icono = boton.find("i")
		
		boton.prop('disabled',true);
		
		icono.toggleClass('fa-search fa-spinner fa-pulse ');
		
		
		listarRegistros().done(function(){
			boton.prop('disabled',false);
			icono.toggleClass('fa-search fa-spinner fa-pulse ');
			
			
		});
		
	})
	
	
	
	
	$('.nuevo').on('click',function(){
	
		
		$('#form_edicion')[0].reset();
		
		
		$('.modal-title').text('Nuevo Descuento');
		$('#modal_edicion').modal({ backdrop: 'static'}).modal('show').on('shown.bs.modal', function () {
			$('#form_edicion input:eq(1)').trigger("focus");
		});
	});
	
	
	
	
	$('#form_edicion').on('submit', guardarRegistro)
	
	
	
	
	
	
});


function guardarRegistro(event){
	event.preventDefault();
	let form = $(this);
	let boton = form.find(':submit');
	let icono = boton.find('.fa');
	let datos = form.serializeArray();
	
	boton.prop('disabled',true);
	icono.toggleClass('fa-save fa-spinner fa-pulse ');
	
	$.ajax({
		url: '../../funciones/guardar.php',
		method: 'POST',
		dataType: 'JSON',
		data:{
			tabla: 'descuentos',
			datos: datos
		}
		}).done(function(respuesta){
		if(respuesta.estatus == 'success'){
			alertify.success('Se ha agregado correctamente');
			$('#modal_edicion').modal('hide');
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
	
	return $.ajax({
		url: 'consultas/listar_descuentos.php',
		method: 'GET',
		data: $("#form_filtros").serialize()
		}).done(function(respuesta){
		
		
		
		$('#tabla_registros').html(respuesta);
		
		
		
		$('.eliminar').click(function(){
			let boton = $(this);
			let id_conductores = boton.data('id_conductores');
			var fila = boton.closest('tr');
			
			alertify.confirm('Confirmacion', '¿Deseas eliminarlo?', eliminar , function(){
			});
			
			function eliminar(){
				$.ajax({
					url: 'control/eliminar.php',
					method: 'POST',
					dataType: 'JSON',
					data: {
						tabla: 'descuentos',
						id_campo: 'id_descuento',
						campo: id_conductores
					}
					}).done(function(respuesta){
					if(respuesta.estatus == 'success'){
						alertify.success('Se ha eliminado correctamente');
						fila.fadeOut(1000);
						}else{
						alertify.error('Ocurrio un error');
					}
				});
			}
			
		});
		
		
		$('.editar').click(cargarRegistro);
		
		
		
	});
}

function cargarRegistro(){
	// CONSOLE.LOG("cargarRegistro")
	var boton = $(this);
	var icono = boton.find('.fas');
	var id_descuento = boton.data('id_descuento');
	boton.prop('disabled',true);
	icono.toggleClass('fas fa-pencil-alt fa fa-spinner fa-pulse fa-fw');
	
	$.ajax({
		url: '../../funciones/listar.php',
		method: 'POST',
		dataType: 'JSON',
		data: {
			tabla: 'descuentos',
			id_campo: 'id_descuento',
			campo: id_descuento
		}
		}).done(function(respuesta){
		if(respuesta.estatus == 'success'){
			
			$.each(respuesta.mensaje[0],function(name , value){
				$('#'+name).val(value);
				
			});
			
			
			
			$('.modal-title').text('Editar Conductor');
			$('#modal_edicion').modal('show');
			}else{
			//console.log(respuesta.mensaje);
		}
		}).always(function(){
		boton.prop('disabled',false);
		icono.toggleClass('fas fa-pencil-alt fa fa-spinner fa-pulse fa-fw');
	});
}

