<?php
declare(strict_types=1);

$ambiente = ($_POST['ambiente'] ?? 'local') === 'produccion' ? 'produccion' : 'local';
$config = require __DIR__ . '/config_' . $ambiente . '.php';
$respuesta = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos = [
        'token' => trim((string) ($_POST['token'] ?? '')),
        // datetime-local envía YYYY-mm-ddTHH:ii; la API recibe segundos.
        'inicio' => str_replace('T', ' ', trim((string) ($_POST['inicio'] ?? ''))) . ':00',
        'fin' => str_replace('T', ' ', trim((string) ($_POST['fin'] ?? ''))) . ':00',
    ];
    $curl = curl_init(rtrim($config['base_url'], '/') . '/api/operacion/getData/');
    curl_setopt_array($curl, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query($datos),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_TIMEOUT => 30,
    ]);
    $contenido = curl_exec($curl);
    $error = curl_error($curl) ?: null;
    $codigo = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
    curl_close($curl);
    $respuesta = ['codigo_http' => $codigo, 'cuerpo' => $contenido];
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pruebas API T30</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<main class="container py-5" style="max-width: 800px">
    <div class="card shadow-sm">
        <div class="card-body p-4">
            <h1 class="h3 mb-2">Pruebas API T30</h1>
            <p class="text-secondary mb-4">Ambiente: <strong><?= htmlspecialchars($config['nombre']) ?></strong> — <?= htmlspecialchars($config['base_url']) ?></p>
            <form method="post" class="row g-3">
                <div class="col-12"><label class="form-label">Ambiente<select class="form-select" name="ambiente"><option value="local" <?= $ambiente === 'local' ? 'selected' : '' ?>>Local</option><option value="produccion" <?= $ambiente === 'produccion' ? 'selected' : '' ?>>Producción</option></select></label></div>
                <div class="col-12"><label class="form-label">Token<input class="form-control" name="token" required value="<?= htmlspecialchars($_POST['token'] ?? '') ?>"></label></div>
                <div class="col-md-6"><label class="form-label">Inicio<input class="form-control" type="datetime-local" name="inicio" required value="<?= htmlspecialchars($_POST['inicio'] ?? date('Y-m-d\\T00:00')) ?>"></label></div>
                <div class="col-md-6"><label class="form-label">Fin<input class="form-control" type="datetime-local" name="fin" required value="<?= htmlspecialchars($_POST['fin'] ?? date('Y-m-d\\T23:59')) ?>"></label></div>
                <div class="col-12"><button class="btn btn-primary" type="submit">Enviar POST a getData</button></div>
            </form>
        </div>
    </div>
    <?php if ($respuesta): ?><div class="card shadow-sm mt-4"><div class="card-body"><h2 class="h5">Respuesta HTTP <?= (int) $respuesta['codigo_http'] ?></h2><pre class="bg-body-tertiary border rounded p-3 mb-0 text-wrap"><?= htmlspecialchars($error ?: $respuesta['cuerpo']) ?></pre></div></div><?php endif; ?>
</main>
</body>
</html>
