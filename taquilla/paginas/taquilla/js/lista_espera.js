$(document).ready(function(){
	
	$('#tabla_DB').on('click', ".btn_editar", cargarRegistro);
	
	$('#form_filtros').on('submit',  function(event){
		event.preventDefault()
		listarRegistros();
	});
	
	
	
	
	$('#btn_modal').on('click',function(){
		$('#form_lista_espera')[0].reset();
		$('.modal-title').text('Nuevo Viaje');
		// $('#modal_lista_espera').modal('show');
		$('#modal_lista_espera').modal({ backdrop: 'static'}).modal('show').on('shown.bs.modal', function () {
			$('#form_lista_espera input:eq(1)').trigger("focus");
		});
	});
	
	
	$('#form_lista_espera').submit( guardarRegistro);
	
	
	
	listarRegistros();
	
	
});


function guardarRegistro(event){
	event.preventDefault();
	
	boton= $(this).find(":submit")
	icono= boton.find("i")
	
	boton.prop("disabled", true)
	icono.toggleClass("fa-save fa-spinner fa-spin");
	
	
	$.ajax({
		url: 'consultas/guardar_lista_espera.php',
		dataType: 'JSON',
		method: 'POST',
		data: $("#form_lista_espera").serialize()
		}).done(function(respuesta){
		if(respuesta.estatus == "success"){
			alertify.success('Se ha agregado correctamente');
			$('#modal_lista_espera').modal('hide');
			listarRegistros();
		}
		else{
			alertify.error("Error al guardar" + respuesta.error);
			
		}
		}).always(function(){
		
		boton.prop("disabled", false)
		icono.toggleClass("fa-save fa-spinner fa-spin");
		
	});
}

function cargarRegistro(){
	console.log("cargarRegistro");
	let id_campo = $(this).data('id_registro');
	
	
	$.ajax({
		url: '../../funciones/listar.php',
		method: 'POST',
		dataType: 'JSON',
		data: {
			tabla: 'lista_espera',
			id_campo: id_campo,
			campo: 'id_espera'
		}
		}).done(function(respuesta){
		if(respuesta.estatus == "success"){
			$.each(respuesta.mensaje[0],function(index,element){
				$('#'+index).val(element);
			});
			$('.modal-title').text('Editar propietario');
			$('#modal_lista_espera').modal('show');
			}else{
			console.log("error al buscar ");
		}
		
		
	});
}


function listarRegistros() {
	return $.ajax({
		url: 'consultas/lista_espera.php',
		method: 'GET',
		data: $("#form_filtros").serialize()
		}).done(function(respuesta){
		
		$('#tabla_DB').html(respuesta);
		
	});
}				