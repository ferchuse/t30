$(document).ready(function(){ 
	
	
	$('#form_filtro').on('submit', function filtrar(event){
		event.preventDefault();
		
		listarRegistros();
		
	});
	
	listarRegistros();
	$('#tabla_registros').on('keyup','.aportacion' , guardarRegistro); 
	$('#tabla_registros').on('blur','.aportacion' , guardarRegistro); 
	
	// $('#form_cargos').on('submit', guardarRegistro)
	
	
});

function listarRegistros(){
	console.log("listarRegistros()");
	
	let form = $("#form_filtro");
	let boton = form.find(":submit");
	let icono = boton.find('.fa');
	
	boton.prop('disabled',true);
	icono.toggleClass('fa-search fa-spinner fa-pulse ');
	
	return $.ajax({
		url: 'control/lista_aportaciones_mensuales.php',
		data: $("#form_filtro").serialize()
		}).done(function(respuesta){
		
		$("#tabla_registros").html(respuesta)
		
		
		
		
		}).always(function(){  
		
		boton.prop('disabled',false);
		icono.toggleClass('fa-search fa-spinner fa-pulse fa-fw');
		
	});
}


function guardarRegistro(event){
	console.log("guardarRegistro()");
	event.preventDefault();
	
	
	if(event.key == "Enter"){
		
		
		input= $(this);
		
		input.addClass("cargando");
		
		let aportacion =  $(this).val();
		let fecha =  $(this).data("fecha");
		let id_empresas =  $(this).data("id_empresas");
		
		
		$.ajax({
			url: 'control/guardar_aportacion.php',
			method: 'POST',
			dataType: 'JSON',
			data: {
				"aportacion": aportacion,
				"id_empresas": id_empresas,
				"fecha": fecha
				
				
			}
			}).done(function(respuesta){
			if(respuesta.estatus == 'success'){
				alertify.success('Guardado');
				listarRegistros();
			}
			else{
				alertify.error('Ocurrió un error');
			}
			}).always(function(){
			input.removeClass("cargando");
		});
	}
}




