

listarRegistros();

$(".nuevo").on('click',function(){
	$("#form_edicion")[0].reset();
	$(".modal-title").text("Edicion de Precio");
	$("#modal_edicion").modal("show");
	
});

$('#form_edicion').on('submit', guardarRegistro);


function guardarRegistro(event){
	event.preventDefault();
	let form = $(this);
	 
	let boton = form.find(':submit');
	let icono = boton.find('.fa');
	let datos = form.serializeArray();
	
	boton.prop('disabled',true);
	icono.toggleClass('fa-save fa-spinner fa-spin');
	
	$.ajax({
		url: '../../funciones/guardar.php',
		method: 'POST',
		dataType: 'JSON',
		data:{
			tabla: "precios_boletos",
			datos: datos
		}
		}).done(function(respuesta){
		if(respuesta.estatus == 'success'){
			alertify.success('Se ha guardado correctamente');
			$('#modal_edicion').modal('hide');
			listarRegistros();
			}else{
			alertify.error('Ocurrio un error');
		}
		}).always(function(){
		boton.prop('disabled',false);
		icono.toggleClass('fa-save fa-spinner fa-spin');
	});
}





function listarRegistros(){ 
	
	return $.ajax({
		url: 'control/lista_precios_boletos.php',
		method: 'GET',
		data: {}
		}).done(function(respuesta){
		
		$('#tabla_registros').html(respuesta);
		
		$('.eliminar').click();
		$('.editar').click(cargarRegistro);
		
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