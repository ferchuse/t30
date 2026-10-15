<?php
include("../login/login_check.php");
include('../../conexi.php');
$link = Conectarse();
$nombre_pagina = 'Clientes frecuentes';
?>
<!DOCTYPE html>
<html lang="es"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Catálogo de <?= $nombre_pagina ?></title><?php include('../../styles.php')?>
</head><body id="page-top">
<?php include("../../navbar.php")?><div id="wrapper"><?php include("../../menu.php")?>
<div id="content-wrapper"><div class="container-fluid">
<ol class="breadcrumb"><li class="breadcrumb-item">Catálogos</li><li class="breadcrumb-item active"><?= $nombre_pagina ?></li></ol>
<div class="row mb-2"><div class="col-12"><button class="btn btn-success btn-sm" type="button" id="nuevo_cliente"><i class="fas fa-plus"></i> Nuevo cliente</button></div></div>
<form id="form_filtros" autocomplete="off"><div class="row mb-3">
<div class="col-md-4"><label>Nombre, teléfono o UUID</label><input class="form-control" name="busqueda" type="search"></div>
<div class="col-md-3"><label>Estatus</label><select class="form-control" name="estatus"><option value="">Todos</option><option value="Activo" selected>Activo</option><option value="Inactivo">Inactivo</option></select></div>
<div class="col-md-2 pt-4"><button class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Buscar</button></div>
</div></form>
<div class="table-responsive" id="tabla_registros"><h5>Cargando...</h5></div>
</div></div></div>
<a class="scroll-to-top rounded" href="#page-top"><i class="fas fa-angle-up"></i></a>
<form class="was-validated" id="form_cliente_frecuente" autocomplete="off"><div class="modal fade" id="modal_cliente_frecuente"><div class="modal-dialog modal-lg"><div class="modal-content">
<div class="modal-header"><h4 class="modal-title">Cliente frecuente</h4><button type="button" class="close" data-dismiss="modal">&times;</button></div>
<div class="modal-body"><input type="hidden" name="id_cliente_frecuente" id="id_cliente_frecuente">
<div class="form-row"><div class="form-group col-md-4"><label>Nombre</label><input class="form-control" required name="nombre_cliente" id="nombre_cliente"></div><div class="form-group col-md-4"><label>Apellido paterno</label><input class="form-control" name="apellidop_cliente" id="apellidop_cliente"></div><div class="form-group col-md-4"><label>Apellido materno</label><input class="form-control" name="apellidom_cliente" id="apellidom_cliente"></div></div>
<div class="form-row"><div class="form-group col-md-4"><label>País</label><input class="form-control" name="pais_cliente" id="pais_cliente" value="México" required></div><div class="form-group col-md-2"><label>Clave</label><input class="form-control" name="clave_pais_cliente" id="clave_pais_cliente" value="+52" required></div><div class="form-group col-md-6"><label>Teléfono</label><input class="form-control" name="telefono_cliente" id="telefono_cliente" required></div></div>
<div class="form-row"><div class="form-group col-md-6"><label>Correo</label><input class="form-control" type="email" name="correo_cliente" id="correo_cliente"></div><div class="form-group col-md-3"><label>RFC</label><input class="form-control text-uppercase" maxlength="13" name="rfc_cliente" id="rfc_cliente"></div><div class="form-group col-md-3"><label>Estatus</label><select class="form-control" name="estatus_cliente" id="estatus_cliente"><option>Activo</option><option>Inactivo</option></select></div></div>
<div class="form-row"><div class="form-group col-md-3"><label>Puntos acumulados</label><input class="form-control" id="puntos_cliente" readonly></div><div class="form-group col-md-9"><label>UUID para QR</label><input class="form-control" id="uuid_cliente" readonly></div></div>
<div class="form-group"><label>Observaciones</label><textarea class="form-control" rows="3" name="observaciones" id="observaciones"></textarea></div>
</div><div class="modal-footer"><button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button><button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Guardar</button></div>
</div></div></div></form>
<?php include("../../scripts.php")?><script src="js/clientes.js?v=<?= date('YmdHis') ?>"></script>
</body></html>
