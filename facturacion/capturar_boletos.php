<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Boletos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2>Agregar Boletos</h2>
        <div class="mb-3">
            <label for="codigoBoleto" class="form-label">Código del Boleto</label>
            <input type="text" id="codigoBoleto" class="form-control" placeholder="Ingrese código">
        </div>
        <button class="btn btn-primary" id="validarBoleto">Validar y Agregar</button>
        
        <h3 class="mt-4">Boletos Agregados</h3>
        <ul id="listaBoletos" class="list-group mt-2"></ul>
        
        <a href="captura_facturacion.html" class="btn btn-success mt-3" id="continuar" style="display: none;">Continuar a Facturación</a>
    </div>
    
    <script>
        $(document).ready(function () {
            let boletos = [];
            
            $('#validarBoleto').click(function () {
                let codigo = $('#codigoBoleto').val().trim();
                if (codigo === '') {
                    alert('Ingrese un código de boleto');
                    return;
                }
                
                $.ajax({
                    url: 'validar_boleto.php',
                    type: 'POST',
                    data: { codigo: codigo },
                    dataType: 'json',
                    success: function (response) {
                        if (response.valido && !boletos.includes(codigo)) {
                            boletos.push(codigo);
                            $('#listaBoletos').append(`<li class="list-group-item">${codigo}</li>`);
                            $('#codigoBoleto').val('');
                            if (boletos.length > 0) {
                                $('#continuar').show();
                            }
                        } else {
                            alert(response.mensaje);
                        }
                    },
                    error: function () {
                        alert('Error al validar el boleto');
                    }
                });
            });
        });
    </script>
</body>
</html>
