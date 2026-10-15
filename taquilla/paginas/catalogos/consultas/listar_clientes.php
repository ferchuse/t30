<?php
include('../../../conexi.php');
include('../../../funciones/dame_permiso.php');
$link = Conectarse();
if (!isset($_COOKIE['id_usuarios'])) { http_response_code(401); exit('Sesión caducada.'); }
$busqueda = isset($_GET['busqueda']) ? trim($_GET['busqueda']) : '';
$estatus = isset($_GET['estatus']) ? $_GET['estatus'] : '';
$sql = "SELECT id_cliente_frecuente, uuid_cliente, nombre_cliente, apellidop_cliente, apellidom_cliente, clave_pais_cliente, telefono_cliente, correo_cliente, puntos_cliente, estatus_cliente FROM clientes_frecuentes WHERE 1";
if ($busqueda !== '') { $b = mysqli_real_escape_string($link, $busqueda); $sql .= " AND (CONCAT(nombre_cliente, ' ', apellidop_cliente, ' ', apellidom_cliente) LIKE '%{$b}%' OR telefono_cliente LIKE '%{$b}%' OR uuid_cliente LIKE '%{$b}%')"; }
if (in_array($estatus, ['Activo', 'Inactivo'], true)) { $sql .= " AND estatus_cliente = '" . mysqli_real_escape_string($link, $estatus) . "'"; }
$sql .= ' ORDER BY nombre_cliente, apellidop_cliente, apellidom_cliente';
$resultado = mysqli_query($link, $sql);
?>
<table class="table table-bordered table-hover table-sm"><thead><tr><th>Cliente</th><th>Teléfono</th><th>Puntos</th><th>UUID</th><th>Estatus</th><th></th></tr></thead><tbody>
<?php while ($fila = mysqli_fetch_assoc($resultado)) { $nombre = trim($fila['nombre_cliente'].' '.$fila['apellidop_cliente'].' '.$fila['apellidom_cliente']); ?>
<tr><td><?= htmlspecialchars($nombre) ?><br><small><?= htmlspecialchars($fila['correo_cliente']) ?></small></td><td><?= htmlspecialchars($fila['clave_pais_cliente'].' '.$fila['telefono_cliente']) ?></td><td class="text-right"><?= (int) $fila['puntos_cliente'] ?></td><td><small><?= htmlspecialchars($fila['uuid_cliente']) ?></small></td><td><span class="badge badge-<?= $fila['estatus_cliente'] === 'Activo' ? 'success' : 'secondary' ?>"><?= htmlspecialchars($fila['estatus_cliente']) ?></span></td><td><button class="btn btn-outline-warning btn-sm editar_cliente" data-id="<?= (int) $fila['id_cliente_frecuente'] ?>"><i class="fas fa-pencil-alt"></i></button></td></tr>
<?php } ?></tbody><tfoot><tr><td colspan="6"><?= mysqli_num_rows($resultado) ?> registros.</td></tr></tfoot></table>
