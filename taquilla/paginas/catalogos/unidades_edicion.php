<?php 
	include("../login/login_check.php");
	include("../../conexi.php");
	include("../../funciones/generar_select.php");
	
	$link = Conectarse();
	
	$id_unidades = $_GET["id_unidades"];
	
	
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Catálogo de Unidades</title>
		<?php include('../../styles.php')?>
	</head>
	<body id="page-top">
    <?php include("../../navbar.php")?>
    <div id="wrapper">
			<?php include("../../menu.php")?>	
			<div id="content-wrapper">		
				<div class="container-fluid">		
          <!-- Breadcrumbs-->
					<ol class="breadcrumb">
						<li class="breadcrumb-item">
							<a href="#">Catálogos</a>
						</li>
						<li class="breadcrumb-item active">Unidades</li>
					</ol>
					
					<div class="row mb-2">
						<div class="col-12">
							
							<button type="button" class="btn btn-success" >
								<i class="fas fa-save"></i> Guardar
							</button>
						</div>
					</div>
					<hr>
					
					<form id="form_edicion" autocomplete="off" class="was-validated">
						
						<input class="d-none" name="id_unidades" id="id_unidades" value="">
						<input class="d-none" name="fecha_ingreso" id="fecha_ingreso" value="<?php echo date("Y-m-d");?>">
						<div class="row mb-2">
							<div class="col-2">
								<label >Empresa:</label>
							</div>	 
							<div class="col-5">			
								<?php
									echo generar_select($link, "empresas", "id_empresas", "nombre_empresas");
								?>
							</div>
						</div>
						<div class="row mb-2">
							<div class="col-2">
								<label >No Eco:</label>
							</div>	
							<div class="col-5">			
								<input class="form-control" type="number" name="num_eco" id="num_eco" required>
							</div>
						</div>
						<div class="row mb-2">
							<div class="col-2">
								<label >Propietario:</label>
							</div>	
							<div class="col-5">			
								<?php
									echo generar_select($link, "propietarios", "id_propietarios", "nombre_propietarios");
								?>
							</div>
						</div>
						<div class="row mb-2">
							<div class="col-2">
								<label >Tipo Vehículo:</label>
							</div>	
							<div class="col-5">			
								<select class="form-control" id="tipo_unidad" name="tipo_unidad">
									<option value="">Seleccione</option>
									<option value="Autobús">Autobús</option>
									<option value="Camioneta">Camioneta</option>
									<option value="Sprinter">Sprinter</option>
								</select>
							</div>
						</div>
						<div class="row mb-2">
							<div class="col-2">
								<label >Estatus:</label>
							</div>	
							<div class="col-5">			
								<select class="form-control" id="estatus_unidades" name="estatus_unidades">
									<option value="">Seleccione</option>
									<option value="Alta">Alta</option>
									<option value="Baja">Baja</option>
									<option value="Inactivo">Inactivo</option>
								</select>
							</div>
						</div>
						<div class="row mb-2">
							<div class="col-2">
								<label >Derrotero:</label>
							</div>	
							<div class="col-5">			
								<?php
									echo generar_select($link, "derroteros", "id_derroteros", "nombre_derroteros");
								?>
							</div>
						</div>
						<div class="row mb-2">
							<div class="col-2">
								<label for="nombre_propietario">Serie:</label>
							</div>	
							<div class="col-5">			
								<input class="form-control" type="text" name="serie" id="serie">
							</div>
						</div>
						<div class="row mb-2">
							<div class="col-2">
								<label for="nombre_propietario">Modelo:</label>
							</div>	
							<div class="col-5">			
								<input class="form-control" type="text" name="modelo" id="modelo">
							</div>
						</div>
						<div class="row mb-2">
							<div class="col-2">
								<label for="nombre_propietario">Poliza:</label>
							</div>	
							<div class="col-5">			
								<input class="form-control" type="text" name="poliza" id="poliza" required>
							</div>
						</div>
						<div class="row mb-2">
							<div class="col-2">
								<label for="nombre_propietario">Aseguradora:</label>
							</div>	
							<div class="col-5">			
								<input class="form-control" type="text" name="aseguradora" id="aseguradora">
							</div>
						</div>
						<div class="row mb-2">
							<div class="col-2">
								<label for="nombre_propietario">Vigencia:</label>
							</div>	
							<div class="col-5">			
								<input class="form-control" type="date" name="vigencia" id="vigencia">
							</div>
						</div>
						<div class="row mb-2">
							<div class="col-2">
								<label for="nombre_propietario">Mutualidad:</label>
							</div>	
							<div class="col-5">			
								<input class="form-control" type="number" name="mutualidad" id="mutualidad">
							</div>
						</div>
						<div class="row mb-2">
							<div class="col-2">
								<label for="nombre_propietario">Rin:</label>
							</div>	
							<div class="col-5">			
								<input class="form-control" type="text" name="rin" id="rin">
							</div>
						</div>
						<div class="row mb-2">
							<div class="col-2">
								<label for="nombre_propietario">Tipo de Aceite:</label>
							</div>	
							<div class="col-5">			
								<input class="form-control" type="text" name="tipo_aceite" id="tipo_aceite">
							</div>
						</div>
						<div class="row mb-2">
							<div class="col-2">
								<label for="nombre_propietario">Asientos:</label>
							</div>	
							<div class="col-5">			
								<input class="form-control" type="text" name="asientos" id="asientos">
							</div>
						</div>
						<div class="row mb-2">
							<div class="col-2">
								<label for="saldo_unidades">Saldo Inicial:</label> 
							</div>	
							<div class="col-5">			
								<input class="form-control" type="number"  name="saldo_unidades" id="saldo_unidades" value="0">
							</div>
						</div>
					</div>
					
					<!-- Modal footer -->
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal">
						<i class="fas fa-times"></i> Cancelar</button>
						<button type="submit" class="btn btn-success " >
						<i class="fas fa-save"></i> Guardar </button>
					</div>
					
				</form>		
				
				<!-- Sticky Footer -->
				<footer class="sticky-footer">
					<div class="container my-auto">
						<div class="copyright text-center my-auto">
							<span>Copyright © Glifo Media 2018</span>
						</div>
					</div>
				</footer>
				
			</div>
			<!-- /.content-wrapper -->
		</div>
		<!-- /#wrapper -->
		
		<!-- Scroll to Top Button-->
		<a class="scroll-to-top rounded" href="#page-top">
			<i class="fas fa-angle-up"></i>
		</a>		
		
		
		<!-- The Modal -->
		<
		
		
		<?php include("forms/form_unidades.php")?>
		<?php include("../../scripts.php")?>
		<script>
			$(document).ready(function(){
				listarRegistros();
				
				$('#form_edicion').submit( guardarRegistro );
				$('.nuevo').click( nuevoRegistro );
				$('#num_eco').blur( buscarUnidad );
				
			});
			
			function buscarUnidad(){
				
				$('#num_eco').addClass("cargando");
				var num_eco = $(this).val();
				
				$.ajax({
					url: '../../funciones/fila_select.php',
					method: 'GET',
					data: {
						tabla: "unidades",
						id_campo: "num_eco",
						id_valor: num_eco
						
					}
					}).done(function(respuesta){
					
					if(respuesta.count_rows > 0){
						alertify.error("Ya existe la unidad")
						$('#num_eco').focus();
					}
					
					}).always(function(){
					$('#num_eco').removeClass("cargando");
					
				});
			}
			
			function nuevoRegistro(event){
				
				$("#form_edicion")[0].reset();
				$('#modal_edicion').modal({ backdrop: 'static'}).modal('show').on('shown.bs.modal', function () {
					$('#form_edicion input:eq(1)').trigger("focus");
				});
			}
			function guardarRegistro(event){
				event.preventDefault();
				let datos = $(this).serializeArray();
				let boton = $(this).find(":submit");
				let icono = boton.find(".fas");
				
				boton.prop("disabled", true);
				icono.toggleClass("fa-save fa-spinner fa-spin");
				
				$.ajax({
					url: 'control/guardar.php',
					dataType: 'JSON',
					method: 'POST',
					data: {
						tabla: 'unidades',
						datos: datos
					}
				}).done(
				function(respuesta){
					boton.prop("disabled", false);
					icono.toggleClass("fa-save fa-spinner fa-spin");
					
					if(respuesta.estatus == "success"){ 
						alertify.success('Se ha agregado correctamente');
						$('#form_edicion')[0].reset();
						$('#modal_edicion').modal("hide");
						listarRegistros();
						}else{
						console.log(respuesta.mensaje);
					}
				});
			}
			
			//FUNCION DE ENLISTAR
			function listarRegistros() {
				console.log("listarRegistros")
				return $.ajax({
					url: 'control/listar_unidades.php',
					method: 'GET',
					data: $("#form_filtros").serialize()
					}).done(function(respuesta){
					
					$('#lista_registros').html(respuesta);
					$('#tabla_registros').DataTable({
            "language": {
							"url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
						}
					});
					
					//BOTON DE Editar
					$('.btn_editar').on('click', cargarRegistro);
					
				});
			}
			//FUNCION DE Cargar datos
			function cargarRegistro() {
				console.log("cargarRegistro()");
				var $boton = $(this);
				var id_registro= $(this).data("id_registro");
				
				$boton.prop("disabled", true);
				
				$.ajax({
					url: '../../funciones/fila_select.php',
					method: 'GET',
					data: {
						tabla: "unidades",
						id_campo: "id_unidades",
						id_valor: id_registro
						
					}
					}).done(function(respuesta){
					console.log("imprime registros")
					$boton.prop("disabled", false);
					
					$.each(respuesta.data, function(name , value){
						$("#form_edicion").find("#"+ name).val(value);
						
					});
					
					$("#modal_edicion").modal("show");
					// $('#lista_registros').html(respuesta);
					
				});
			}
			
		</script>
</body>
</html>
