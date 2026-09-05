<?php
declare(strict_types=1);

/** Uso: php generar_token.php correo@dominio.mx [tipo] */
if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Este script solo se ejecuta desde la consola.\n");
}

$correo = $argv[1] ?? '';
$tipo = $argv[2] ?? 'getData';
if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    exit("Uso: php generar_token.php correo@dominio.mx [tipo]\n");
}

// Permite reutilizar la conexión local al generar y probar el token.
$_SERVER['SERVER_NAME'] = 'localhost';
require_once dirname(__DIR__) . '/taquilla/conexi.php';

$token = rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
$secreto = rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
$activo = 'SI';

$consulta = mysqli_prepare($link, 'INSERT INTO api_keys (api_key, secret, correo, tipo, activo) VALUES (?, ?, ?, ?, ?)');
mysqli_stmt_bind_param($consulta, 'sssss', $token, $secreto, $correo, $tipo, $activo);
if (!mysqli_stmt_execute($consulta)) {
    exit('No se pudo crear el token: ' . mysqli_error($link) . PHP_EOL);
}

echo "Token creado y activado para {$correo}. Guárdalo en un lugar seguro:\n{$token}\n";
