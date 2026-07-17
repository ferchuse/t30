$("#activar_fecha").change(function(){
	
	$("#fecha_inicial").attr("disabled" , !$(this).prop("checked"))
	$("#fecha_final").attr("disabled" , !$(this).prop("checked"))
	
})
$('#id_beneficiarios').select2({'width': "100%"});
$('#id_empresas').select2({'width': "100%"});

listarRegistros();

$(".nuevo").on('click',function(){
	$("#form_edicion")[0].reset();
	$(".modal-title").text("Edicion de Precio");
	$("#modal_edicion").modal("show");
	
});

$('#form_edicion').on('submit', guardarRegistro);

$('#form_filtros').on('submit', function filtrar(event){
	event.preventDefault();
	
	listarRegistros();
	
});


$('#tabla_registros').on("click", ".btn_cancelar", confirmaCancelar);
$('#tabla_registros').on("click", ".btn_reset", resetBoleto);


function listarRegistros(){ 
	
	boton = $("#form_filtros").find(':submit');
	icono = boton.find('i');
	
	boton.prop('disabled',true);
	icono.toggleClass('fa-search fa-spinner fa-spin ');
	
	
	return $.ajax({
		url: 'consultas/lista_boletos_sencillos.php',
		method: 'GET',
		data: $('#form_filtros').serialize()
		}).done(function(respuesta){
		
		$('#tabla_registros').html(respuesta);
		
		
		boton.prop('disabled',false);
		icono.toggleClass('fa-search fa-spinner fa-spin ');
		
		
	});
}


function resetBoleto(event){
	console.log("resetBoleto()");
	let boton = $(this);
	let icono = boton.find(".fas");
	
	var folio = $(this).data("folio");
	
	
	boton.prop("disabled", true);
	icono.toggleClass("fa-redo fa-spinner fa-spin");
	
	if(confirm("¿Está seguro?")){
		
		return $.ajax({
			url: "consultas/reset_boleto.php",
			dataType:"JSON",
			method:"POST",
			data:{
				folio : folio
			}
			}).done(function (respuesta){
			if(respuesta.estatus == "success"){
				alertify.success("Correcto");
				listarRegistros();
			}
			else{
				alertify.error(respuesta.mensaje);
				
			}
			
			}).always(function(){
			boton.prop("disabled", false);
			icono.toggleClass("fa-redo fa-spinner fa-spin");
			
		});
		
	}
	
	
}	
function confirmaCancelar(event){
	console.log("confirmaCancelar()");
	let boton = $(this);
	let icono = boton.find(".fas");
	var folio = $(this).data("folio");
	var fila = boton.closest('tr');
	
	alertify.prompt()
	.setting({
		'reverseButtons': true,
		'labels' :{ok:"SI", cancel:'NO'},
		'title': "Cancelar Abono" ,
		'message': "Motivo de Cancelación" ,
		'onok':cancelarRegistro,
		'oncancel': function(){
			boton.prop('disabled', false);
			
		}
	}).show();
	
	function cancelarRegistro(evt, motivo){
		if(motivo == ''){
			console.log("Escribe un motivo");
			alertify.error("Escribe un motivo");
			return false;
			
		}
		boton.prop("disabled", true);
		icono.toggleClass("fa-times fa-spinner fa-spin");
		
		return $.ajax({
			url: "consultas/cancelar_boletos_sencillos.php",
			dataType:"JSON",
			data:{
				folio : folio,
				motivo : motivo
			}
			}).done(function (respuesta){
			if(respuesta.result == "success"){
				alertify.success("Cancelado");
				listarRegistros();
			}
			else{
				alertify.error(respuesta.result);
				
			}
			
			}).always(function(){
			boton.prop("disabled", false);
			icono.toggleClass("fa-times fa-spinner fa-spin");
			
		});
	}
}		

function guardarRegistro(event){
	event.preventDefault();
	let form = $(this);
	
	let boton = form.find(':submit');
	let icono = boton.find('.fa');
	
	
	boton.prop('disabled',true);
	icono.toggleClass('fa-save fa-spinner fa-spin');
	
	$.ajax({
		url: 'consultas/guardar_sencillos_precios.php',
		method: 'POST',
		dataType: 'JSON',
		data: $("#form_edicion").serialize()
		}).done(function(respuesta){
		if(respuesta.estatus == 'success'){
			alertify.success('Se ha guardado correctamente');
			$('#modal_edicion').modal('hide');
			listarRegistros();
		}
		else{
			alertify.error('Ocurrio un error');
		}
		}).always(function(){
		boton.prop('disabled',false);
		icono.toggleClass('fa-save fa-spinner fa-spin');
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
			tabla: "sencillos_precios",
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