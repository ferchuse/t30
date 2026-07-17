$(document).ready( function onLoad(){ 
	
	
	listarRegistros();
	
	
	$("#tipo_busqueda").change(function(){
		
		if($(this).val() == "libre"){
			$("#fecha_inicial").prop("disabled", false).closest("div").prop("hidden", false)
			$("#fecha_final").prop("disabled", false).closest("div").prop("hidden", false)
			
			
			$("#year").prop("disabled", true).closest("div").prop("hidden", true)
			$("#mes_inicial").prop("disabled", true).closest("div").prop("hidden", true)
			$("#mes_final").prop("disabled", true).closest("div").prop("hidden", true)
			
			
			
		}
		else{
			
			$("#fecha_inicial").prop("disabled", true).closest("div").prop("hidden", true)
			$("#fecha_final").prop("disabled", true).closest("div").prop("hidden", true)
			
			
			$("#year").prop("disabled", false).closest("div").prop("hidden", false)
			$("#mes_inicial").prop("disabled", false).closest("div").prop("hidden", false)
			$("#mes_final").prop("disabled", false).closest("div").prop("hidden", false)
			
			
			
		}
	})
	
	
	
	
	$('#form_filtro').on('submit', function filtrar(event){
		
		listarRegistros();
		return false;
	});
	
	
	
});





function listarRegistros(){
	console.log("listarRegistros()");
	
	let form = $("#form_filtro");
	let boton = form.find(":submit");
	let icono = boton.find('i');
	
	boton.prop('disabled',true);
	icono.toggleClass('fa-search fa-spinner fa-pulse ');
	
	return $.ajax({
		url: 'control/lista_estado_cuenta.php',
		data: $("#form_filtro").serialize()
		}).done(function(respuesta){
		
		$("#tabla_registros").html(respuesta)
		
		$('#tabla_registros .focusable')
		.SpatialNavigation()
		.focus(function() {
			$(this).addClass("selected");
			// $(this).find('input:radio').prop('checked', true);
			selected = $(this);
		})
		.blur(function() {
			$(this).removeClass('selected');
			$(this).find('input:radio').prop('checked', false);
		})
		.first()
		.focus();
		
		
		}).always(function(){  
		
		boton.prop('disabled',false);
		icono.toggleClass('fa-search fa-spinner fa-pulse fa-fw');
		
	});
}

