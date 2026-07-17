
var printService = new WebSocketPrinter();

$(document).ready(function(){
	
	$('#form_filtros').on('submit', listarRegistros);
	
	
	$('.cantidad').on('keyup', sumarArqueo);
	$('.cantidad').on('focus', function selectOnFocus(event) {$(this).select()});
	
	
	$("#btn_modal").on('click',function(){
		$("#form_arqueo")[0].reset();
		$(".modal-title").text("Nuevo Desglose de Dinero");
		$("#modal_arqueo").modal("show");
		
	});
	
	
	
	
	$('#form_arqueo').on('submit', guardarRegistro);
	
	$('#form_filtros').submit();
	
	
});



function sumarArqueo(){
	console.log("sumarArqueo()");
	
	let importe_total = 0;
	let $fila = $(this).closest("tr");
	let denominacion = Number($fila.find(".cantidad").data('denomi'));
	let cantidad = Number($fila.find(".cantidad").val());
	let importe = cantidad * denominacion;
	
	$fila.find('.importe').val(importe);
	
	
	$(".importe").each( function sumarImportes(index, item){
		importe_total += Number($(item).val());
	});
	
	let subtotal = importe_total.toFixed(2);
	
	
	$("#importe_total").val(subtotal);
}


function guardarRegistro(event){
	console.log("guardarRegistro")
	event.preventDefault();
	let form = $(this);
	let boton = form.find(':submit');
	let icono = boton.find('.fa');
	let datos = form.serializeArray();
	datos.push({"name": "id_usuarios", "value": $("#sesion_id_usuarios").val()})
	datos.push({"name": "id_administrador", "value": $("#sesion_id_administrador").val()})
	let importe_desglose = $('#importe_desglose').val();
	console.log("importe_desglose", importe_desglose);
	console.log("datos", datos);
	if(importe_desglose != ""){
		
		boton.prop('disabled',true);
		icono.toggleClass('fa-save fa-spinner fa-pulse ');
		
		$.ajax({
			url: 'control/fila_insert.php',
			method: 'POST',
			dataType: 'JSON',
			data:{
				tabla: 'desglose_dinero',
				valores: datos
			}
			}).done(function(respuesta){
			if(respuesta.estatus == 'success'){
				alertify.success('Se ha agregado correctamente');
				$('#modal_arqueo').modal('hide');
				$('#form_filtros').submit();
				
				imprimirDesglose(respuesta.nuevo_id);
			}
			else{
				alertify.error('Ocurrio un error');
			}
			}).always(function(){ 
			boton.prop('disabled',false);
			icono.toggleClass('fa-save fa-spinner fa-pulse');
		});
	}
	else{
		alertify.error("Ingrese alguna cantidad");
		
		
	}
}

function listarRegistros(ev){
	ev.preventDefault();
	console.log("listarRegistros()");
	
	$.ajax({
		url: 'control/lista_desglose_dinero.php',
		data: $("#form_filtros").serialize()
		}).done(function termina_listar(respuesta){
		
		$('#registros').html(respuesta);
		
		$('.imprimir').click(imprimirTicket);
		
		
	});
}


function imprimirDesglose(id_registro){
	
	
	$.ajax({
		url: "impresion/imprimir_desglose.php",
		data:{
			id_registro : id_registro
		}
		}).done(function (respuesta){
		printService.submit({
			'type': 'LABEL',
			'raw_content': respuesta
		});
		}).always(function(){
		
		
		
	});
}	
function imprimirTicket(event){
	var id_registro = $(this).data("id_registro");
	var boton = $(this);
	var icono = boton.find("fas");
	
	boton.prop("disabled", true);
	icono.toggleClass("fa-print fa-spinner fa-spin");
	
	$.ajax({
		url: "impresion/imprimir_desglose.php",
		data:{
			id_registro : id_registro
		}
		}).done(function (respuesta){
		printService.submit({
			'type': 'LABEL',
		'raw_content': respuesta
		});
		}).always(function(){
		
		boton.prop("disabled", false);
		icono.toggleClass("fa-print fa-spinner fa-spin");
		
		});
		}			