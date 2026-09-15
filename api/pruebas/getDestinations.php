<?php
declare(strict_types=1);

$ambiente = ($_POST['ambiente'] ?? 'local') === 'produccion' ? 'produccion' : 'local';
$config = require __DIR__ . '/config_' . $ambiente . '.php';
$respuesta = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $curl = curl_init(rtrim($config['base_url'], '/') . '/api/operacion/getDestinations/');
    curl_setopt_array($curl, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query(['token' => trim((string) ($_POST['token'] ?? ''))]),
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
    <title>Pruebas getDestinations API T30</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<main class="container py-5" style="max-width: 800px">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Pruebas getDestinations</h1>
        <div class="btn-group"><a class="btn btn-outline-secondary btn-sm" href="./">getData</a><a class="btn btn-outline-secondary btn-sm" href="putCustomer.php">putCustomer</a></div>
    </div>
    <div class="card shadow-sm">
        <div class="card-body p-4">
            <p class="text-secondary">Consulta el listado de destinos disponibles. Ambiente: <strong><?= htmlspecialchars($config['nombre']) ?></strong>.</p>
            <form method="post" class="row g-3">
                <div class="col-12"><label class="form-label">Ambiente<select class="form-select" name="ambiente"><option value="local" <?= $ambiente === 'local' ? 'selected' : '' ?>>Local</option><option value="produccion" <?= $ambiente === 'produccion' ? 'selected' : '' ?>>Producción</option></select></label></div>
                <div class="col-12"><label class="form-label">Token<input class="form-control" name="token" required value="<?= htmlspecialchars($_POST['token'] ?? '') ?>"></label></div>
                <div class="col-12"><button class="btn btn-primary" type="submit">Enviar POST a getDestinations</button></div>
            </form>
        </div>
    </div>
    <?php if ($respuesta): ?><div class="card shadow-sm mt-4"><div class="card-body"><h2 class="h5">Respuesta HTTP <?= (int) $respuesta['codigo_http'] ?></h2><pre class="bg-body-tertiary border rounded p-3 mb-0 text-wrap"><?= htmlspecialchars($error ?: $respuesta['cuerpo']) ?></pre></div></div><?php endif; ?>
</main>
</body>
</html>
