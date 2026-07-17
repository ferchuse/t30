$(document).ready(function(){
	listarRegistros();
	
	
	$("#btn_modal").click(function(){
		$("#form_edicion")[0].reset();
		$(".modal-title").text("Nueva Base");
		$("#modal_edicion").modal("show");
	});
	
	

	$('#form_edicion').on('submit',function(event){
		event.preventDefault();
		let form = $(this);
		let boton = form.find(':submit');
		let icono = boton.find('.fa');
		let datos = form.serializeArray();
		
		boton.prop('disabled',true);
		icono.toggleClass('fa-save fa-spinner fa-pulse ');
		
		$.ajax({
			url: '../../funciones/guardar.php',
			method: 'POST',
			dataType: 'JSON',
			data:{
				tabla: 'bases',
				datos: datos
			}
			}).done(function(respuesta){
			if(respuesta.estatus == 'success'){
				alertify.success('Se ha agregado correctamente');
				$('#modal_edicion').modal('hide');
				listarRegistros();
				}else{
				alertify.error('Ocurrio un error');
			}
			}).always(function(){
			boton.prop('disabled',false);
			icono.toggleClass('fa-save fa-spinner fa-pulse fa-fw');
		});
	})
	
});

function listarRegistros(){
	return $.ajax({
		url: '../../funciones/listar.php',
		method: 'POST',
		dataType: 'JSON',
		data: {
			tabla: 'bases'
		}
    }).done(function(respuesta){
		if(respuesta.estatus == 'success'){
			let registros = '';
			
			
			if($("#permiso").val() == "Supervisor"){
				permitido = ""; 
			}
			else{
				permitido = " hidden "; 
			}
			
			if(respuesta.num_rows > 0){
				$.each(respuesta.mensaje,function(index,element){
					registros += `
					<tr>
					
					<td class="text-center">${element.base}</td>
				
					<td class="text-center"> 
					<button ${permitido} class="btn btn-outline-danger eliminar" data-id_registro='${element.id_base}'><i class="fas fa-trash-alt"></i></button>
					<button ${permitido} class="btn btn-outline-warning editar" data-id_registro='${element.id_base}'><i class="fas fa-pencil-alt"></i></button>
					</td>
					</tr>
					`;
				});
				}else{
				registros += `
				<tr>
				<td class="text-center"><h3>No hay Registros</h3></td>
				</tr>
				`;
			}
			$('#lista_registros').html(registros);    
			
			
			$('.eliminar').click(function(){
				let boton = $(this);
				let id_registro = boton.data('id_registro');
				var fila = boton.closest('tr');
				
				alertify.confirm('Confirmacion', '¿Deseas eliminarlo?', eliminar , function(){
				});
				
				function eliminar(){
					$.ajax({
						url: 'control/eliminar.php',
						method: 'POST',
						dataType: 'JSON',
						data: {
							tabla: 'bases',
							id_campo: 'id_base',
							campo: id_registro
						}
						}).done(function(respuesta){
						if(respuesta.estatus == 'success'){
							alertify.success('Se ha eliminado correctamente');
							fila.fadeOut(1000);
							}else{
							alertify.error('Ocurrio un error');
						}
					});
				}
				
			});
			
			/*=======LISTAR EMPRESAS=========*/
			$('.editar').click(function(){
				var boton = $(this);
				var icono = boton.find('.fas');
				var id_registro = boton.data('id_registro');
				boton.prop('disabled',true);
				icono.toggleClass('fas fa-pencil-alt  fa-spinner fa-pulse');
				
				$.ajax({
					url: '../../funciones/listar.php',
					method: 'POST',
					dataType: 'JSON',
					data: {
						tabla: 'bases',
						id_campo: 'id_base',
						campo: id_registro
					}
					}).done(function(respuesta){
					if(respuesta.estatus == 'success'){
						$.each(respuesta.mensaje[0],function(index,element){
							$('#'+index).val(element);
						});
						$('.modal-title').text('Editar Derrotero');
						$('#modal_edicion').modal('show');
						}else{
						//console.log(respuesta.mensaje);
					}
					}).always(function(){
					boton.prop('disabled',false);
					icono.toggleClass('fas fa-pencil-alt fa-spinner fa-pulse');
				});
			});
			
			
			
			
			
			}else{
			console.log("Error al cargar la tabla");
		}
	});
	
}