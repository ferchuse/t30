# API T30

## Endpoint

`POST /api/operacion/getData/`

Parámetros obligatorios: `token`, `inicio` y `fin`. Las fechas usan el formato `YYYY-mm-dd HH:ii:ss`.

Cada elemento de `viajes` contiene `pagos`, un arreglo de objetos con `medio` e `importe`. Esto evita duplicar el viaje cuando hubo pagos mixtos. Para recolecciones finalizadas se incluye primero el anticipo y después los importes de la liquidación del boleto asociado.

## Clientes frecuentes

`POST /api/operacion/putCustomer/` crea o actualiza un registro de `clientes_frecuentes`. Recibe `token`, `nombre`, `apellidop`, `apelidom`, `mail`, `telefono` y `rfc` opcional. El teléfono identifica al cliente: si ya existe, se actualizan sus datos. En éxito responde `status` e `id`.

En producción la ruta es `https://t30.mx/app/api/operacion/putCustomer/`.

## Destinos

`POST /api/operacion/getDestinations/` recibe únicamente `token` y devuelve los destinos disponibles en `destinos`. Cada registro contiene `iddestino`, `destino`, `costo1` (sedán) y `costo2` (ejecutivo). En producción la ruta es `https://t30.mx/app/api/operacion/getDestinations/`.

## Token y pruebas

En local, genere una llave activa con:

```powershell
php .\generar_token.php correo@ejemplo.com
```

Abra `http://localhost/t30/api/pruebas/` para probar local (`config_local.php`) o producción (`config_produccion.php`). En producción el consumidor usa `https://t30.mx/app/api/operacion/getData/`. El token se captura en el formulario y nunca se guarda en sus archivos.

Para probar clientes frecuentes abra `http://localhost/t30/api/pruebas/putCustomer.php`.

Para probar el catálogo de destinos abra `http://localhost/t30/api/pruebas/getDestinations.php`.
