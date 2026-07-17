$(document).ready(function(){
	
	$('#tabla_DB').on('click', ".btn_editar", cargarRegistro);
	$('#form_filtros').on('submit',  function(event){
		event.preventDefault()
		listarPropietarios();
	});
	
	
	listarPropietarios();
	
	
	//========BOTON DE NUEVO=============
	$('#btn_modal').on('click',function(){
		$('#form_propietario')[0].reset();
		$('.modal-title').text('Nuevo Propietario');
		// $('#modal_propietario').modal('show');
		$('#modal_propietario').modal({ backdrop: 'static'}).modal('show').on('shown.bs.modal', function () {
			$('#form_propietario input:eq(1)').trigger("focus");
		});
	});
	
	//=================GUARDAR======================================
	$('#form_propietario').submit( guardarRegistro);
	// $('#nombre_propietarios').blur( buscarDuplicado);
	
	//=============BUSCAR DENTRO DE LA TABLA===========================
	$('#buscar_nombre').keyup(function filtro_buscar(){
		let indice = $(this).data("indice");
		let valor_filtro = $(this).val();
		let num_rows = buscar(valor_filtro,'dataTable',indice);
		
		if(num_rows == 0){
			$('.mensaje').html(`
				<div class="alert alert-dark text-center" role="alert">
				<strong>NO se ha encontrado</strong>
				</div>
			`);
			}else{
			$('.mensaje').html("");
		}
	});
	
	
});


function guardarRegistro(event){
	event.preventDefault();
	
	boton= $(this).find(":submit")
	
	boton.prop("disabled", true)
	
	let datos = $(this).serializeArray();
	$.ajax({
		url: '../../funciones/guardar.php',
		dataType: 'JSON',
		method: 'POST',
		data: {
			tabla: 'propietarios',
			datos: datos
		}
		}).done(function(respuesta){
		if(respuesta.estatus == "success"){
			alertify.success('Se ha agregado correctamente');
			$('#modal_propietario').modal('hide');
			listarPropietarios();
			}else{
			alertify.error("Error al guardar");
			console.log(respuesta.mensaje);
		}
		}).always(function(){
		
		boton.prop("disabled", false)
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
			tabla: 'propietarios',
			id_campo: id_campo,
			campo: 'id_propietarios'
		}
		}).done(function(respuesta){
		if(respuesta.estatus == "success"){
			$.each(respuesta.mensaje[0],function(index,element){
				$('#'+index).val(element);
			});
			$('.modal-title').text('Editar propietario');
			$('#modal_propietario').modal('show');
			}else{
			console.log("error al buscar ");
		}
		
		
	});
}

function buscarDuplicado(){
	
	let nombre = $(this).val();
	
	if($("#id_propietarios").val() == ''){
		$("#nombre_propietarios").addClass("cargando");
		
		$.ajax({
			url: '../../funciones/fila_select.php',
			method: 'GET',
			dataType: 'JSON',
			data: {
				tabla: 'propietarios',
				id_campo: 'nombre_propietarios',
				id_valor: nombre
			}
			}).done(function(respuesta){
			
			$("#nombre_propietarios").removeClass("cargando");
		});
	}
}

function listarPropietarios() {
	return $.ajax({
		url: 'consultas/listar_propietarios.php',
		method: 'GET',
		data: $("#form_filtros").serialize()
		}).done(function(respuesta){
		
		$('#tabla_DB').html(respuesta);
		
	});
}				