$(document).ready( function onLoad(){
	console.log("onLoad");
	listarRegistros(); 
	
	$('#form_edicion').submit( guardarRegistro );
	$('#form_filtros').submit( function(event){
		event.preventDefault();
		listarRegistros();
	} );
	$('.nuevo').click( nuevoRegistro );
	// $("#tabla_registros").tableExport();
	
});



function nuevoRegistro(event){
	console.log("nuevoRegistro") 
	$("#form_edicion")[0].reset();
	$("#modal_edicion").modal("show");
	
}
function guardarRegistro(event){
	console.log("guardarRegistro")
	event.preventDefault();
	let datos = $(this).serialize();
	let boton = $(this).find(":submit");
	let icono = boton.find(".fas");
	
	boton.prop("disabled", true);
	icono.toggleClass("fa-save fa-spinner fa-spin");
	
	$.ajax({
		url: 'control/guardar_usuarios.php',
		dataType: 'JSON',
		method: 'POST',
		data: datos
		
	}).done(
	function(respuesta){
		
		if(respuesta.estatus == "success"){
			alertify.success('Se ha agregado correctamente');
			$('#form_edicion')[0].reset();
			$('#modal_edicion').modal("hide");
			listarRegistros();
			}else{
			console.log(respuesta.mensaje);
		}
		}).always(function(){
		
		boton.prop("disabled", false);
		icono.toggleClass("fa-save fa-spinner fa-spin");
		
		
	}); 
}

//FUNCION DE ENLISTAR
function listarRegistros() {
	console.log("listarRegistros");
	$.ajax({
		url: 'control/listar_usuarios.php',
		method: 'GET',
		data: $("#form_filtros").serialize()
		}).done(function(respuesta){
		
		$('#lista_registros').html(respuesta);
		// $('#tabla_registros').DataTable({
		// "language": {
		// "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
		// }
		// });
		
		//BOTON DE Editar
		$('.btn_editar').on('click', cargarRegistro);
		
	});
}
//FUNCION DE Cargar datos
function cargarRegistro() {
	console.log("cargarRegistro()");
	var $boton = $(this);
	var id_registro= $(this).data("id_registro");
	
	$boton.prop("disabled", true);
	
	$.ajax({
		url: 'control/cargar_permisos.php',
		method: 'GET',
		data: {
			id_usuarios: id_registro
		}
		}).done(function(respuesta){
		console.log("imprime registros")
		$boton.prop("disabled", false);
		console.table(respuesta.data.permisos);
		console.table(respuesta.data.permisos);
		
		$("#form_edicion")[0].reset();
		
		//Imprime Datos del Usuario
		$.each(respuesta.data.usuarios, function(name , value){
			$("#form_edicion").find("#"+ name).val(value);
			// console.log("name", name)
			if(name == "id_usuarios"){
				$("#edicion_id_usuarios").val(value);
			}
			
		});
		
		//Imprime permisos
		$.each(respuesta.data.permisos, function(index , permiso){
			$input_paginas = $('input[value="'+permiso.id_paginas+'"].id_paginas');
			
			$input_paginas.closest("tr").find('input[value="'+permiso.permiso+'"]').prop("checked", true);
			
		});
		
		//Imprime Accesos Empresas
		$.each(respuesta.data.acceso_empresas, function(index , acceso){
			$empresas = $('input[value="'+acceso.id_empresas+'"].id_empresas');
			
			console.log("$empresas", $empresas)
			
			$empresas.closest("tr").find('input[value="'+acceso.acceso+'"]').prop("checked", true);
		});
		
		$("#modal_edicion").modal("show");
		// $('#lista_registros').html(respuesta);
		
	});
}