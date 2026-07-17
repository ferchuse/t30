$(document).ready(function(){
	listarRegistros();
	//=========BOTON DEL MODAL=========================
	$("#btn_modal").click(function(){
		$("#form_edicion")[0].reset();
		$("#id_roles").val("");
		$("#numero_roles").val("");
		$("#nombre_roles").val("");
		
		$(".modal-title").text("Nuevo ");
		$("#modal_edicion").modal("show");
		$(".borrar_rol").closest("tr").each(function resetRutas(i, item){
			if(i > 0){
				
				item.remove();		
			}
			
		});
		
		
		$("select").val("");
		// $("select[name=id_destino]")[0].val("");
	});
	
	$("#agregar_rol").click(agregarRol)
	$(".borrar_rol").click(borrarRol)
	
	
	
	
	//==========GUARDAR ============ 
	$('#form_edicion').on('submit',function(event){
		event.preventDefault();
		let form = $(this);
		let boton = form.find(':submit');
		let icono = boton.find('.fa'); 
		let datos = form.serialize();
		
		boton.prop('disabled',true);
		icono.toggleClass('fa-save fa-spinner fa-pulse ');
		
		$.ajax({		
			url: 'control/guardar_roles.php',
			method: 'POST',
			dataType: 'JSON',
			data:datos
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
			icono.toggleClass('fa-save fa-spinner fa-pulse fa-fw');
			$("#form_edicion")[0].reset();
		});
	})
	
});

function listarRegistros(){
	return $.ajax({
		url: '../../funciones/listar.php',
		method: 'POST',
		dataType: 'JSON',
		data: {
			tabla: 'roles'
		}
    }).done(function(respuesta){
		if(respuesta.estatus == 'success'){
			let roles = '';
			if(respuesta.num_rows > 0){
				$.each(respuesta.mensaje,function(index,element){
					roles += `
					<tr>
					<td class="text-center">${element.id_roles}</td>					
					<td class="text-center">${element.numero_roles}</td>
					<td class="text-center">${element.nombre_roles}</td>
					<td class="text-center">
					<button class="btn btn-outline-danger eliminar" data-id_roles='${element.id_roles}'><i class="fas fa-trash-alt"></i></button>
					<button class="btn btn-outline-warning editar" data-id_roles='${element.id_roles}'><i class="fas fa-pencil-alt"></i></button>
					</td>
					</tr>
					`;
				});
				}else{
				derroteros += `
				<tr>
				<td class="text-center"><h3>No hay Derroteros</h3></td>
				</tr>
				`;
			}
			$('#tabla_DB').html(roles);    
			
			// $('#tableDerroteros').DataTable({
			// "language": {
			// "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
			// }
			// });
			
			//=========ELIMINAR=========
			$('.eliminar').click(function(){
				let boton = $(this);
				let id_roles = boton.data('id_roles');
				var fila = boton.closest('tr');
				
				alertify.confirm('Confirmacion', '¿Deseas eliminarlo?', eliminar , function(){
				});
				
				function eliminar(){
					$.ajax({
						url: '../../funciones/eliminar.php',
						method: 'POST',
						dataType: 'JSON',
						data: {
							tabla: 'roles',
							id_campo: 'id_roles',
							campo: id_roles
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
				var id_roles = boton.data('id_roles');
				boton.prop('disabled',true);
				icono.toggleClass('fas fa-pencil-alt fa fa-spinner fa-pulse fa-fw');
				
				$.ajax({
					url: 'control/cargar_roles.php',
					method: 'GET',
					data: {
						id_roles: id_roles
					}
					}).done(function(respuesta){
					
					$('.modal-title').text('Editar roles');
					$('#modal_edicion').modal('show');
					$('#modal_edicion .modal-body').html(respuesta);
					$("#agregar_rol").click(agregarRol)
					$(".borrar_rol").click(borrarRol)
					
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

function agregarRol(event){
	console.log("agregarRol")
	$("#tabla_rol tbody tr:first").clone(true).appendTo("#tabla_rol tbody")
	
}

function borrarRol(event){
	console.log("borrarRol")
	if($("#tabla_rol tbody tr").length >1){
		$(this).closest("tr").remove();
	}
	
	
}