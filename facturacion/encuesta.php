<?php
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	
	date_default_timezone_set('America/Mexico_City');
	setlocale(LC_ALL,"en_US"); 
	
	include("control/is_selected.php");
	include("conexi.php");
	include("funciones/funciones_factura.php");
	
	$link = Conectarse();
	
	$id_emisores = 1;
	
	$emisor = getEmisor($link, $id_emisores );
	
	$folio = getFolio($link, $id_emisores);
	
	$productos = copyProductos($link, $_GET["folio"]);
	
	$venta = copyVenta($link, $_GET["folio"], $_GET["fecha"]);
	
	//NOTA: Una vez emitida la factura ya no podrás corregir ningún dato, por favor asegúrate que estén correctos antes de dar el click para emitir la factura.
	
?>

<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Encuesta de Calificación</title>
		<!-- Agregar los enlaces a Bootstrap y jQuery -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
		<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
		<!-- Agregar estilos personalizados -->
		<style>
			body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: left;
            margin: 20px;
			}
			
			.rating {
            display: flex;
            justify-content: left;
            margin-top: 5px;
			}
			
			.rating input {
            display: none;
			}
			
			.rating label {
            cursor: pointer;
            font-size: 30px;
            color: #ddd;
            margin: 0 10px;
            transition: color 0.3s; /* Transición de color al pasar el ratón */
			}
			
			.rating input:checked ~ label,
			.rating label:hover {
            color: #ffcc00;
			}
			
			button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4caf50;
            color: #fff;
            border: none;
            cursor: pointer;
			}
			
			
			.survey-question {
            margin-top: 20px;
			}
			
			.survey-options label {
            display: block;
            margin-bottom: 10px;
			}
			
			.survey-options label {
            margin: 0 15px 10px 0;
			}
			
			.survey-question p {
			font-weight: bold;
			}
			
			
		</style>
	</head>
	<body>
		<?php
			
			//Validacion de boleto
			if(count($venta["fila"]) == 0){
				
				
				
				echo "<div class='alert alert-danger text-center h4'>No se encontró el folio {$_GET["folio"]} con Fecha {$_GET["fecha"]} , verifica la información.
				<a href='index.php'>Regresar<a>
				</div>";
				
				
				exit();
			}
			
			if($venta["fila"]["id_facturas"] != ""){
				
				echo "<div class='alert alert-danger text-center h4'>
				Este ticket ya ha sido facturado previamente.
				<a href='index.php'>Regresar<a>
				</div>";
				
			?>
			<div class="row d-none">
				<div class="col-auto">
					<a href="facturacion/<?php echo "facturacion/". $venta["fila"]["url_pdf"]?>" class="btn btn-success" id="url_pdf">
						<i class="fas fa-file-pdf"></i> Descargar PDF
					</a>
					<a href="facturacion/<?php echo "facturacion/". $venta["fila"]["url_xml"]?>"  class="btn btn-info"  id="url_xml"><i class="fas fa-code"></i>
						Descargar XML
					</a>
				</div>
			</div>
			<?php
				exit();
			}
			
			$fecha_boleto = new DateTime($venta["fila"]["fecha_boletos"]);
			$fecha_actual = new DateTime("now");
			$intervalo=date_diff($fecha_boleto,$fecha_actual);
			
			// si es del mes pasado no facturar
			
			// echo $intervalo->days;
			if(!isset($_GET["override"])){
				
				// if($intervalo->days > 3){
				
				// echo "<div class='alert alert-danger text-center h4'>
				// El boleto tiene mas de 3 dias de haber sido emitido
				// <a href='index.php'>Regresar<a>
				// </div>";
				// exit();
				// }
				
				if(date_format($fecha_boleto, "m") != date_format($fecha_actual, "m") ){
					
					echo "<div class='alert alert-danger text-center h4'>
					El mes de emision del boleto es diferente al mes actual
					<a href='index.php'>Regresar<a>
					</div>";
					exit();
				}
			}
			
		?>
		
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<h2>Encuesta de Satisfacción</h2>
					<small>
					Antes de generar su factura responda esta breve encuesta
					</small>
					<form id="form_encuesta">
						
						<input type="hidden" name="id_boletos" id="id_boletos" value="<?php echo $_GET["folio"]?>">
						<input type="hidden" name="fecha" id="fecha" value="<?php echo $_GET["fecha"]?>">
						
						<div class="survey-question">
							<p>¿Cuál es el tipo de unidad que lo llevó a su destino?</p>
							<div class="survey-options">
								<label><input type="radio" required  name="tipo_unidad" value="Camioneta"> Camioneta</label>
								<label><input type="radio" required  name="tipo_unidad" value="Carro"> Carro</label>
							</div>
						</div>
						
						<div class="survey-question">
							<p>En los últimos 6 meses, ¿cuántas veces viajó con nosotros?</p>
							<div class="survey-options">
								<label><input type="radio" required  name="frequencia_viaje" value="1"> 1 ocasión</label>
								<label><input type="radio" required  name="frequencia_viaje" value="2 a 4"> 2 a 4 ocasiones</label>
								<label><input type="radio" required  name="frequencia_viaje" value="Más de 5"> Más de 5 ocasiones</label>
							</div>
						</div>
						
						<div class="survey-question">
							<p>¿Cómo califica la calidad del servicio de taquilla?</p>
							<div class="survey-options" >
								<div class="rating" data-question="rating_taquilla">
									<label class="rating_taquilla" for="star1">&#9733;</label>
									<input type="radio"   id="star1" name="rating_taquilla" value="1" />
									
									<label class="rating_taquilla" for="star2">&#9733;</label>
									<input type="radio"   id="star2" name="rating_taquilla" value="2" />
									
									<label class="rating_taquilla" for="star3">&#9733;</label>
									<input type="radio"   id="star3" name="rating_taquilla" value="3" />
									
									<label class="rating_taquilla" for="star4">&#9733;</label>
									<input type="radio"   id="star4" name="rating_taquilla" value="4" />
									
									<label class="rating_taquilla" for="star5">&#9733;</label>
									<input type="radio"   id="star5" name="rating_taquilla" value="5" />
								</div>
							</div>
						</div>
						
						<div class="survey-question">
							<p>¿Cómo califica la calidad del servicio de modulación?</p>
							<div class="survey-options">
								<div class="rating" data-question="rating_modulacion">
									<label class="rating_modulacion" for="rating_modulacionstar1">&#9733;</label>
									<input type="radio"   id="rating_modulacionstar1" name="rating_modulacion" value="1" />
									
									<label class="rating_modulacion" for="rating_modulacionstar2">&#9733;</label>
									<input type="radio"   id="rating_modulacionstar2" name="rating_modulacion" value="2" />
									
									<label class="rating_modulacion"  for="rating_modulacionstar3">&#9733;</label>
									<input type="radio"   id="rating_modulacionstar3" name="rating_modulacion" value="3" />
									
									<label class="rating_modulacion" for="rating_modulacionstar4">&#9733;</label>
									<input type="radio"   id="rating_modulacionstar4" name="rating_modulacion" value="4" />
									
									<label class="rating_modulacion"  for="rating_modulacionstar5">&#9733;</label>
									<input type="radio"   id="rating_modulacionstar5" name="rating_modulacion" value="5" />
								</div>
							</div>
						</div>
						
						<div class="survey-question">
							<p>¿Cómo califica la calidad del servicio del conductor?</p>
							<div class="survey-options">
								<div class="rating" data-question="rating_conductor">
									<label class="rating_conductor" for="rating_conductorstar1">&#9733;</label>
									<input type="radio"   id="rating_conductorstar1" name="rating_conductor" value="1" />
									
									<label class="rating_conductor" for="rating_conductorstar2">&#9733;</label>
									<input type="radio"   id="rating_conductorstar2" name="rating_conductor" value="2" />
									
									<label class="rating_conductor" for="rating_conductorstar3">&#9733;</label>
									<input type="radio"   id="rating_conductorstar3" name="rating_conductor" value="3" />
									
									<label class="rating_conductor" for="rating_conductorstar4">&#9733;</label>
									<input type="radio"   id="rating_conductorstar4" name="rating_conductor" value="4" />
									
									<label class="rating_conductor" for="rating_conductorstar5">&#9733;</label>
									<input type="radio"   id="rating_conductorstar5" name="rating_conductor" value="5" />
								</div>
							</div>
						</div>
						<div class="survey-question">
							<p>¿Está satisfecho con la limpieza e higiene de la unidad en donde viajó?</p>
							<div class="survey-options">
								<label><input type="radio" required  name="limpieza" value="SI"> Si, el operador y la unidad cuentan con la presentación adecuada.</label>
								<label><input type="radio" required  name="limpieza" value="NO"> No, no estoy satisfecho por la presentación.</label>
							</div>
						</div>
						<div class="survey-question">
							<p>¿Volvería a viajar con nosotros?</p>
							<div class="survey-options">
								<label><input type="radio" required  name="volveria_viajar" value="SI"> Sí, es un excelente servicio y estoy conforme con lo que se brindó.</label>
								<label><input type="radio" required  name="volveria_viajar" value="TAL VEZ"> Tal vez, es un buen servicio pero no sé cuándo vuelva a viajar pero estoy satisfecho.</label>
								<label><input type="radio" required  name="volveria_viajar" value="NO"> No, no fue un servicio de mi agrado.</label>
							</div>
						</div>
						
						<div class="survey-question">
							<p>Comentarios o sugerencias</p>
							<div class="survey-options">
								<textarea name="comentarios" class="form-control">
								</textarea>
							</div>
						</div>
						
						<div class="text-center">
							<button type="submit" class="btn btn-success btn-lg" >Enviar</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		
		<script>
			$(".rating input").on("change", function() {
				// $(this).css("color", "#ffcc00");
				var question = $(this).closest('.rating').data('question');
				
				$(this).prevAll("."+question).add(this).css("color", "#ffcc00");
				// $(this).prevAll().addBack().css("color", "#ffcc00");
				$(this).nextAll("."+question).css("color", "#ddd");
			});
			
			// $(".rating label").hover(function() {
			// $(this).css("color", "#ffcc00");
            // $(this).prevAll().addBack().css("color", "#ffcc00");
            // $(this).nextAll().css("color", "#ddd");
			// }, function() {
            // $(".rating label").css("color", "#ddd");
			// });
			
			
			$('#form_encuesta').submit( guardarEncuesta);
			
			
			function guardarEncuesta(event){
				console.log("guardarEncuesta()")
				event.preventDefault()
				
				
				
				
				var rating1 = $("input[name='rating_taquilla']:checked").val();
				var rating2 = $("input[name='rating_modulacion']:checked").val();
				var rating3 = $("input[name='rating_conductor']:checked").val();
				
				
				if (!rating1 || !rating2 || !rating3) {
					alert('Por favor, selecciona una calificación.');
					return false;
				}
				
				$boton = $("#form_encuesta:submit");
				$icono = $boton.find('i');
				
				$boton.prop('disabled',true);	
				$icono.toggleClass('fa-arrow-right fa-spinner fa-spin ');
				
				// alert('Has calificado con ' + ratingValue + ' estrellas. ¡Gracias por tu opinión!');
				//Aquí puedes enviar el valor de la calificación al servidor o realizar otras acciones.
				// } else {
				// alert('Por favor, selecciona una calificación antes de enviar.');
				// }
				
				
				$.ajax({
					url: 'control/guardar_encuesta.php',
					method: 'POST',
					dataType: 'JSON',
					data: $('#form_encuesta').serialize() 
					}).done(function(respuesta){
					if(respuesta.estatus == "error"){
						alert(respuesta.error)
						return false;
					}
					
					window.location.href= "nueva_factura.php?folio=" + $("#id_boletos").val()+ "&fecha=" + $("#fecha").val();
					}).always(function(){
					
					$boton.prop('disabled',false);	
					$icono.toggleClass('fa-arrow-right fa-spinner fa-spin ');
				})
			}
			
			
		</script>
		
	</body>
</html>
