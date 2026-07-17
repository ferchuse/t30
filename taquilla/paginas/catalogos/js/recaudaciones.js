$(document).ready(function(){
	
	//=====PROMESA DE LISTAR CONDUCTOR========
	listarRegistros();
	
	//========DAR LCIK EN BOTON DE NUEVO=============
	$('.nuevo').on('click',function(){
		$('#form_edicion')[0].reset();
		$('.modal-title').text('Nueva Recaudación');
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
				tabla: 'recaudaciones',
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
	
	//=========BUSCAR EMPRESA=========
	$("#numero_conductor").keyup(function filtro_buscar(){
		var indice = $(this).data("indice");
		var valor_filtro = $(this).val();
		var num_rows = buscar(valor_filtro,'tabla_recaudaciones',indice);
		if(num_rows == 0){
			$('#mensaje').html("<div class='alert alert-dark text-center' role='alert'><strong>No se ha encontrado.</strong></div>");
			}else{
			$('#mensaje').html('');
		}
	});
	//=========BUSCAR EMPRESA=========
	$("#nombre_conductor").keyup(function filtro_buscar(){
		var indice = $(this).data("indice");
		var valor_filtro = $(this).val();
		var num_rows = buscar(valor_filtro,'tabla_recaudaciones',indice);
		if(num_rows == 0){
			$('#mensaje').html("<div class='alert alert-dark text-center' role='alert'><strong>No se ha encontrado.</strong></div>");
			}else{
			$('#mensaje').html('');
		}
	});
	
	
	
	
});





function listarRegistros(){
	
	return $.ajax({
		url: '../../funciones/listar.php',
		method: 'POST',
		dataType: 'JSON',
		data: {
			tabla: 'recaudaciones',
			
		}
    }).done(function(respuesta){
		if(respuesta.estatus == 'success'){
			let recaudaciones = '';
			if(respuesta.num_rows > 0){
				$.each(respuesta.mensaje,function(index,element){
					recaudaciones += `
					<tr>
					
					<td class="text-center">${element.nombre_recaudaciones}</td>
					
					<td class="text-center">
					<button class="btn btn-outline-danger eliminar" data-id_recaudaciones='${element.id_recaudaciones}'><i class="fas fa-trash-alt"></i></button>
					<button class="btn btn-outline-warning editar" data-id_recaudaciones='${element.id_recaudaciones}'><i class="fas fa-pencil-alt"></i></button>
					</td>
					</tr>
					`;
				});
				}else{
				recaudaciones = `
				<tr>
				<td colspan="8"><h3 class="text-center">No hay recaudaciones</h3></td>
				</tr>
				`;
			}
			$('#containerLista').html(recaudaciones);
			
			
			//=========ELIMINAR=========
			$('.eliminar').click(function(){
				let boton = $(this);
				let id_recaudaciones = boton.data('id_recaudaciones');
				var fila = boton.closest('tr');
				
				alertify.confirm('Confirmacion', '¿Deseas eliminarlo?', eliminar , function(){
				});
				
				function eliminar(){
					$.ajax({
						url: '../../funciones/eliminar.php',
						method: 'POST',
						dataType: 'JSON',
						data: {
							tabla: 'recaudaciones',
							id_campo: 'id_recaudaciones',
							campo: id_recaudaciones
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
				var id_recaudaciones = boton.data('id_recaudaciones');
				boton.prop('disabled',true);
				icono.toggleClass('fas fa-pencil-alt fa fa-spinner fa-pulse fa-fw');
				
				$.ajax({
					url: '../../funciones/listar.php',
					method: 'POST',
					dataType: 'JSON',
					data: {
						tabla: 'recaudaciones',
						id_campo: 'id_recaudaciones',
						campo: id_recaudaciones
					}
					}).done(function(respuesta){
					if(respuesta.estatus == 'success'){
						$.each(respuesta.mensaje[0],function(index,element){
							$('#'+index).val(element);
						});
						$('.modal-title').text('Editar Conductor');
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
			console.log("Error al cargar la tabla");
		}
	});
}

