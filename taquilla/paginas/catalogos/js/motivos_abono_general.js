$(document).ready(function(){
	$('#body_modal').html(crearModal());
	
	//=====PROMESA DE LISTAR EMPRESAS========
	listarMotivosAbono();
	
	//========DAR LCIK EN BOTON DE NUEVO=============
	$('#nuevoMotivo').on('click',function(){
		$('#name_form')[0].reset();
		// $('.modal-title').text('Nueva Empresa');
		// $('#name_modal').modal('show');
		$('#name_modal').modal({ backdrop: 'static'}).modal('show').on('shown.bs.modal', function () {
			$('#name_form input:eq(1)').trigger("focus");
		});
	});
	
	//==========GUARDAR NUEVA EMPRESA============
	$('#name_form').on('submit',function(event){
		event.preventDefault();
		let form = $(this);
		let boton = form.find(':submit');
		let icono = boton.find('.fa');
		let datos = form.serializeArray();
		
		boton.prop('disabled',true);
		icono.toggleClass('fa-save fa-spinner fa-pulse fa-fw');
		
		$.ajax({
			url: '../../funciones/guardar.php',
			method: 'POST',
			dataType: 'JSON',
			data:{
				tabla: 'motivosAbonoUnidades',
				datos: datos
			}
			}).done(function(respuesta){
			if(respuesta.estatus == 'success'){
				alertify.success('Se ha agregado correctamente');
				$('#name_modal').modal('hide');
				listarMotivosAbono();
				}else{
				alertify.error('Ocurrio un error');
			}
			}).always(function(){
			boton.prop('disabled',false);
			icono.toggleClass('fa-save fa-spinner fa-pulse fa-fw');
		});
	})
	
	//=========BUSCAR EMPRESA=========
	$("#nombre_motivo").keyup(function filtro_buscar(){
		var indice = $(this).data("indice");
		var valor_filtro = $(this).val();
		var num_rows = buscar(valor_filtro,'tabla_motivos',indice);
		if(num_rows == 0){
			$('#mensaje').html("<div class='alert alert-dark text-center' role='alert'><strong>No se ha encontrado.</strong></div>");
			}else{
			$('#mensaje').html('');
		}
	});
	
	
});





function listarMotivosAbono(){
	return $.ajax({
		url: '../../funciones/listar.php',
		method: 'POST',
		dataType: 'JSON',
		data: {
			tabla: 'motivosAbonoUnidades'
		}
    }).done(function(respuesta){
		if(respuesta.estatus == 'success'){
			let lista = '';
			if(respuesta.num_rows > 0){
				$.each(respuesta.mensaje,function(index,element){
					lista += `
					<tr>
					<td class="text-center">${element.nombre_motivosAbono}</td>
					<td class="text-center">
					<button class="btn btn-outline-danger eliminar" data-id_motivosabono='${element.id_motivosAbono}'><i class="fas fa-trash-alt"></i></button>
					<button class="btn btn-outline-warning editar" data-id_motivosabono='${element.id_motivosAbono}'><i class="fas fa-pencil-alt"></i></button>
					</td>
					</tr>
					`;
				});
				}else{
				lista = `
				<tr>
				<td colspan="4"><h3 class="text-center">No hay empresas</h3></td>
				</tr>
				`;
			}
			$('#containerLista').html(lista);
			
			
			//=========ELIMINAR=========
			$('.eliminar').click(function(){
				let boton = $(this);
				let id_motivosAbono = boton.data('id_motivosabono');
				var fila = boton.closest('tr');
				
				alertify.confirm('Confirmacion', '¿Deseas eliminarlo?', eliminar , function(){
				});
				
				function eliminar(){
					$.ajax({
						url: 'control/eliminar.php',
						method: 'POST',
						dataType: 'JSON',
						data: {
							tabla: 'motivosAbonoUnidades',
							id_campo: 'id_motivosAbono',
							campo: id_motivosAbono
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
				var id_motivosabono = boton.data('id_motivosabono');
				boton.prop('disabled',true);
				icono.toggleClass('fas fa-pencil-alt fa fa-spinner fa-pulse fa-fw');
				
				$.ajax({
					url: '../../funciones/listar.php',
					method: 'POST',
					dataType: 'JSON',
					data: {
						tabla: 'motivosAbonoUnidades',
						id_campo: 'id_motivosAbono',
						campo: id_motivosabono
					}
					}).done(function(respuesta){
					if(respuesta.estatus == 'success'){
						$.each(respuesta.mensaje[0],function(index,element){
							$('#'+index).val(element);
						});
						$('.modal-title').text('Editar Empresa');
						$('#name_modal').modal('show');
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

function crearModal(){
	let modal = `
	<input type="text" hidden class="form-control" id="id_motivosAbono" name="id_motivosAbono">
	<div class="form-group">
	<label for="nombre_motivosAbono">NOMBRE</label>
	<input type="text" class="form-control" id="nombre_motivosAbono" name="nombre_motivosAbono" placeholder="Motivo de Abono General de Unidades" required>
	</div>
	`;
	return modal;
}
