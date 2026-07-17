//ultima modificaion Fernando 08:39 p. m. 24/11/2018


$(document).ready(function(){
	
	//=====PROMESA DE LISTAR CONDUCTOR========
	listarRegistros();
	
	//========DAR LCIK EN BOTON DE NUEVO=============
	$('.nuevo').on('click',function(){
		$('#form_edicion')[0].reset();
		$('.modal-title').text('Nuevo Motivo');
		// $('#modal_edicion').modal('show');
		$('#modal_edicion').modal({ backdrop: 'static'}).modal('show').on('shown.bs.modal', function () {
			$('#form_edicion input:eq(1)').trigger("focus");
		});
	});
	
	//==========GUARDAR NUEVA EMPRESA============
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
				tabla: 'motivos_condonacion',
				datos: datos
			}
			}).done(function(respuesta){
			if(respuesta.estatus == 'success'){
				alertify.success('Guardado correctamente');
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
	// let subconsulta = 'LEFT JOIN empresas USING(id_empresas)';
	
	return $.ajax({
		url: '../../funciones/listar.php',
		method: 'POST',
		dataType: 'JSON',
		data: {
			tabla: 'motivos_condonacion'
		}
    }).done(function(respuesta){
		if(respuesta.estatus == 'success'){
			let condonaciones = '';
			if(respuesta.num_rows > 0){
				$.each(respuesta.mensaje,function(index,element){
					condonaciones += `
					<tr>
					<td class="text-center">${element.motivo_condonacion}</td>
					<td class="text-center">
					<button class="btn btn-outline-danger eliminar" data-id_registro='${element.id_motivo_condonacion}'><i class="fas fa-trash-alt"></i></button>
					<button class="btn btn-outline-warning editar" data-id_registro='${element.id_motivo_condonacion}'><i class="fas fa-pencil-alt"></i></button>
					</td>
					</tr>
					`;
				});
				}else{
				condonaciones = `
				<tr>
				<td colspan="8"><h3 class="text-center">No hay condonaciones</h3></td>
				</tr>
				`;
			}
			$('#containerLista').html(condonaciones);
			
			
			//=========ELIMINAR=========
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
							tabla: 'motivos_condonacion',
							id_campo: 'id_motivo_condonacion',
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
				icono.toggleClass('fas fa-pencil-alt fa fa-spinner fa-pulse fa-fw');
				
				$.ajax({
					url: '../../funciones/listar.php',
					method: 'POST',
					dataType: 'JSON',
					data: {
						tabla: 'motivos_condonacion',
						id_campo: 'id_motivo_condonacion',
						campo: id_registro
					}
					}).done(function(respuesta){
					if(respuesta.estatus == 'success'){
						$.each(respuesta.mensaje[0],function(index,element){
							$('#'+index).val(element);
						});
						$('.modal-title').text('Editar Motivo');
						$('#modal_edicion').modal('show');
						}else{
						//console.log(respuesta.mensaje);
					}
					}).always(function(){
					boton.prop('disabled',false);
					icono.toggleClass('fas fa-pencil-alt fa fa-spinner fa-pulse fa-fw');
				});
			});
			
			
			}else{
			//console.log(respuesta.mensaje);
		}
	});
}

