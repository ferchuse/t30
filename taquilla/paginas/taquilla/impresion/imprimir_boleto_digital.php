<?php
	
	include('../../../conexi.php');
	include('../../../lib/numero_a_letras.php');
	
	$link = Conectarse();
	
	$protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
	$url_actual = $protocolo . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
	
	$consulta = "SELECT * FROM boletos
	
	LEFT JOIN usuarios USING (id_usuarios)
	LEFT JOIN unidades USING (num_eco)
	LEFT JOIN empresas USING (id_empresas)
	LEFT JOIN conductores USING (id_conductores)
	WHERE id_boletos={$_GET["folio"]} ";
	
	$result = mysqli_query($link, $consulta);
	
	while ($row = mysqli_fetch_assoc($result)) {
		
		$boleto = $row;
	}
	
	
	$telefono = $boleto["celular"] ;// formato internacional sin "+"
	$mensaje = "Hola, aquí está tu boleto digital:";
	// $pdf_url = "https://" . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"];
	
	// Construye el mensaje con el enlace al PDF
	$texto = urlencode("$mensaje\n$url_actual");
	
	// Genera la URL de WhatsApp (puedes usar wa.me o api.whatsapp.com)
	$whatsapp_url = "https://wa.me/$telefono?text=$texto";
	
	$titulo = "Boleto Digital {$boleto["nombre_empresas"]}";
	$descripcion = "Consulta y descarga tu boleto digital de {$boleto["nombre_empresas"]}. Incluye detalles de tu viaje, fecha y destino.";
	$imagen = $protocolo . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"]. "/../../../../img/logo.png"; // imagen de vista previa
	
?>

