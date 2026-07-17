<?php 
	include_once("../login/login_check.php");
	include("../../conexi.php");
	include("../../funciones/generar_select.php");
	
	$link = Conectarse();
	
	
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">
		<title>Usuarios</title>
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
							<a href="#">Administración</a>
						</li>
						<li class="breadcrumb-item active">Asistencia</li>
					</ol>
					
					
					<form id="form_filtros" >
						<div class="row mb-2">
							
							<div class="col-sm-2"> 
								<label>Fecha Inicial</label>
								<input class="form-control" type="date" name="fecha_inicial" value="<?php echo date("Y-m-d", strtotime("-3 days"))?>">
							</div>
							<div class="col-sm-2"> 
								<label>Fecha Final</label>
								<input   class="form-control" type="date" name="fecha_final" value="<?php echo date("Y-m-d", strtotime("-3 days"))?>">
							</div>
							<div class="col-sm-2"> 
								<label>Tipo</label>
								<select name="tipo_acceso" class="form-control">
									<option value="">Todos</option>
									<option value="ENTRADA">ENTRADA</option>
									<option value="SALIDA">SALIDA</option>
									
								</select>
							</div>
							<div class="col-sm-2"> 
								<label>Usuario</label>
								<?php echo generar_select($link, "empleados" ,"id_empleado","nombre_empleado", true)?>
							</div>
							<div class="form-group col-sm-2 pt-4"> 
								<button type="submit"  title="Buscar" class="btn btn-primary  d-print-none btn-sm ">
									<i class="fas fa-search"></i> Buscar
								</button>	
								
							</div>
						</div>
						
					</form>
					
					<hr>
					
					
					
					<div class="table-responsive" id="lista_registros">
						<h3 >Cargando...</h3>
						</div>
						
					</div>
					<!-- /.container-fluid -->
					
					
			</div>
			<!-- /.content-wrapper -->
		</div>
		<!-- /#wrapper -->
		
		<!-- Scroll to Top Button-->
		<a class="scroll-to-top rounded" href="#page-top">
			<i class="fas fa-angle-up"></i>
		</a>		
		
		
		<!-- The Modal -->
		
		
		
		<?php include("forms/form_usuarios.php")?>
		<?php include("../../scripts.php")?>
		<script src="js/asistencia.js?v=<?= date("dmYHi")?>" ></script>
		
	</body>
</html>
