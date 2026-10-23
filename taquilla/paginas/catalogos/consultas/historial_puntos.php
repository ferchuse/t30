<?php
header('Content-Type: text/html; charset=utf-8');
if (empty($_COOKIE['id_usuarios']) || !ctype_digit((string)$_COOKIE['id_usuarios'])) { http_response_code(401); exit('Sesión caducada.'); }
require_once __DIR__ . '/../../../conexi.php';
require_once __DIR__ . '/../../../funciones/dame_permiso.php';
if (!in_array(dame_permiso('clientes.php', $link), array('Lectura','Escritura','Supervisor','Administrador'), true)) { http_response_code(403); exit('No tienes permiso para consultar clientes.'); }
$id = filter_var(isset($_GET['id']) ? $_GET['id'] : '', FILTER_VALIDATE_INT, array('options'=>array('min_range'=>1)));
if (!$id) { http_response_code(400); exit('Cliente inválido.'); }
$q = mysqli_prepare($link, 'SELECT nombre_cliente, apellidop_cliente, apellidom_cliente, puntos_cliente FROM clientes_frecuentes WHERE id_cliente_frecuente=?');
mysqli_stmt_bind_param($q,'i',$id); mysqli_stmt_execute($q);
$cliente = mysqli_stmt_get_result($q)->fetch_assoc(); mysqli_stmt_close($q);
if (!$cliente) { http_response_code(404); exit('Cliente no encontrado.'); }
$q = mysqli_prepare($link, 'SELECT id_boletos, fecha_boletos, puntos_otorgados, puntos_canjeados, descuento_puntos, estatus_boletos FROM boletos WHERE id_cliente_frecuente=? AND (puntos_otorgados>0 OR puntos_canjeados>0) ORDER BY fecha_boletos DESC, id_boletos DESC');
mysqli_stmt_bind_param($q,'i',$id); mysqli_stmt_execute($q);
$movimientos = mysqli_stmt_get_result($q);
?>
<h6><?= htmlspecialchars(trim($cliente['nombre_cliente'].' '.$cliente['apellidop_cliente'].' '.$cliente['apellidom_cliente']), ENT_QUOTES, 'UTF-8') ?></h6>
<p><span class="badge badge-primary">Saldo actual: <?= (int)$cliente['puntos_cliente'] ?> puntos</span></p>
<div class="table-responsive"><table class="table table-bordered table-sm"><thead><tr><th>Fecha del boleto</th><th>Folio</th><th>Acumulados</th><th>Utilizados</th><th>Descuento por puntos</th><th>Estatus</th></tr></thead><tbody>
<?php if (!$movimientos->num_rows): ?><tr><td colspan="6" class="text-center text-muted">Sin movimientos de puntos registrados en boletos.</td></tr><?php endif; ?>
<?php while ($m = $movimientos->fetch_assoc()): ?>
<tr><td><?= htmlspecialchars($m['fecha_boletos'], ENT_QUOTES, 'UTF-8') ?></td><td><?= (int)$m['id_boletos'] ?></td><td class="text-success text-right">+<?= (int)$m['puntos_otorgados'] ?></td><td class="text-danger text-right">−<?= (int)$m['puntos_canjeados'] ?></td><td class="text-right">$<?= number_format((float)$m['descuento_puntos'],2) ?></td><td><?= htmlspecialchars($m['estatus_boletos'], ENT_QUOTES, 'UTF-8') ?></td></tr>
<?php endwhile; mysqli_stmt_close($q); ?>
</tbody></table></div>
<small class="text-muted">Historial basado en los puntos guardados en cada boleto. Los ajustes manuales o saldos anteriores sin boleto no aparecen aquí.</small>
