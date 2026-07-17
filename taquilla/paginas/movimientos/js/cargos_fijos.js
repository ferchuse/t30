


$('#form_filtro').on('submit', function filtrar(event){
	event.preventDefault();
	
	listarRegistros();
	
});

$('#form_filtro select').on('change', function filtrar(event){
	
		listarRegistros();
});


listarRegistros();

function guardarCargo(event){
	console.log("event", event)
	if(event.which == 13 || event.type == "blur"){
		
		console.log("enter")
		
		let input = $(this);
		
		input.addClass('cargando');
		
		return $.ajax({
			url: 'consultas/guardar_cargos_fijos.php',
			method: "POST",
			dataType: "JSON",
			data: {
				"num_eco" : input.data("num_eco"),
				"fecha_cargos" : input.data("fecha_cargos"),
				"concepto" : input.data("concepto"),
				"monto" : input.val()
				
			}  
			}).done(function(respuesta){
			
			if(respuesta.estatus == "success"){
				alertify.success("Actualizado")
			}
			else{
				alertify.error("Error")
			}
			}).always(function(){
			
			input.removeClass('cargando');
			
		});
	}
	
	
}
function listarRegistros(){
	console.log("listarRegistros()");
	
	let form = $("#form_filtro");
	let boton = form.find(":submit");
	let icono = boton.find('.fas');
	
	boton.prop('disabled',true);
	icono.toggleClass('fa-search fa-spinner fa-pulse ');
	
	return $.ajax({
		url: 'consultas/lista_cargos_fijos.php',
		data: $("#form_filtro").serialize()
		}).done(function(respuesta){
		
		$("#tabla_registros").html(respuesta)
	
		
		$('.cargo').on('keyup', guardarCargo);
		$('.cargo').on('blur', guardarCargo);
		
		
		}).always(function(){
		
		boton.prop('disabled',false); 
		icono.toggleClass('fa-search fa-spinner fa-pulse ');
		
	});
	
}


