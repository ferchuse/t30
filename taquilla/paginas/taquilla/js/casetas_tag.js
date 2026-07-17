$('#form_filtros').on('submit', function(event){
	event.preventDefault();
	listarRegistros();
	
});


$('#form_casetas_tag').on('submit', function cargarExcel(event){
	event.preventDefault();
	var boton =$('#form_casetas_tag').find(":submit");
	var icono =boton.find("i");
	
	boton.prop("disabled", true);
	icono.toggleClass("fa-search fa-spinner fa-spin");
	
	return $.ajax({
		url: 'consultas/cargar_excel.php',
		method: 'POST',
		dataType: 'JSON',
		data: $("#form_casetas_tag").serialize()
		}).done(function(respuesta){
		
		console.log(respuesta)
		
		
		
		alert("Registros Agregados:" + respuesta.guardados  + " Registros Repetidos: " + respuesta.repetidos)
		
		$("#modal_casetas_tag").modal("hide")
		
		listarRegistros();
		
		}).always(function(){
		
		boton.prop("disabled", false);
		icono.toggleClass("fa-search fa-spinner fa-spin");
	});
});

$('#btn_cargar_excel').on('click', function(event){
	// event.preventDefault();
	$("#form_casetas_tag")[0].reset()
	$("#modal_casetas_tag").modal("show")
	
});


listarRegistros();

function listarRegistros(){ 
	var boton =$('#form_filtros').find(":submit");
	var icono =boton.find("i");
	
	boton.prop("disabled", true);
	icono.toggleClass("fa-search fa-spinner fa-spin");
	
	return $.ajax({
		url: 'consultas/lista_casetas_tag.php',
		method: 'GET',
		data: $("#form_filtros").serialize()
		}).done(function(respuesta){
		
		$('#lista_registros').html(respuesta);
		
		$('#tabla_tag').DataTable({
			language: {
				url: '//cdn.datatables.net/plug-ins/2.1.8/i18n/es-MX.json',
			},
		});
		
		$('#lista_registros .focusable')
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
		
		boton.prop("disabled", false);
		icono.toggleClass("fa-search fa-spinner fa-spin");
		
	});
}


