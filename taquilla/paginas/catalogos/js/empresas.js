$(document).ready(function(){
	
	//=====PROMESA DE LISTAR EMPRESAS========
	listarEmpresas();
	
	//========DAR LCIK EN BOTON DE NUEVO=============
	$('#nuevaEmpresa').on('click',function(){
		$('#form_empresas')[0].reset();
		$('.modal-title').text('Nueva Empresa');
		// $('#modal_empresas').modal('show');
		$('#modal_empresas').modal({ backdrop: 'static'}).modal('show').on('shown.bs.modal', function () {
			$('#form_empresas input:eq(1)').trigger("focus");
		});
	});
	
	//==========GUARDAR NUEVA EMPRESA============
	$('#form_empresas').on('submit',function(event){
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
				tabla: 'empresas',
				datos: datos
			}
			}).done(function(respuesta){
			if(respuesta.estatus == 'success'){
				alertify.success('Se ha agregado correctamente');
				$('#modal_empresas').modal('hide');
				listarEmpresas();
				}else{
				alertify.error('Ocurrio un error');
			}
			}).always(function(){
			boton.prop('disabled',false);
			icono.toggleClass('fa-save fa-spinner fa-pulse fa-fw');
		});
	})
	
	//=========BUSCAR EMPRESA=========
	$("#buscar_empresa").keyup(function filtro_buscar(){
		var indice = $(this).data("indice");
		var valor_filtro = $(this).val();
		var num_rows = buscar(valor_filtro,'tabla_empresas',indice);
		if(num_rows == 0){
			$('#mensaje').html("<div class='alert alert-dark text-center' role='alert'><strong>No se ha encontrado.</strong></div>");
			}else{
			$('#mensaje').html('');
		}
	});
	
	
});





function listarEmpresas(){
	return $.ajax({
		url: '../../funciones/listar.php',
		method: 'POST',
		dataType: 'JSON',
		data: {
			tabla: 'empresas'
		}
		}).done(function(respuesta){
		if(respuesta.estatus == 'success'){
			let empresas = '';
			
			if($("#permiso").val() == "Supervisor"){
				permitido = ""; 
			}
			else{
				permitido = " hidden "; 
			}
			
			if(respuesta.num_rows > 0){
				$.each(respuesta.mensaje,function(index,element){
					empresas += `
					<tr>
					<td class="text-center">${element.id_empresas}</td>
					<td class="text-center">${element.nombre_empresas}</td>
					<td class="text-center">${element.usuario_empresas}</td>
					<td class="text-center">
					<button  ${permitido} class="btn btn-outline-danger eliminar" data-id_empresas='${element.id_empresas}'><i class="fas fa-trash-alt"></i></button>
					<button  ${permitido} class="btn btn-outline-warning editar" data-id_empresas='${element.id_empresas}'><i class="fas fa-pencil-alt"></i></button>
					</td>
					</tr>
					`;
				});
				}else{
				empresas = `
				<tr>
				<td colspan="4"><h3 class="text-center">No hay empresas</h3></td>
				</tr>
				`;
			}
			$('#containerLista').html(empresas);
			
			
			//=========ELIMINAR=========
			$('.eliminar').click(function(){
				let boton = $(this);
				let id_empresas = boton.data('id_empresas');
				var fila = boton.closest('tr');
				
				alertify.confirm('Confirmacion', '¿Deseas eliminarlo?', eliminar , function(){
				});
				
				function eliminar(){
					$.ajax({
						url: 'control/eliminar.php',
						method: 'POST',
						dataType: 'JSON',
						data: {
							tabla: 'empresas',
							id_campo: 'id_empresas',
							campo: id_empresas
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
				var id_empresas = boton.data('id_empresas');
				boton.prop('disabled',true);
				icono.toggleClass('fas fa-pencil-alt fa fa-spinner fa-pulse fa-fw');
				
				$.ajax({
					url: '../../funciones/listar.php',
					method: 'POST',
					dataType: 'JSON',
					data: {
						tabla: 'empresas',
						id_campo: 'id_empresas',
						campo: id_empresas
					}
					}).done(function(respuesta){
					if(respuesta.estatus == 'success'){
						$.each(respuesta.mensaje[0],function(index,element){
							$('#'+index).val(element);
						});
						$('.modal-title').text('Editar Empresa');
						$('#modal_empresas').modal('show');
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

