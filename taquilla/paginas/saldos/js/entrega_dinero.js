$(document).ready(function(){
    //=================CARGAR LA TABLA AL INICIO===================
    listar();
    //===============OPEN MODAL===========
    $("#btn_modal").on('click',function(){
        $("#form_modal")[0].reset();
        $(".modal-title").text("Nueva Entrega de Dinero a Empresa");
        $("#modal_modal").modal("show");

        let id_usua = $("#id_usuarios").val();
        $("#id_u").val(id_usua);
        console.log(id_usua);
        cadena_numeros();
    });
    //========= MANDAR LOS LAS FECHAS A LA TABLA ===============
    $('#btn_fechas').on('click',function(){
        //let fechas = $(this);
        let fecha_inicio = $('#fecha_inicio').val();
        let fecha_final = $('#fecha_final').val();
        console.log(fecha_inicio+' '+fecha_final);
        //let reporte = $('#tabla_DB');
        lista_fechas(fecha_inicio,fecha_final);
    });
    //=====Exportar a excel=================
    $("#btn_excel").on('click',function(){
        $('#tableData').tableExport({
            type:'excel',
            tableName:'Desglose de Dinero', 
            ignoreColumn: [5],
            escape:'false'
        });
    });
    //=====Imprimir tabla=================
    $("#btn_imprimir").on('click',function(){
        let formato = '';
        formato += `
            <h1 class="text-center">GAAZ</h1>
            <h2 class="text-center">Desglose de Dinero</h2>
        `;
        $('#formato_imprimir').html(formato);
        window.print();
    });
    //==========BOTON CUBRIR Y DESCUBRIR LOS PRECIOS DE DOCUMENTO DE BOLESTOS=======================
    $('#btn_boletos').on('click',function(){
        $('#datos_boleto').toggle();//Alternar entre ocultar y mostrar para todos los elementos
    });
    $('#btn_taquilla').on('click',function(){
        $('#imp_cadena').toggle();//Alternar entre ocultar y mostrar para todos los elementos
    });
    //==========GUARDAR============
    $('#form_modal').on('submit',function(event){
        event.preventDefault();
        let form = $(this);
        let boton = form.find(':submit');
        let icono = boton.find('.fa');
        let datos = form.serializeArray();
        let importe = $('#importe_entregadinero').val();
        boton.prop('disabled',true);
        icono.toggleClass('fa-save fa-spinner fa-pulse ');
        console.log(importe);
        if(importe != ""){
            $.ajax({
                url: '../catalogos/control/guardar.php',
                method: 'POST',
                dataType: 'JSON',
                data:{
                    tabla: 'entrega_dinero_empresas',
                    datos: datos
                }
            }).done(function(respuesta){
                if(respuesta.estatus == 'success'){
                    alertify.success('Se ha agregado correctamente');
                    $('#modal_modal').modal('hide');
                    listar();
                }else{
                    alertify.error('Ocurrio un error');
                }
            }).always(function(){
                boton.prop('disabled',false);
                icono.toggleClass('fa-save fa-spinner fa-pulse fa-fw');
            });
        }else{
            alertify.error("Ingrese alguna cantidad");
            boton.prop('disabled',false);
            icono.toggleClass('fa-save fa-spinner fa-pulse fa-fw');
        }
    });
    //=============BUSCAR DENTRO DE LA TABLA===========================
    $('#buscar_usuario').on('keyup',function filtro_buscar(){
        let indice = $(this).data("indice");
        let valor_filtro = $(this).val();
        let num_rows = buscar(valor_filtro,'tableData',indice);
    
        if(num_rows == 0){
            $('.mensaje').html(`
                <div class="alert alert-dark text-center" role="alert">
                    <strong>NO se ha encontrado</strong>
               </div>
            `);
        }else{
            $('.mensaje').html("");
        }
    });

});

//========funcion de listar con fechas============
function lista_fechas(fecha_inicio,fecha_final){
   let subconsulta ='LEFT JOIN usuarios USING (id_usuarios)';
    return $.ajax({
        url: '../recaudacion/control/listar_general.php',
        method: 'POST',
        dataType: 'JSON',
        data: {
            tabla: 'entrega_dinero_empresas',
            subconsulta: subconsulta,
            fecha_inicio: fecha_inicio,
            fecha_final: fecha_final,
            campo: 'fecha_entregadinero'
        }
    }).done(function(respuesta){
        resultado(respuesta);
    });
}

