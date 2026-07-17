$(document).ready(function(){
    $('#body_modal').html(crearModal());
    
    //=====PROMESA DE LISTAR EMPRESAS========
    listarSalidas();

    //========DAR LCIK EN BOTON DE NUEVO=============
    $('#nuevaSalida').on('click',function(){
        $('#name_form')[0].reset();
        $('.modal-title').text('Nueva Salida');
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
            url: '../../funciones/guardar.php',
            method: 'POST',
            dataType: 'JSON',
            data:{
                tabla: 'motivos_salida',
                datos: datos
            }
        }).done(function(respuesta){
            if(respuesta.estatus == 'success'){
                alertify.success('Se ha agregado correctamente');
                $('#name_modal').modal('hide');
                listarSalidas();
            }else{
                alertify.error('Ocurrio un error');
            }
        }).always(function(){
            boton.prop('disabled',false);
            icono.toggleClass('fa-save fa-spinner fa-pulse fa-fw');
        });
    })

    //=========BUSCAR EMPRESA=========
    $("#buscar_motivo").keyup(function filtro_buscar(){
        var indice = $(this).data("indice");
        var valor_filtro = $(this).val();
        var num_rows = buscar(valor_filtro,'tabla_salidas',indice);
        if(num_rows == 0){
          $('#mensaje').html("<div class='alert alert-dark text-center' role='alert'><strong>No se ha encontrado.</strong></div>");
        }else{
          $('#mensaje').html('');
        }
    });


});





function listarSalidas(){
    return $.ajax({
        url: '../../funciones/listar.php',
        method: 'POST',
        dataType: 'JSON',
        data: {
            tabla: 'motivos_salida'
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
                            <td class="text-center">${element.nombre_motivosSalida}</td>
                            <td class="text-center" ${permitido}>
                                <button class="btn btn-outline-danger eliminar" data-id_motivossalida='${element.id_motivosSalida}'><i class="fas fa-trash-alt"></i></button>
                                <button class="btn btn-outline-warning editar" data-id_motivossalida='${element.id_motivosSalida}'><i class="fas fa-pencil-alt"></i></button>
                            </td>
                        </tr>
                    `;
                });
            }else{
                lista = `
                <tr>
                    <td colspan="2"><h3 class="text-center">No hay Motivos</h3></td>
                </tr>
                `;
            }
            $('#containerLista').html(lista);


            //=========ELIMINAR=========
        $('.eliminar').click(function(){
            let boton = $(this);
            let id_motivosSalida = boton.data('id_motivossalida');
            var fila = boton.closest('tr');
            
            alertify.confirm('Confirmacion', '¿Deseas eliminarlo?', eliminar , function(){
            });

            function eliminar(){
                $.ajax({
                    url: '../../funciones/eliminar.php',
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                        tabla: 'motivos_salida',
                        id_campo: 'id_motivossalida',
                        campo: id_motivosSalida
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
            var id_motivosSalida = boton.data('id_motivossalida');
            boton.prop('disabled',true);
            icono.toggleClass('fas fa-pencil-alt fa fa-spinner fa-pulse fa-fw');

            $.ajax({
                url: '../../funciones/listar.php',
                method: 'POST',
                dataType: 'JSON',
                data: {
                    tabla: 'motivos_salida',
                    id_campo: 'id_motivosSalida',
                    campo: id_motivosSalida
                }
            }).done(function(respuesta){
                if(respuesta.estatus == 'success'){
                    $.each(respuesta.mensaje[0],function(index,element){
                        $('#'+index).val(element);
                    });
                    $('.modal-title').text('Editar Salida');
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
        <input type="text" hidden class="form-control" id="id_motivosSalida" name="id_motivosSalida">
        <div class="form-group">
            <label for="nombre_motivosSalida">NOMBRE</label>
            <input type="text" class="form-control" id="nombre_motivosSalida" name="nombre_motivosSalida" placeholder="Motivo de la salida" required>
        </div>
    `;
    return modal; 
}
