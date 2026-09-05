# API T30

## Endpoint

`POST /api/operacion/getData/`

Parámetros obligatorios: `token`, `inicio` y `fin`. Las fechas usan el formato `YYYY-mm-dd HH:ii:ss`.

Cada elemento de `viajes` contiene `pagos`, un arreglo de objetos con `medio` e `importe`. Esto evita duplicar el viaje cuando hubo pagos mixtos. Para recolecciones finalizadas se incluye primero el anticipo y después los importes de la liquidación del boleto asociado.

## Token y pruebas

En local, genere una llave activa con:

```powershell
php .\generar_token.php correo@ejemplo.com
```

Abra `http://localhost/t30/api/pruebas/` para probar local (`config_local.php`) o producción (`config_produccion.php`). En producción el consumidor usa `https://t30.mx/app/api/operacion/getData/`. El token se captura en el formulario y nunca se guarda en sus archivos.
