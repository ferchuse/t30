$(document).ready( function onLoad(){
	console.log("onLoad");
	// listarRegistros(); 
	
	// $('#form_filtros').submit();

	$('#form_filtros').submit( function(event){
		event.preventDefault();
		listarEncuestasTaxi();
		listarEncuestasColectivo();
	} );
	
	
	$('.btn_editar').on('click', cargarRegistro);
		
});



function listarEncuestasTaxi() {
	console.log("listarRegistros");
	$.ajax({
		url: 'control/listar_encuestas.php',
		method: 'GET',
		data: $("#form_filtros").serialize()
		}).done(function(respuesta){
		
		$('#encuestas_taxi').html(respuesta);
		
		
	});
}

function listarEncuestasColectivo() {
	console.log("listarEncuestasColectivo");
	$.ajax({
		url: 'control/listar_encuestas_colectivo.php',
		method: 'GET',
		data: $("#form_filtros").serialize()
		}).done(function(respuesta){
		
		$('#encuestas_colectivo').html(respuesta);
		
		
	});
}



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