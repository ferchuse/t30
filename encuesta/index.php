<?php
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	
	date_default_timezone_set('America/Mexico_City');
	setlocale(LC_ALL,"en_US"); 
	
	// include("control/is_selected.php");
	include("../taquilla/conexi.php");
	// include("funciones/funciones_factura.php");
	
	$link = Conectarse();
	
	
	
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
		
		<div class="container">
			<img src="img/encabezado.png" class="img-fluid">
			<div class="row">
				<div class="col-md-12">
					<h2>ENCUESTA SOBRE LA NUEVA RUTA DE TRANSPORTE</h2>
					<small>
						
						Zona de Carga AIFA – Deportivo 18 de Marzo
						
					</small>
					<div class="alert alert-warning">
						Estimado usuario, estamos implementando un nuevo servicio de transporte y nos gustaría conocer sus necesidades para ofrecerle un mejor servicio. Le pedimos responder las siguientes preguntas.
					</div>
					
					<form id="form_encuesta">
						<h4>DATOS DE CONTACTO</h4>
						<div class="form-group">
							<label>Nombre completo:</label>
							<input type="text" class="form-control" name="nombre" required>
						</div>
						<div class="form-group">
							<label>Celular:</label>
							<input type="tel" class="form-control" name="contacto" required>
						</div>
						
						<h4>HÁBITOS DE VIAJE</h4>
						<div class="form-group">
							<label>1. ¿Con qué frecuencia realiza este recorrido?</label>
							<select class="form-control" name="frecuencia" required>
								<option value="">Elige...</option>
								<option>Diario</option>
								<option>De 3 a 5 veces por semana</option>
								<option>De 1 a 2 veces por semana</option>
								<option>Ocasionalmente</option>
							</select>
						</div>
						
						<div class="form-group">
							<label>2. ¿En qué horario necesita llegar a su destino final?</label>
							<select class="form-control" name="horario_llegada" required>
								<option value="">Elige...</option>
								<option>Antes de las 6:00 AM</option>
								<option>Entre 6:00 AM y 8:00 AM</option>
								<option>Entre 8:00 AM y 10:00 AM</option>
								<option>Entre 10:00 AM y 12:00 PM</option>
								<option>Después de las 12:00 PM</option>
							</select>
						</div>
						
						<div class="form-group">
							<label>3. ¿Desde qué punto inicia su viaje al AIFA?</label>
							<select class="form-control" name="origen" required>
								<option value="">Elige...</option>
								<option>Indios Verdes</option>
								<option>Deportivo 18 de Marzo</option>
								<option>La Raza</option>
								<option>Otra ubicación</option>
							</select>
							<input type="text" class="form-control mt-2" name="origen_otro" placeholder="Especifique otra ubicación">
						</div>
						
						<div class="form-group">
							<label>4. ¿Cuál es su destino final después de llegar a Deportivo 18 de Marzo?</label>
							<select class="form-control" name="destino" required>
								<option value="">Elige...</option>
								<option>Centro Histórico de CDMX</option>
								<option>Polanco</option>
								<option>Santa Fe</option>
								<option>Iztapalapa</option>
								<option>Otra ubicación</option>
							</select>
							<input type="text" class="form-control mt-2" name="destino_otro" placeholder="Especifique otra ubicación">
						</div>
						
						<div class="form-group">
							<label>5. ¿Qué medio de transporte utiliza actualmente para llegar a su destino?</label>
							<select class="form-control" name="transporte">
								<option value="">Elige...</option>
								<option>Autobús foráneo</option>
								<option>Transporte público (Metro, Metrobús, combi)</option>
								<option>Taxi o servicio de aplicación (Uber, Didi, Cabify)</option>
								<option>Vehículo particular</option>
								<option>Otro</option>
							</select>
							<input type="text" class="form-control mt-2" name="transporte_otro" placeholder="Especifique otro medio">
						</div>
						
						<div class="form-group">
							<label>6. ¿Cuánto paga actualmente por su traslado desde el AIFA a Deportivo 18 de Marzo?</label>
							<select class="form-control" name="costo_actual">
								<option value="">Elige...</option>
								<option>Menos de $50 MXN</option>
								<option>Entre $50 y $100 MXN</option>
								<option>Entre $100 y $150 MXN</option>
								<option>Entre $150 y $200 MXN</option>
								<option>Otro</option>
							</select>
							<input type="text" class="form-control mt-2" name="costo_otro" placeholder="Especifique cuánto paga">
						</div>
						
						<div class="form-group">
							<label>7. ¿Cuánto estaría dispuesto a pagar por un servicio directo?</label>
							<select class="form-control" name="costo_dispuesto">
								<option value="">Elige...</option>
								<option>Menos de $50 MXN</option>
								<option>Entre $50 y $100 MXN</option>
								<option>Entre $100 y $150 MXN</option>
								<option>Entre $150 y $200 MXN</option>
								<option>Otro</option>
							</select>
							<input type="text" class="form-control mt-2" name="costo_dispuesto_otro" placeholder="Especifique cuánto pagaría">
						</div>
						
						<div class="form-group">
							<label>8. ¿En qué horario necesitaría el servicio desde AIFA hacia 18 de Marzo?</label>
							<select  class="form-control" name="horario_servicio" required>
								<option value="">Elige...</option>
								<option>Antes de las 6:00 AM</option>
								<option>Entre 6:00 AM y 8:00 AM</option>
								<option>Entre 8:00 AM y 10:00 AM</option>
								<option>Entre 10:00 AM y 12:00 PM</option>
								<option>Después de las 12:00 PM</option>
								<option>Otro</option>
							</select>
							<input type="text" class="form-control mt-2" name="horario_otro" placeholder="Especifique otro horario">
						</div>
						<div class="form-group">
							<label>9. ¿Cuánto tiempo le toma actualmente llegar desde la Zona de Carga del AIFA a 18 de Marzo?</label>
							<select class="form-control" name="tiempo_viaje" required>
								<option value="">Elige...</option>
								<option>Menos de 45 minutos</option>
								<option>Entre 45 minutos y 1 hora</option>
								<option>Entre 1 hora y 1 hora 30 minutos</option>
								<option>Más de 1 hora 30 minutos</option>
							</select>
						</div>
						<div class="form-group">
							<label>10.	¿Qué aspectos considera más importantes en un servicio de transporte? (Puede seleccionar hasta 3 opciones usando la tecla Ctrl)</label>
							<select class="form-control mb-2" name="aspectos_importantes[]" multiple required>
								<option value="">Elige...</option>
								<option>Precio accesible</option>
								<option>Seguridad</option>
								<option>Comodidad del vehículo</option>
								<option>Disponibilidad de horarios</option>
							</select>
							
							<input type="aspectos_importantes_otro" class="form-control" placeholder="Especifique otro aspecto">
							
							
							
						</div>
						
						
						
						
						<div class="form-group">
							<label>11. ¿Estaría interesado en un sistema de reservación anticipada para asegurar su asiento?</label>
							<select class="form-control" name="reservacion" required>
								<option value="">Elige...</option>
								<option>Sí</option>
								<option>No</option>
							</select>
						</div>
						
						
						<div class="form-group">
							<label>12. ¿Algún otro comentario o sugerencia?</label>
							<textarea class="form-control" name="comentarios" rows="3"></textarea>
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
					url: 'consultas/guardar_encuesta_colectivo.php',
					method: 'POST',
					dataType: 'JSON',
					data: $('#form_encuesta').serialize() 
					}).done(function(respuesta){
					if(respuesta.estatus == "error"){
						alert(respuesta.error)
						return false;
					}
					
					window.location.href = "encuesta_finalizada.php?uid=" + respuesta.uid;
					}).always(function(){
					
					$boton.prop('disabled',false);	
					$icono.toggleClass('fa-arrow-right fa-spinner fa-spin ');
				})
			}
			
			
		</script>
		
	</body>
</html>
