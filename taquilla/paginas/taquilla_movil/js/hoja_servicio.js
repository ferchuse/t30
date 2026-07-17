

$("#form_filtros").submit(function(event){
	event.preventDefault();
	listarRegistros();
});

listarRegistros();



function listarRegistros(){ 
	
	return $.ajax({
		url: 'consultas/hoja_servicio.php',
		method: 'GET',
		data: $("#form_filtros").serialize()
		}).done(function(respuesta){
		
		$('#tabla_registros').html(respuesta);
		
		
	});
}
