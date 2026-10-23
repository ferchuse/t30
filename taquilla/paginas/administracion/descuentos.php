<?php
include_once("../login/login_check.php");
include("../../conexi.php");
$link = Conectarse();
$resultado = mysqli_query($link, "SELECT * FROM configuracion_lealtad WHERE id_configuracion = 1 LIMIT 1");
$configuracion = mysqli_fetch_assoc($resultado);
$promociones = mysqli_query($link, 'SELECT * FROM promociones ORDER BY id_promocion DESC');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Descuentos y lealtad</title>
    <?php include('../../styles.php')?>
    <style>
        .descuentos-pills .nav-link {
            border-radius: 50rem;
            padding: .65rem 1.5rem;
            margin-right: .5rem;
            margin-bottom: .5rem;
            font-weight: 600;
            color: #073b70;
            background: #edf3fa;
            border: 1px solid #c6d8eb;
        }
        .descuentos-pills .nav-link:hover { background: #dce9f7; }
        .descuentos-pills .nav-link.active {
            color: #fff;
            background: #073b70;
            border-color: #073b70;
            box-shadow: 0 3px 8px rgba(7, 59, 112, .2);
        }
        .descuentos-pills .nav-link:focus-visible {
            outline: 3px solid #70b5ff;
            outline-offset: 3px;
        }
    </style>
</head>
<body id="page-top">
<?php include("../../navbar.php")?>
<div id="wrapper"><?php include("../../menu.php")?>
    <div id="content-wrapper"><div class="container-fluid">
        <ol class="breadcrumb"><li class="breadcrumb-item">Administración</li><li class="breadcrumb-item active">Descuentos</li></ol>
        <ul class="nav nav-pills descuentos-pills mb-3" role="tablist">
            <li class="nav-item"><a class="nav-link active" id="tab-lealtad" data-toggle="tab" href="#lealtad" role="tab" aria-controls="lealtad" aria-selected="true">Lealtad</a></li>
            <li class="nav-item"><a class="nav-link" id="tab-promociones" data-toggle="tab" href="#promociones" role="tab" aria-controls="promociones" aria-selected="false">Promociones</a></li>
        </ul>
        <div class="tab-content"><div class="tab-pane fade show active" id="lealtad" role="tabpanel" aria-labelledby="tab-lealtad"><div class="row"><div class="col-lg-7 col-xl-6">
            <div class="card shadow mb-4">
                <div class="card-header"><h5 class="mb-0"><i class="fas fa-gift"></i> Programa de lealtad</h5></div>
                <div class="card-body">
                    <p class="text-muted">Activa el programa de puntos y define sus reglas de acumulación y canje.</p>
                    <form id="form_lealtad" class="was-validated">
                        <div class="custom-control custom-switch toggle-ios mb-4">
                            <input type="checkbox" class="custom-control-input" id="activo" name="activo" value="1" <?= !empty($configuracion['activo']) ? 'checked' : '' ?>>
                            <label class="custom-control-label font-weight-bold" for="activo">Programa de lealtad activo</label>
                        </div>
                        <div class="border rounded p-3 mb-3 bg-light"><strong>Puntos</strong><br><small class="text-muted">Los clientes acumulan puntos por sus compras y pueden canjearlos en una venta.</small></div>
                        <div class="form-group"><label>Monto para ganar 1 punto (MXN)</label><input type="number" min="0.01" step="0.01" required class="form-control" name="monto_por_punto" value="<?= htmlspecialchars($configuracion['monto_por_punto']) ?>"></div>
                        <div class="form-group"><label>Valor del punto al canjear (MXN)</label><input type="number" min="0.01" step="0.01" required class="form-control" name="valor_punto" value="<?= htmlspecialchars($configuracion['valor_punto']) ?>"></div>
                        <div class="form-group"><label>Canje mínimo (puntos)</label><input type="number" min="1" step="1" required class="form-control" name="canje_minimo" value="<?= htmlspecialchars($configuracion['canje_minimo']) ?>"></div>
                        <button class="btn btn-primary" type="submit"><i class="fas fa-save"></i> Guardar configuración</button>
                    </form>
                </div>
            </div>
        </div>
        </div></div>
        <div class="tab-pane fade" id="promociones" role="tabpanel" aria-labelledby="tab-promociones">
            <button type="button" class="btn btn-success mb-3" id="nueva_promocion"><i class="fas fa-plus"></i> Nueva promoción</button>
            <div class="card shadow mb-4"><div class="card-body table-responsive">
                <table class="table table-hover"><thead><tr><th>Promoción</th><th>Código</th><th>Descuento</th><th>Vigencia</th><th>Usos</th><th>Acciones</th></tr></thead><tbody>
                <?php if ($promociones && mysqli_num_rows($promociones)): ?>
                <?php while ($p = mysqli_fetch_assoc($promociones)): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['nombre_promocion'], ENT_QUOTES, 'UTF-8') ?><br><span class="badge badge-<?= $p['activo'] ? 'success' : 'secondary' ?>"><?= $p['activo'] ? 'Activo' : 'Inactivo' ?></span></td>
                        <td><?= htmlspecialchars($p['codigo_promocion'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= $p['tipo_descuento'] === 'Monto' ? '$' : '' ?><?= number_format((float)$p['valor_descuento'], 2) ?><?= $p['tipo_descuento'] === 'Porcentaje' ? '%' : '' ?></td>
                        <td><?php if ($p['fecha_inicio'] && $p['fecha_fin']): ?><?= date('d/m/Y H:i', strtotime($p['fecha_inicio'])) ?><br><?= date('d/m/Y H:i', strtotime($p['fecha_fin'])) ?><?php else: ?>Sin vigencia<?php endif; ?></td>
                        <td><span class="badge badge-info"><?= (int)$p['usos_realizados'] ?> usos<?= $p['uso_limitado'] ? ' de ' . (int)$p['limite_usos'] : '' ?></span></td>
                        <td><button type="button" class="btn btn-outline-primary btn-sm editar_promocion" data-promocion="<?= htmlspecialchars(json_encode($p), ENT_QUOTES, 'UTF-8') ?>"><i class="fas fa-pencil-alt"></i> Editar</button></td>
                    </tr>
                <?php endwhile; ?>
                <?php else: ?><tr><td colspan="6" class="text-muted"><?= $promociones ? 'No hay promociones registradas.' : 'No fue posible cargar las promociones.' ?></td></tr><?php endif; ?>
                </tbody></table>
            </div></div>
        </div></div>
        <div class="modal fade" id="modal_promocion" tabindex="-1" role="dialog" aria-labelledby="titulo_promocion" aria-hidden="true"><div class="modal-dialog modal-lg" role="document"><div class="modal-content">
                <div class="modal-header"><h5 class="modal-title" id="titulo_promocion">Nueva promoción</h5><button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button></div>
                <div class="modal-body">
                    <form id="form_promocion">
                        <input type="hidden" name="id_promocion" id="id_promocion" value="0">
                        <div class="custom-control custom-switch toggle-ios mb-3"><input type="checkbox" class="custom-control-input" id="primera_compra" name="primera_compra" value="1"><label class="custom-control-label" for="primera_compra">Automática para primera compra</label></div>
                        <small class="form-text text-muted mb-3">Una sola vez por cliente sin compras anteriores. Si hay varias promociones automáticas disponibles, se aplica la primera creada.</small>
                        <div class="custom-control custom-switch toggle-ios mb-3"><input type="checkbox" class="custom-control-input" id="promocion_activa" name="activo" value="1" checked><label class="custom-control-label" for="promocion_activa" id="etiqueta_promocion_activa">Activo</label></div>
                        <div class="form-group"><label for="nombre_promocion">Nombre de la promoción</label><input class="form-control" id="nombre_promocion" name="nombre_promocion" maxlength="150" required></div>
                        <div class="form-group"><label for="codigo_promocion">Código</label><input class="form-control text-uppercase" id="codigo_promocion" name="codigo_promocion" maxlength="64" autocomplete="off" required></div>
                        <div class="form-row">
                            <div class="form-group col-md-6"><label for="tipo_descuento_promocion">Tipo del descuento</label><select class="form-control" id="tipo_descuento_promocion" name="tipo_descuento"><option value="Porcentaje">Porcentaje</option><option value="Monto">Monto</option></select></div>
                            <div class="form-group col-md-6"><label for="valor_promocion">Valor</label><div class="input-group"><div class="input-group-prepend"><span class="input-group-text" id="icono_valor_promocion">%</span></div><input type="number" class="form-control" id="valor_promocion" name="valor_descuento" min="0.01" max="100" step="0.01" required></div></div>
                        </div>
                        <div class="form-row">
                            <div class="col-12 mb-3"><div class="custom-control custom-switch toggle-ios"><input type="checkbox" class="custom-control-input" id="tiene_vigencia" name="tiene_vigencia" value="1"><label class="custom-control-label" for="tiene_vigencia">Tiene vigencia</label></div></div>
                            <div class="form-group col-md-6 campo-vigencia d-none"><label for="inicio_promocion">Inicio de vigencia</label><input type="datetime-local" class="form-control" id="inicio_promocion" name="fecha_inicio" disabled></div>
                            <div class="form-group col-md-6 campo-vigencia d-none"><label for="fin_promocion">Fin de vigencia</label><input type="datetime-local" class="form-control" id="fin_promocion" name="fecha_fin" disabled></div>
                        </div>
                        <div class="custom-control custom-switch toggle-ios mb-3"><input type="checkbox" class="custom-control-input" id="uso_limitado" name="uso_limitado" value="1"><label class="custom-control-label" for="uso_limitado">Uso limitado</label></div>
                        <div class="form-group d-none" id="grupo_limite_usos"><label for="limite_usos">Cantidad de usos</label><input type="number" class="form-control" id="limite_usos" name="limite_usos" min="1" step="1" disabled></div>
                        <button class="btn btn-primary" type="submit">Guardar promoción</button>
                        <div id="resultado_promocion" class="mt-3" role="status" aria-live="polite"></div>
                    </form>
                </div>
            </div>
        </div>
        </div><!-- modal_promocion -->
    </div></div>
</div>
<?php include("../../scripts.php")?>
<script>
$("#form_lealtad").submit(function(event) {
    event.preventDefault();
    $.post("control/guardar_configuracion_lealtad.php", $(this).serialize(), function(respuesta) {
        if (respuesta.ok) { alertify.success("Configuración guardada"); }
        else { alertify.error(respuesta.mensaje || "No fue posible guardar"); }
    }, "json").fail(function() { alertify.error("No fue posible guardar"); });
});
$("#tipo_descuento_promocion").change(function(){
    var porcentaje = $(this).val() === "Porcentaje";
    $("#icono_valor_promocion").text(porcentaje ? "%" : "$");
    if (porcentaje) { $("#valor_promocion").attr("max", "100"); }
    else { $("#valor_promocion").removeAttr("max"); }
});
$("#uso_limitado").change(function(){ $("#grupo_limite_usos").toggleClass("d-none", !this.checked).find("input").prop("required", this.checked).prop("disabled", !this.checked); });
$("#form_promocion").submit(function(event){
    event.preventDefault();
    var form = this, boton = $(this).find(":submit"), resultado = $("#resultado_promocion");
    if (boton.prop("disabled")) { return; }
    boton.prop("disabled", true);
    resultado.removeClass("text-success text-danger").text("Guardando…");
    $.post("control/guardar_promocion.php", $(form).serialize(), null, "json")
    .done(function(r){
        resultado.addClass(r.ok ? "text-success" : "text-danger").text(r.mensaje);
        if (r.ok) { window.location.hash = 'promociones'; window.location.reload(); }
    }).fail(function(xhr){
        resultado.addClass("text-danger").text(xhr.responseJSON ? xhr.responseJSON.mensaje : "No fue posible guardar. Revisa la conexión antes de reintentar.");
    }).always(function(){ boton.prop("disabled", false); });
});
$("#tiene_vigencia").change(function(){
    $(".campo-vigencia").toggleClass("d-none", !this.checked).find("input").prop("disabled", !this.checked).prop("required", this.checked);
});
$("#nueva_promocion").click(function(){
    $("#form_promocion")[0].reset(); $("#id_promocion").val(0);
    $("#promocion_activa").trigger("change");
    $("#tiene_vigencia").trigger("change");
    $("#titulo_promocion").text("Nueva promoción");
    $("#resultado_promocion").empty();
    $("#tipo_descuento_promocion, #uso_limitado").trigger("change");
    $("#modal_promocion").modal("show");
});
$(".editar_promocion").click(function(){
    var p = $(this).data("promocion");
    $("#form_promocion")[0].reset();
    $("#id_promocion").val(p.id_promocion);
    $('#primera_compra').prop('checked', Number(p.primera_compra) === 1);
    $("#promocion_activa").prop("checked", Number(p.activo) === 1).trigger("change");
    $("#nombre_promocion").val(p.nombre_promocion); $("#codigo_promocion").val(p.codigo_promocion);
    $("#tipo_descuento_promocion").val(p.tipo_descuento).trigger("change");
    $("#valor_promocion").val(p.valor_descuento);
    $("#tiene_vigencia").prop("checked", !!(p.fecha_inicio && p.fecha_fin)).trigger("change");
    $("#inicio_promocion").val((p.fecha_inicio || '').replace(' ', 'T').slice(0,16));
    $("#fin_promocion").val((p.fecha_fin || '').replace(' ', 'T').slice(0,16));
    $("#uso_limitado").prop("checked", Number(p.uso_limitado) === 1).trigger("change");
    $("#limite_usos").val(p.limite_usos);
    $("#titulo_promocion").text("Editar promoción"); $("#resultado_promocion").empty();
    $("#modal_promocion").modal("show");
});
if (window.location.hash === '#promociones') { $('#tab-promociones').tab('show'); }
$("#promocion_activa").change(function(){ $("#etiqueta_promocion_activa").text(this.checked ? "Activo" : "Inactivo"); });
</script>
</body></html>
