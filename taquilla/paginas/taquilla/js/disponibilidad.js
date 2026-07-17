$('#form_filtros').on('submit', function(event){
	event.preventDefault();
	listarRegistros();
	
});


listarRegistros();

function listarRegistros(){ 
	var boton =$('#form_filtros').find(":submit");
	var icono =boton.find("i");
	
	boton.prop("disabled", true);
	icono.toggleClass("fa-search fa-spinner fa-spin");
	
	return $.ajax({
		url: 'consultas/lista_disponibilidad.php',
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


function cargarRegistro() {
	console.log("cargarRegistro()");
	var $boton = $(this);
	var id_registro= $(this).data("id_registro");
	
	$boton.prop("disabled", true);
	
	$.ajax({
		url: '../../funciones/fila_select.php',
		method: 'GET',
		data: {
			tabla: "precios_boletos",
			id_campo: "id_precio",
			id_valor: id_registro
			
		}
		}).done(function(respuesta){
		console.log("imprime registros")
		$boton.prop("disabled", false);
		
		$.each(respuesta.data, function(name , value){
			$("#form_edicion").find("#"+ name).val(value);
			
		});
		
		$("#modal_edicion").modal("show");
		
	});
}