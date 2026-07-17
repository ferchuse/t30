$('#form_filtros').on('submit', function(event){
	event.preventDefault();
	listarRegistros();
	
});

$("#btn_exportar").click(function(event){
		window.open("consultas/exportar_comisiones.php?"+ $("#form_filtros").serialize())
	});

$("input").on("focus", function(){
	
	$(this).select();
})

listarRegistros();

function listarRegistros(){ 
	var boton =$('#form_filtros').find(":submit");
	var icono =boton.find("i");
	
	boton.prop("disabled", true);
	icono.toggleClass("fa-search fa-spinner fa-spin");
	
	return $.ajax({
		url: 'consultas/lista_comisiones.php',
		method: 'GET',
		data: $("#form_filtros").serialize()
		}).done(function(respuesta){
		
		$('#lista_registros').html(respuesta);
		
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

