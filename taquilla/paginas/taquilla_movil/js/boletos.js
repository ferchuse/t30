

var  $select_boletos = "";

$(document).ready(onLoad);

function onLoad(){
	
	listaBoletos();
	
	
	$.ajax({
		"url" : "control/precios_boletos_json.php",
		"dataType" : "JSON"
		
		
		}).done(function (respuesta){
		$select_boletos+= `<select class="tipo_boleto form-control" name="id_precio[]" required >`;
		$select_boletos+= `<option value="" >Elige...</option>`;
		
		$.each(respuesta.precios_boletos, function(index, item){
			$select_boletos+=`<option data-precio="${item.precio} " value="${item.id_precio}"> 
			${item.nombre_origenes}-${item.nombre_destinos} 
			${item.tipo_precio}-$ ${item.precio} 
			
			</option>`
		});
		
		$select_boletos+= `</select>`;
		
		console.log("select_boletos", $select_boletos)
		
		$("input[type=checkbox]").change( function( evt){
			console.log("change asiento", evt)
			if($(this).prop("checked")){
				$("#form_boletos :submit").prop("disabled", false)
				// $("#num_asiento").val(evt.target.id)
				agregarBoleto(evt.target.id);
				
				// $("#modal_boleto").modal("show")
			}
			else{
				quitarBoleto(evt.target.id);
			}
			
		});
	});
}

function listaBoletos(){
	console.log("listaBoletos");
	$.ajax({
		"url" : "control/lista_boletos.php",
		"data" :{"id_corridas": $("#id_corridas").val()}
		
		}).done(function (respuesta){
		$("#lista_boletos").html(respuesta);
	});
	
}

desactivaAsientosOcupados();


$("#nueva_venta").click( nueva_venta);

function nueva_venta(){
	$("#resumen_boletos").html("");
	$("#importe_total").val(0);
	$(":checked").prop("checked", false);
	// $("#form_boletos")[0].reset();
	
}
function desactivaAsientosOcupados(){
	console.log(" desactivaAsientosOcupados()")
	
	$.ajax({
		url: "control/asientos_ocupados.php" ,
		dataType: "JSON" ,
		data:{
			id_corridas : $("#id_corridas").val()
		}
		}).done(function (respuesta){
		
		$.each(respuesta.asientos_ocupados, function(index, num_asiento){
			$("#"+ num_asiento).prop("disabled", true);
		})
		}).always(function(){
		
		// boton.prop("disabled", false);
		// icono.toggleClass("fa-print fa-spinner fa-spin");
		
	});
}
$("#form_boletos").submit(guardarBoletos);


function guardarBoletos(event){
	event.preventDefault();
	let form = $(this);
	let boton = form.find(':submit');
	let icono = boton.find('.fa');
	let datos = form.serialize();
	
	datos+="&id_usuarios="+ $("#id_usuarios").val();
	
	boton.prop('disabled',true);
	icono.toggleClass('fa-save fa-spinner fa-pulse ');
	$.ajax({
		url: 'control/guardar_boletos.php',
		method: 'POST',
		dataType: 'JSON',
		data: datos
		}).done(function(respuesta){
		if(respuesta.result == 'success'){
			
			alertify.success('Se ha guardado correctamente');
			desactivaAsientosOcupados();
			$("#nueva_venta").click();
			
			listaBoletos();
			imprimirTicket(respuesta.boletos);
		}
		else{
			alertify.error(respuesta.mensaje);
		}
		}).always(function(){
		boton.prop('disabled',false);
		icono.toggleClass('fa-save fa-spinner fa-pulse ');
	});
}

function quitarBoleto(num_asiento){
	console.log("quitarBoleto", num_asiento);
	
	$("input[value='"+num_asiento+"']").closest("tr").remove();
	sumarImportes();
	
	if($("#resumen_boletos tr" ).length == 0){
		$("#form_boletos :submit").prop("disabled", true)
		
	}
}

function apartaBoletos(){
	console.log("apartaBoletos");
	
	$("input[type=checkbox]:checked").prop("disabled", true);
}


function agregarBoleto(num_asiento){
	
	console.log("num_asiento", num_asiento);
	console.log("select_boletos", $select_boletos);
	
	var boleto_html = 
	`<tr>
	<td class="w-10"><input class="form-control num_asiento" type="number" readonly name="num_asiento[]"  
	value='${num_asiento}'>
	</td>
	<td>
	${$select_boletos}
	</td>
	<td class="w-25"><input name="nombre_pasajero[]" required class="form-control nombre_pasajero" ></td>
	<td><input name="precio[]" class="precio form-control" readonly></td>
	<td>
	<button class="btn btn-danger quitar_boleto" type="button">
	<i class="fas fa-times"></i>
	</button>
	</td>
	
	</tr>`;
	$("#resumen_boletos").append(boleto_html);
	
	$(".quitar_boleto").click(function( evt){
		num_asiento = $(this).closest("tr").find(".num_asiento").val();
		$("#"+num_asiento).prop("checked", false);
		quitarBoleto(num_asiento);
	});
	$(".nombre_pasajero").keyup(function( evt){
		$(".nombre_pasajero").val($(this).val())
	});
	
	$(".tipo_boleto").change(function( evt){
		console.log("cambiar_tipo_boleto", evt)
		
		$(this).closest("tr").find(".precio").val($(this).find(":selected").data("precio"));
		
		sumarImportes();
	});
	
	sumarImportes();
}

function sumarImportes(){
	console.log("sumarImportes()")
	var importe_total = 0;
	$(".precio").each(function (index, item ){
		
		importe_total+= Number($(item).val());
	});
	
	$("#importe_total").val(importe_total)
	
}

function imprimirTicket(boletos){
	console.log("imprimirTicket()");
	var id_registro = $(this).data("id_registro");
	// var url = $(this).data("url");
	var boton = $(this); 
	var icono = boton.find("fas");
	
	boton.prop("disabled", true);
	icono.toggleClass("fa-print fa-spinner fa-spin");
	
	$.ajax({
		url: "impresion/imprimir_boletos.php" ,
		data:{
			boletos : boletos
		}
		}).done(function (respuesta){
		
		$("#ticket").html(respuesta); 
		window.print();
		}).always(function(){
		
		boton.prop("disabled", false);
		icono.toggleClass("fa-print fa-spinner fa-spin");
		
	});
}


