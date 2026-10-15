<?php
header('Content-Type: application/json; charset=utf-8'); include('../../../conexi.php'); $link = Conectarse();
if (!isset($_COOKIE['id_usuarios'])) { http_response_code(401); echo json_encode(['ok'=>false]); exit; }
$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
$q = mysqli_prepare($link, 'SELECT * FROM clientes_frecuentes WHERE id_cliente_frecuente = ? LIMIT 1'); mysqli_stmt_bind_param($q, 'i', $id); mysqli_stmt_execute($q); $cliente = mysqli_fetch_assoc(mysqli_stmt_get_result($q)); mysqli_stmt_close($q);
echo json_encode(['ok' => (bool) $cliente, 'cliente' => $cliente]);
