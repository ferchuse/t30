$(document).ready(function () {
	
	$('#form_filtros').on('submit', function (event) {
		event.preventDefault()
		listarRegistros();
		
	})
	
	
    listarRegistros();
	
    //========DAR LCIK EN BOTON DE NUEVO=============
    $('.nuevo').on('click', function () {
        $('#form_edicion')[0].reset();
        $('.modal-title').text('Nuevo Origen');
        $('#modal_edicion').modal('show');
	});
	
    //==========GUARDAR NUEVA EMPRESA============
    $('#form_edicion').on('submit', function (event) {
        event.preventDefault();
        let form = $(this);
        let boton = form.find(':submit');
        let icono = boton.find('.fa');
        let datos = form.serializeArray();
		
        boton.prop('disabled', true);
        icono.toggleClass('fa-save fa-spinner fa-pulse ');
		
        $.ajax({
            url: '../../funciones/guardar.php',
            method: 'POST',
            dataType: 'JSON',
            data: {
                tabla: 'destinos',
                datos: datos
			}
			}).done(function (respuesta) {
            if (respuesta.estatus == 'success') {
                alertify.success('Se ha guardado correctamente');
                $('#modal_edicion').modal('hide');
                listarRegistros();
				} else {
                alertify.error('Ocurrio un error');
			}
			}).always(function () {
            boton.prop('disabled', false);
            icono.toggleClass('fa-save fa-spinner fa-pulse ');
		});
	})
	
    //=========BUSCAR EMPRESA=========
    $("#id_origenes").keyup(function filtro_buscar() {
        var indice = $(this).data("indice");
        var valor_filtro = $(this).val();
        var num_rows = buscar(valor_filtro, 'tabla_origenes', indice);
        if (num_rows == 0) {
            $('#mensaje').html("<div class='alert alert-dark text-center' role='alert'><strong>No se ha encontrado.</strong></div>");
			} else {
            $('#mensaje').html('');
		}
	});
    //=========BUSCAR EMPRESA=========
    $("#nombre_origenes").keyup(function filtro_buscar() {
        var indice = $(this).data("indice");
        var valor_filtro = $(this).val();
        var num_rows = buscar(valor_filtro, 'tabla_origenes', indice);
        if (num_rows == 0) {
            $('#mensaje').html("<div class='alert alert-dark text-center' role='alert'><strong>No se ha encontrado.</strong></div>");
			} else {
            $('#mensaje').html('');
		}
	});
	
	
});


function listarRegistros() {
	
	boton = $("#form_filtros").find(":submit");
	icono = boton.find("i");
	
	boton.prop("disabled", true)
	icono.toggleClass("fa-search fa-spinner fa-spin");
	
	
    return $.ajax({
        url: 'consultas/listar_destinos.php',
        method: 'GET',
        data: $("#form_filtros").serialize()
		
		}).done(function (respuesta) {
		
		
		$('#lista_registros').html(respuesta);
		
		
		//=========ELIMINAR=========
		$('.btn_borrar').click(function () {
			let boton = $(this);
			let id = boton.data('id');
			var fila = boton.closest('tr');
			
			alertify.confirm('Confirmacion', '¿Deseas eliminarlo?', eliminar, function () {
			});
			
			function eliminar() {
				$.ajax({
					url: '../../funciones/eliminar.php',
					method: 'POST',
					dataType: 'JSON',
					data: {
						tabla: 'destinos',
						id_campo: 'id_precio',
						campo: id
					}
                    }).done(function (respuesta) {
					if (respuesta.estatus == 'success') {
						alertify.success('Se ha eliminado correctamente');
						fila.fadeOut(1000);
                        } else {
						alertify.error('Ocurrio un error');
					}
				});
			}
			
		});
		
		/*=======LISTAR EMPRESAS=========*/
		$('.btn_editar').click(function () {
			var boton = $(this);
			var icono = boton.find('.fas');
			var id = boton.data('id');
			boton.prop('disabled', true);
			icono.toggleClass('fa-edit fa-spinner fa-spin');
			
			$.ajax({
				url: '../../funciones/listar.php',
				method: 'POST',
				dataType: 'JSON',
				data: {
					tabla: 'destinos',
					id_campo: id,
					campo: 'id_precio'
				}
				}).done(function (respuesta) {
				if (respuesta.estatus == 'success') {
					$.each(respuesta.mensaje[0], function (index, element) {
						$('#' + index).val(element);
					});
					$('.modal-title').text('Editar Destino');
					$('#modal_edicion').modal('show');
					} else {
					//console.log(respuesta.mensaje);
				}
				}).always(function () {
				boton.prop('disabled', false);
				icono.toggleClass('fa-edit fa-spinner fa-spin');
			});
		});
		
		
		
		}).always(function (){
		boton.prop("disabled", false)
		icono.toggleClass("fa-search fa-spinner fa-spin");
		
	});
}

