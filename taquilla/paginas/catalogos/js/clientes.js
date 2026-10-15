$(function () {
    listarClientes();
    $('#form_filtros').submit(function (e) { e.preventDefault(); listarClientes(); });
    $('#nuevo_cliente').click(function () { $('#form_cliente_frecuente')[0].reset(); $('#id_cliente_frecuente,#puntos_cliente,#uuid_cliente').val(''); $('#pais_cliente').val('México'); $('#clave_pais_cliente').val('+52'); $('#estatus_cliente').val('Activo'); $('#modal_cliente_frecuente').modal('show'); });
    $('#form_cliente_frecuente').submit(function (e) { e.preventDefault(); var f=$(this), b=f.find(':submit'); b.prop('disabled',true); $.post('consultas/guardar_cliente_frecuente.php',f.serialize(),function(r){ if(r.ok){ alertify.success(r.mensaje); $('#modal_cliente_frecuente').modal('hide'); listarClientes(); } else { alertify.error(r.mensaje); } },'json').fail(function(){alertify.error('No fue posible guardar.');}).always(function(){b.prop('disabled',false);}); });
});
function listarClientes(){ return $.get('consultas/listar_clientes.php',$('#form_filtros').serialize(),function(html){ $('#tabla_registros').html(html); $('.editar_cliente').click(cargarCliente); }); }
function cargarCliente(){ var id=$(this).data('id'); $.post('consultas/obtener_cliente.php',{id:id},function(r){ if(!r.ok){alertify.error('Cliente no encontrado');return;} $.each(r.cliente,function(k,v){$('#'+k).val(v);}); $('#modal_cliente_frecuente').modal('show'); },'json'); }