//========funcion de listar============
function listar(){
    let subconsulta ='LEFT JOIN usuarios USING (id_usuarios)  LEFT JOIN beneficiarios USING (id_beneficiarios)';
    let fecha_hoy = Date.today();
    let fecha = fecha_hoy.toString('yyyy-MM-dd');
    console.log(fecha);
    return $.ajax({
        url: '../recaudacion/control/listar_general.php',
        method: 'POST',
        dataType: 'JSON',
        data: {
            tabla: 'entrega_dinero_empresas',
            subconsulta: subconsulta,
            campo: 'fecha_entregadinero',
            fecha: fecha
        }
    }).done(function(respuesta){
        resultado(respuesta);
    });
}
//=========CREACION DE td PARA LA TABLA ===========================
function resultado(respuesta){
    if(respuesta.estatus == 'success'){
        let contenedor = '';
        let totalMonto = 0;
        if(respuesta.num_rows > 0){
            $.each(respuesta.mensaje,function(index,element){
                contenedor += `
                    <tr>
                        <td class="text-center">${element.id_entregadinero}</td>
                        <td class="text-center">${element.nombre_usuarios}</td>
                        <td class="text-center">${element.fecha_entregadinero}</td>
                        <td class="text-center">${element.nombre_empresas}</td>
                        <td class="text-center">${element.nombre_beneficiarios}</td>
                        <td class="text-center">${"$"+element.importe_entregadinero}</td>
                        <td class="text-center d-print-none">
                            <button class="btn btn-outline-primary imprimir " title="Imprimir" data-id_entregadinero='${element.id_entregadinero}'><i class="fas fa-print"></i></button>
                        </td>
                    </tr>
                `;
                totalMonto += Number(element.importe_entregadinero);
                                
            });
            let resultado = totalMonto.toFixed(2);
            $("#total").text("$"+resultado);
            console.log(resultado);
            //console.log(totalMonto+" "+"num");
            //let num = [2,4,6,8,10];
            //let suma = num.reduce((a,b) => a+b);
            //console.log(num);
            
        }else{
            contenedor = `
                <tr>
                    <td colspan="7"><h3 class="text-center">No hay Entrega de Dinero a Empresas</h3></td>
                </tr>
            `;
            $("#total").text(" ");
        }
        $('#tabla_DB').html(contenedor);
        

        //=========IMPRIMIR=========
        $('.imprimir').on('click',function(){
            let subconsulta ='LEFT JOIN usuarios USING (id_usuarios)';
            let boton = $(this);
            let id_entregadinero = boton.data('id_entregadinero');
            $.ajax({
                url: '../recaudacion/control/listar_general.php',
                method: 'POST',
                dataType: 'JSON',
                data: {
                    tabla: 'entrega_dinero_empresas',
                    subconsulta: subconsulta,
                    id_campo: id_entregadinero,
                    campo: 'id_entregadinero'
                }
            }).done(function(respuesta){
                if(respuesta.estatus == 'success'){
                    let contenedor = '';
                    if(respuesta.num_rows > 0){
                        $.each(respuesta.mensaje,function(index,element){
                            contenedor += `
                            <legend>Desglose de Dinero</legend> 
                            <div class="row mb-2">
                                <div class="col-4">
                                    <b>Folio:</b>
                                </div>	 
                                <div class="col-8">			
                                    ${element.id_desglose} 
                                </div>
                            </div>
                        
                            <div class="row mb-2">
                                <div class="col-4">
                                    <b>Fecha:</b>
                                </div>	 
                                <div class="col-8">			
                                    ${element.fecha_desglose}
                                </div>
                            </div>
                            `;
                        });
                    }else{
                        console.log("Error con la tabla de imp");
                    }
                    $('#ticket').html(contenedor);
                    $("#tableCard").addClass("d-print-none");
                    $('#formato_imprimir').removeClass("d-print-block");
                    $("#ticket").addClass("d-print-block");
                    window.print();
                }else{
                    alertify.error('Ocurrio un error');
                }
            }).always(function(){
                $("#tableCard").removeClass("d-print-none");
                $('#formato_imprimir').addClass("d-print-block d-print-none");
                $("#ticket").removeClass("d-print-block");
            });
        });

    }else{
        console.log("Error al cargar la tabla");
    }
}

function cadena_numeros (){
    let cantidad = [1000,500,200,100,50,20,10,5,2,1,.5,.2,.1,.05];
    let contenedor = "";
    cantidad.forEach(function(index,element){
        contenedor += `
            <div class="form-row">
                <div class="form-group col-md-6">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><i class="fas fa-dollar-sign">${index}</i></div>
                        </div>
                        <input type="number" class="form-control cantidad" min="0" data-denomi="${index}" val="">
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <div class="input-group">
                        <input type="number" min="0" class="form-control importe" value="0" readOnly>
                    </div>
                </div>
            </div>
        `;
    });
    $('#imp_cadena').html(contenedor);

    //=========TAQUILLA ============================
    $('.cantidad').on('keyup', function(){
        //console.log("calculaImporte()");
        let importe_total = 0;
        let $fila = $(this).closest(".form-row");
        let denominacion = Number($fila.find(".cantidad").data('denomi'));
        let cantidad = Number($fila.find(".cantidad").val());
        let importe = cantidad * denominacion;
        $fila.find('.importe').val(importe);
        console.log(importe);

        $(".importe").each( function sumarImportes(index, item){
			importe_total += Number($(item).val());
        });
        let subtotal = importe_total.toFixed(2);
        console.log(importe_total);
        $("#importe_total").val(subtotal);
    });
    //============TOTAL DE DOCUMENTO DE BOLETOS=================
    $('#datos_boleto').on('keyup',function(){
        let num_cantidad = $('#cantidad').val();
        let num_denominacion = $('#denominacion').val();
        let total = 0;
        total += num_cantidad * num_denominacion;
        $('#total_importe').val(total);
        $('#total_boletos').val(total);
        console.log(total);
    });

    //============TOTAL DE DOCUMENTO DE CHEQUES=================
    $('#documento_cheque').on('keyup',function(){
        let num_cheque = $(this).val();
        console.log(num_cheque);
    });
    //===========TOTAL DE TOTALES========================
    $('#todo').on('keyup',function(){
        let total_cheque = $('#documento_cheque').val();
        let total_boletos = $('#total_importe').val();
        let sub_total = $('#importe_total').val();
        let n = "";

        n = Number(total_cheque) + Number(total_boletos) + Number(sub_total) ;
        console.log(n);
        $('#importe_entregadinero').val(n);
    }); 
}