<!doctype html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<title>Boleto Digital <?php echo $boleto["nombre_empresas"]?> Folio <?php echo $boleto["id_boletos"]?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		
		<!-- Bootstrap 4 -->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
		
		<style>
			body{ background:#f5f7fb; }
			.ticket-card{
			max-width: 860px; margin: 30px auto; border-radius: 1rem;
			border: 1px solid #e5e7ef;
			}
			.ticket-header{
			background: #ffffff; border-bottom: 1px dashed #d8dbe6; padding: 1rem 1.25rem;
			}
			.ticket-body{ background:#fff; padding: 1.25rem; }
			.brand-flag{ font-weight: 700; letter-spacing: .5px; }
			.label{ color:#6c757d; margin:0; font-size:.85rem; }
			.value{ font-weight:600; margin:0; }
			.big-total{ font-size: 1.35rem; font-weight:700; }
			.qr-box{ text-align:center; }
			.desk-only{ display:none; }
			@media (min-width:768px){ .desk-only{ display:block; } }
			@media print {
			.no-print{ display:none !important; }
			body{ background:#fff; }
			.ticket-card{ box-shadow:none; border:1px solid #000; }
			}
		</style>
		
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
		
		
		
		<!-- Meta tags de vista previa para WhatsApp / Facebook -->
		<meta property="og:type" content="website">
		<meta property="og:title" content="<?php echo htmlspecialchars($titulo); ?>">
		<meta property="og:description" content="<?php echo htmlspecialchars($descripcion); ?>">
		<meta property="og:image" content="<?php echo htmlspecialchars($imagen); ?>">
		<meta property="og:url" content="<?php echo htmlspecialchars($url_actual); ?>">
		<meta property="og:site_name" content="<?php echo htmlspecialchars($boleto["nombre_empresas"]); ?>">
		
		
		
		<!-- También útiles para WhatsApp y otras apps -->
		<meta name="twitter:card" content="summary_large_image">
		<meta name="twitter:title" content="<?php echo htmlspecialchars($titulo); ?>">
		<meta name="twitter:description" content="<?php echo htmlspecialchars($descripcion); ?>">
		<meta name="twitter:image" content="<?php echo htmlspecialchars($imagen); ?>">
		
	</head>
	<body >
		<div class="container d-flex align-items-center justify-content-center">
			<button class="btn btn-info btn-sm no-print" onclick="window.print()">
				<i class="fas fa-print"></i> Imprimir
			</button>
			<button class="btn btn-primary btn-sm no-print" id="btn_correo">
				<i class="fas fa-envelope"></i> Correo
			</button>
			<a href="<?php echo $whatsapp_url; ?>"
			target="_blank"
			class="btn btn-success btn-sm no-print">
				<i class="fab fa-whatsapp"></i> WhatsApp
			</a>
		</div>
		<div class="card ticket-card">
			<!-- Header -->
			<div class="ticket-header d-flex align-items-center justify-content-between">
				<div class="d-flex align-items-center">
					<!-- Cambia la ruta del logo si lo necesitas -->
					<img src="../../../img/logo.png" alt="Zumpango Travels" style="height: 60px;">
					<div class="ml-3">
						<div class="h5 mb-0"><?php echo $boleto["nombre_empresas"]?></div>
						
					</div>
				</div>
				
			</div>
			
			<!-- Body -->
			<div class="ticket-body">
				<div class="row">
					<div class="col-md-8">
						<div class="row">
							<div class="col-6 mb-3">
								<p class="label">Folio</p>
								<p class="value"><?php echo $boleto["id_boletos"]?></p>
								<input type="hidden" value="<?php echo $boleto["id_boletos"]?>" id="folio" >
								<input type="hidden" value="<?php echo $boleto["correo_pasajero"]?>" id="correo_pasajero" >
							</div>
							<div class="col-6 mb-3">
								<p class="label">Fecha</p>
								<p class="value"><?php echo date("d/m/Y", strtotime($boleto["fecha_boletos"]))?></p>
							</div>
							<div class="col-6 mb-3">
								<p class="label">Hora</p>
								<p class="value"><?php echo date("H:i:s", strtotime($boleto["fecha_boletos"]))?></p>
							</div>
							<div class="col-6 mb-3">
								<p class="label">No. Económico</p>
								<p class="value"><?php echo $boleto["num_eco"]?></p>
							</div>
							<div class="col-4 mb-3">
								<p class="label">Atendido por</p>
								<p class="value"><?php echo $boleto["nombre_usuarios"]?></p>
							</div>
							<div class="col-4 mb-3">
								<p class="label">Pasajeros</p>
								<p class="value"><?php echo $boleto["pasajeros"]?></p>
							</div>
							<div class="col-4 mb-3">
								<p class="label">Nombre</p>
								<p class="value"><?php echo $boleto["nombre_pasajero"]?></p>
							</div>
							<div class="col-4 mb-3">
								<p class="label">Taquilla</p>
								<p class="value"><?php echo $boleto["tipo_origen"]?></p>
							</div>
							<div class="col-4 mb-3">
								<p class="label">Origen</p>
								<p class="value"><?php echo $boleto["origen"]?></p>
							</div>
							
							<div class="col-4 mb-3">
								<p class="label">Destino</p>
								<p class="value"><?php echo $boleto["destino"]?></p>
							</div>
							
							<div class="col-6 mb-3">
								<p class="label">Forma de pago</p>
								<p class="value"><?php echo $boleto["forma_pago"]?></p>
							</div>
							<div class="col-6 mb-3">
								<p class="label">Total</p>
								<p class="value big-total">$ <?php echo number_format($boleto["total"],2)?></p>
							</div>
							
							<div class="col-12 mb-2">
								<p class="label mb-1">Operador</p>
								<p class="value"><?php echo $boleto["nombre_conductores"]?></p>
							</div>
							
							<div class="col-12">
								<small class="text-muted">
									Zumpango de Ocampo, Estado de México, Aeropuerto Internacional Felipe Ángeles.
								</small>
							</div>
						</div>
					</div>
					
					<!-- QR -->
					<div class="col-md-4">
						<div class="qr-box p-3 border rounded d-none">
							<div class="mb-2 font-weight-bold">Validación del boleto</div>
							<!-- QR generado desde un servicio público (puedes sustituir por tu generador interno) -->
							<img
							alt="QR Validar Boleto"
							style="width: 180px; height: 180px"
							src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=https%3A%2F%2Ftaxisaifa.com%2Ftaquilla%2Fpaginas%2Ftaquilla%2Fvalidar_boleto.php%3Fid_boletos%3D48070"
							>
							<div class="mt-2">
								<a class="small" target="_blank"
								href="https://taxisaifa.com/taquilla/paginas/taquilla/validar_boleto.php?id_boletos=48070">
									Abrir validación
								</a>
							</div>
						</div>
						
						<div class="qr-box p-3 border rounded d-none">
							<div class="mb-2 font-weight-bold">Términos y Condiciones</div>
							<!-- QR generado desde un servicio público (puedes sustituir por tu generador interno) -->
							<img
							alt="QR Validar Boleto"
							style="width: 180px; height: 180px"
							src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=https%3A%2F%2Ftaxisaifa.com%2Fterminos.php"
							>
							<div class="mt-2">
								<a class="small" target="_blank"
								href="https://taxisaifa.com/terminos.php">
									Términos y Condiciones
								</a>
							</div>
						</div>
						
						<div class="mt-3 desk-only d-none">
							<small class="text-muted d-block">
								Escanee el QR o visite el enlace para verificar la autenticidad del boleto.
							</small>
						</div>
					</div>
				</div>
			</div>
			
			<!-- Footer -->
			<div class="px-4 py-3 bg-light border-top d-flex justify-content-between align-items-center">
				<small class="text-muted">Gracias por su preferencia. ¡Buen viaje!</small>
				<small class="text-muted d-none">www.taxisaifa.com</small>
			</div>
		</div>
		
		<!-- JS Bootstrap 4 -->
		<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
		<script >
			$("#btn_correo").click(function(){
				
				boton = $("#btn_correo");
				var icono = boton.find('i');
				
				boton.prop('disabled',true);
				icono.toggleClass('fa-envelope fa-spinner fa-spin');
				
				$.ajax({
					url: '../consultas/enviar_boleto_digital.php',
					method: 'GET',
					dataType: 'JSON',
					data:{
						"folio": $("#folio").val()
						
					}
					}).done(function(respuesta){
					
					alert("Correo enviado a " + $("#correo_pasajero").val())
					
					boton.prop('disabled',false);
					icono.toggleClass('fa-envelope fa-spinner fa-spin');
				})
			})
			
			
		</script>
		
		
		
	</body>
</html>



