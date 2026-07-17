$(document).ready(function(){
	$('#body_modal').html(crearModal());
	
	//=====PROMESA DE LISTAR EMPRESAS========
	listarBeneficiarios();
	
	//========DAR LCIK EN BOTON DE NUEVO=============
	$('#nuevoBeneficiario').on('click',function(){
		$('#name_form')[0].reset();
		$('.modal-title').text('Nueva Beneficiario');
		$('#name_modal').modal('show');
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
			url: 'control/guardar.php',
			method: 'POST',
			dataType: 'JSON',
			data:{
				tabla: 'beneficiarios',
				datos: datos
			}
			}).done(function(respuesta){
			if(respuesta.estatus == 'success'){
				alertify.success('Se ha agregado correctamente');
				$('#name_modal').modal('hide');
				listarBeneficiarios();
				}else{
				alertify.error('Ocurrio un error');
			}
			}).always(function(){
			boton.prop('disabled',false);
			icono.toggleClass('fa-save fa-spinner fa-pulse fa-fw');
		});
	})
	
	//=========BUSCAR EMPRESA=========
	$("#nombre_beneficiario").keyup(function filtro_buscar(){
		var indice = $(this).data("indice");
		var valor_filtro = $(this).val();
		var num_rows = buscar(valor_filtro,'tabla_beneficiarios',indice);
		if(num_rows == 0){
			$('#mensaje').html("<div class='alert alert-dark text-center' role='alert'><strong>No se ha encontrado.</strong></div>");
			}else{
			$('#mensaje').html('');
		}
	});
	
	
});





function listarBeneficiarios(){
	return $.ajax({
			url: '../../funciones/listar.php',
		method: 'POST',
		dataType: 'JSON',
		data: {
			tabla: 'beneficiarios'
		}
    }).done(function(respuesta){
		if(respuesta.estatus == 'success'){
			let lista = '';
			
			if($("#permiso").val() == "Supervisor"){
				permitido = ""; 
				}
			else{
				permitido = " hidden "; 
			}
			
			
			if(respuesta.num_rows > 0){
				$.each(respuesta.mensaje,function(index,element){
					lista += `
					<tr>
					<td class="text-center">${element.nombre_beneficiarios}</td>
					<td class="text-center">
					<button ${permitido} class="btn btn-outline-danger eliminar" data-id_beneficiarios='${element.id_beneficiarios}'><i class="fas fa-trash-alt"></i></button>
					<button ${permitido} class="btn btn-outline-warning editar" data-id_beneficiarios='${element.id_beneficiarios}'><i class="fas fa-pencil-alt"></i></button>
					</td>
					</tr>
					`;
				});
				}else{
				lista = `
				<tr>
				<td colspan="4"><h3 class="text-center">No hay Beneficiarios</h3></td>
				</tr>
				`;
			}
			$('#containerLista').html(lista);
			
			
			//=========ELIMINAR=========
			$('.eliminar').click(function(){
				let boton = $(this);
				let id_beneficiarios = boton.data('id_beneficiarios');
				var fila = boton.closest('tr');
				
				alertify.confirm('Confirmacion', '¿Deseas eliminarlo?', eliminar , function(){
				});
				
				function eliminar(){
					$.ajax({
						url: 'control/eliminar.php',
						method: 'POST',
						dataType: 'JSON',
						data: {
							tabla: 'beneficiarios',
							id_campo: 'id_beneficiarios',
							campo: id_beneficiarios
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
				var id_beneficiarios = boton.data('id_beneficiarios');
				boton.prop('disabled',true);
				icono.toggleClass('fas fa-pencil-alt fa fa-spinner fa-pulse fa-fw');
				
				$.ajax({
					url: 'control/listar.php',
					method: 'POST',
					dataType: 'JSON',
					data: {
						tabla: 'beneficiarios',
						id_campo: 'id_beneficiarios',
						campo: id_beneficiarios
					}
					}).done(function(respuesta){
					if(respuesta.estatus == 'success'){
						$.each(respuesta.mensaje[0],function(index,element){
							$('#'+index).val(element);
						});
						$('.modal-title').text('Editar Beneficiario');
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
	<input type="text" hidden class="form-control" id="id_beneficiarios" name="id_beneficiarios">
	<div class="form-group">
	<label for="nombre_beneficiarios">NOMBRE</label>
	<input type="text" class="form-control" id="nombre_beneficiarios" name="nombre_beneficiarios" placeholder="Nombre del beneficiario" required>
	</div>
	`;
	return modal; 
}
