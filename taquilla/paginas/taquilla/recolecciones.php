<?php
	include("../../paginas/login/login_check.php");
	include('../../conexi.php');
	include('../../funciones/generar_select.php');
	// include_once('../../funciones/dame_permiso.php');
	include("consultas/get_destinos.php");
	include("consultas/get_terminales.php");
	$link = Conectarse();
	$nombre_pagina = "Recolecciones";
	
	$destinos = getDestinos($link);
	$terminales = getTerminales($link);
	
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">
		<title>Recolecciones</title>
		<?php include('../../styles.php')?>
		<link href="../../css/corrida.less" type="text/css"  rel="stylesheet/less" >
	</head>
	<body id="page-top">
		<?php include("../../navbar.php")?>
		<div id="wrapper" class="">
			<?php include("../../menu.php")?>	
			<div id="content-wrapper">		
				<div class="container-fluid">		
					<!-- Breadcrumbs-->
					<ol class="breadcrumb d-print-none">
						<li class="breadcrumb-item">
							<a href="#">Taquilla</a>
						</li>
						<li class="breadcrumb-item active"><?php echo $nombre_pagina;?></li>
					</ol>
					
					<div class="card card-primary">
						<div class="card-body">
							<div class="row">
								<div class="col-sm-6">
									
									<form id="form_recoleccion" class="was-validated" autocomplete="off" >
										<input type="hidden" name="id_recaudacion" id="id_recaudacion" value="">
										<div class="row"  >
											<div class="form-group col-sm-6"  >
												<label>Fecha de Recoleccion</label>
												<input id="fecha_recoleccion" name="fecha_recoleccion" class="form-control" type="datetime-local" value="<?= date("Y-m-d")?>"  >
											</div>
										</div>
										
										<div class="form-group row">
											
											
											<div class="form-group col-sm-6">
												<label class="">Lugar Recolección:</label>
												<select class="form-control" id="destino" name="destino" required>
													<option value="" required >Elige un destino:</option>
													<?php
														foreach($destinos AS $i=> $destino){
														?>
														<option 
														data-precio="<?php echo $destino["precio"];?>"
														data-precio_ejecutiva="<?php echo $destino["precio_ejecutiva"];?>"
														><?php echo $destino["destino"];?></option>
														<?php
														}
													?>
												</select>
											</div>
											
											<div class="col-sm-6">
												<label>Num Pasajeros:</label>
												<input type="number" required id="pasajeros" name="pasajeros" class="form-control text-right">
											</div>
										</div>
										<div class="form-group row">
											<div class="col-sm-6">
												<label>Nombre Pasajero:</label>
												<input type="text" required id="nombre_pasajero" name="nombre_pasajero" class="form-control mayus">
											</div>
											<div class="col-sm-6">
												<label>#Teléfono:</label>
												<input type="tel"  id="celular" name="celular" class="form-control" required>
											</div>
											
										</div>
										
										
										<div class="row">
											<div class="form-group col-sm-6">
												<label>Precio :</label>
												<input type="number" id="total" name="total" class="form-control text-right" required>
											</div>
											
											<div class="form-group col-sm-6">
												<label>Tipo :</label>
												<select class="form-control" id="tipo_recoleccion" name="tipo_recoleccion" required>
													<option selected >RECOLECCIÓN</option>
													<option  >RESERVACIÓN</option>
													
												</select>
											</div>
											
											
										</div>
										
										<div class="row">
											<div class="form-group col-sm-6">
												<label>Anticipo :</label>
												<input type="number" id="anticipo" name="anticipo" class="form-control text-right" required >
											</div>
											<div class="form-group col-sm-6">
												<label class="">Forma de Pago:</label>
												<select class="form-control" id="forma_pago" name="forma_pago" required>
													<option value="" >Elige..</option>
													<option  >Efectivo</option>
													<option  >Transferencia</option>
													<option >Tarjeta</option>
													<option  >Mixto</option>
												</select>
											</div>
										</div>
										<div class="row form-group">
											<div class=" col-sm-6">
												<label>Restante :</label>
												<input type="number" id="restante" name="restante" class="form-control text-right" required>
											</div>
											<div class=" col-sm-6">
												<label>Folio Transferencia :</label>
												<input type="number" id="referencia" name="referencia" class="form-control text-right" >
											</div>
										</div>
										
										<div class="row form-group">
											<div class=" col-sm-6">
												<div class="form-group" id="div_terminal" style="display:none">
													<label>Terminal:</label>
													<?php echo generar_select($link, "cat_terminales", "id_terminal", "terminal"); ?>
												</div>
											</div>
											<div class=" col-sm-6">
												<?php 
													if (in_array(dame_permiso("recolecciones.php", $link) , array("Supervisor","Administrador", "Escritura"))){
													?>
													<button class="btn btn-success float-right" id="btn_vender"  >
														<i class="fas fa-save"></i>  Guardar
													</button>
													
													<?php 		
													}
													
												?>
												
											</div>
										</div>
										
									</form>
								</div>
								
								<div class="col-sm-6">
									<div id="calendar">	</div>
									
									
									
								</div>
							</div>
							
						</div>
					</div><!-- /.card-body-->
					
					
					<div class="card card-primary">
						<div class="card-header">
							
							<form id="form_filtros" >
								<div class="row">
									
									
									<div class="form-group col-sm-2">
										<label> Fecha Inicial:</label>
										<input type="date" class="form-control" value="<?php echo date("Y-m-d");?>" name="fecha_inicial" id="fecha_inicial">
									</div>
									
									<div class="form-group col-sm-2">
										<label> Fecha Final:</label>
										<input type="date" class="form-control" value="<?php echo date("Y-m-d" , strtotime("+ 7 days"));?>" name="fecha_final" id="fecha_final">
									</div>
									
									<div class="col-sm-1">
										
										<label>Estatus:</label>
										<select  class="form-control"  name="estatus">
											<option selected value="">Todos</option>
											<option  value="PENDIENTE">PENDIENTE</option>
											<option  value="FINALIZADA">FINALIZADA</option>
											<option  value="CANCELADA">CANCELADA</option>
										</select>
										
									</div>
									<div class="col-sm-2">
										
										<label>Tipo:</label>
										<select  class="form-control"  name="tipo_recoleccion">
											<option selected value="">Todos</option>
											<option  >RECOLECCIÓN</option>
											<option  >RESERVACIÓN</option>
										</select>
										
									</div>
									<div class="form-group col-sm-1 pt-4"> 
										<button type="submit"  title="Buscar" class="btn btn-primary  d-print-none btn-sm ">
											<i class="fas fa-search"></i> Buscar
										</button>	
										
									</div>
									
								</div>
								
							</form>
						</div>
						<div class="card-body">
							
							<div class="table-responsive" id="lista_recolecciones">
								
								
							</div>
						</div>
					</div>
				</div><!-- /.container-fluid -->
				
				
			</div> 
			<!-- /.content-wrapper -->
		</div>
		
		
		
		
		
		<!-- Scroll to Top Button-->
		<a class="scroll-to-top rounded d-print-none" href="#page-top">
			<i class="fas fa-angle-up"></i>
		</a>
		
		
		<?php include("recolecciones/forms/form_liquidar.php")?>
		<?php include("recolecciones/forms/form_asignar.php")?>
		<?php include("recolecciones/forms/form_editar.php")?>
		
		<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
		
		<?php include("../../scripts.php")?>
		
		<script src="../../lib/websocket-printer.js" > </script>
		<script >  var printService = new WebSocketPrinter(); </script>
		<script>
			var calendar ;
			
			document.addEventListener('DOMContentLoaded', function() {
				var calendarEl = document.getElementById('calendar');
				calendar = new FullCalendar.Calendar(calendarEl, {
					// plugins: [dayGridPlugin],
					initialView: 'dayGridMonth',
					events: "recolecciones/consultas/lista_recolecciones_calendar.php",
					// events: [
					// {
					// id  : '1',
					// title  : 'Recoleccion Juan',
					// start  : '2024-08-23'
					// },
					// {
					// id  : '2',
					// title  : 'Recoleccion 2',
					// start  :'2024-08-22',
					// end    : '2024-08-11'
					// },
					// {
					// id  : '3',
					// title  : 'Recoleccion Aeropuerto',
					// start  : '2024-08-24T12:30:00',
					// allDay : false // will make the time show
					// }
					// ],
					eventClick: function(info) {
						// console.log("info",info)
						// alert('title: ' + info.event.title);
						alert('Folio: ' + info.event.id + " Lugar: "+ info.event.title);
						
						
						
						// Obtener el ID del evento
						// var eventId = info.event.id;
						// alert("eventId", eventId)
						// Realizar la solicitud AJAX para obtener más detalles
						// fetch('/ruta_a_tu_script_de_detalles.php?id=' + eventId)
						// .then(response => response.json())
						// .then(data => {
						// Mostrar los datos en un modal o de alguna otra forma
						// document.getElementById('eventTitle').innerText = "Título: " + data.title;
						// document.getElementById('eventClient').innerText = "Cliente: " + data.client_name;
						// Mostrar el modal o cualquier otro mecanismo
						// document.getElementById('eventDetailsModal').style.display = 'block';
						// })
						// .catch(error => console.error('Error:', error));
					}
					
				});
				calendar.setOption('locale', 'es');
				calendar.render();
			});
			
			
		</script>
		
		<script src="recolecciones/js/recolecciones.js?v=<?= date("Y-m-d-H-s")?>"></script>
		<!-- PushAlert -->
		<!-- PushAlert -->
		<script type="text/javascript">
			(function(d, t) {
                var g = d.createElement(t),
                s = d.getElementsByTagName(t)[0];
                g.src = "https://cdn.pushalert.co/integrate_7d68f00a03cf2dfcc08ff411e941d919.js";
                s.parentNode.insertBefore(g, s);
			}(document, "script"));
		</script>
		<!-- End PushAlert -->
		<!-- End PushAlert -->
		
		<script>
			(pushalertbyiw = window.pushalertbyiw || []).push(['addToSegment', "51126", callbackFunction]);
			
			function callbackFunction(result){
				console.log(result.success) // True or False
				//Your Code
			}
		</script>
	</body>
</html>																														