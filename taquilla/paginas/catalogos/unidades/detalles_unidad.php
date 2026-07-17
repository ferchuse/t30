<?php 
	session_start();
	require('../../../conexi.php');
	
	$link = Conectarse();
	$filas = array();
	
	
	$consulta = "SELECT * FROM unidades 
	LEFT JOIN empresas USING(id_empresas)
	LEFT JOIN propietarios USING(id_propietarios)
	LEFT JOIN derroteros USING(id_derroteros)
	WHERE num_eco= '{$_GET['num_eco']}'";
	
	
	$result = mysqli_query($link,$consulta); 
	if($result){
		
		if( mysqli_num_rows($result) == 0){
			
			die("<div class='alert alert-danger'>No encontrada</div>");
			
			
		}
		
		while($fila = mysqli_fetch_assoc($result)){
			
			$unidad = $fila ;
			
		}
		
	?> 
	
	<DOCTYPE html>
		<html lang="es_mx">
			<head>
				<meta charset="utf-8">
				<meta http-equiv="X-UA-Compatible" content="IE=edge">
				<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
				<meta name="description" content="">
				<meta name="author" content="">
				<title>Datos de Unidad</title>
				<?php include('../../../styles.php')?>
			</head>
			<body id="page-top">
				
				
				<div id="content-wrapper">		
					<div class="container">		
						
						<div class="media_carta">
							<div class="row">
								<div class="col-3 text-center" >
									<img  src="../../../img/logo.jpg" class="img-fluid">
								</div>
								<div class="col-7 text-center">
									<h4>Coordinadora de Transporte Grupo AAZ AC</h4>
									<legend>Datos de Unidad</legend> 
								</div>
							</div>
							
							
							<div class="row">
								<div class="col-12 col-sm-8" >
									
									<div class="row mb-2"><div class="col-sm-5 col-12">
										<label >Empresa:</label>
									</div>	 
									<div class="col-sm-7 col-12">			
										<input class="form-control" value="<?= $unidad["nombre_empresas"]?>" readonly>
										
									</div>
									</div>
									<div class="row mb-2"><div class="col-sm-5 col-12">
										<label >No Eco:</label>
									</div>	
									<div class="col-sm-7 col-12">			
										<input class="form-control" value="<?= $unidad["num_eco"]?>" readonly>
										
									</div>
									</div>
									<div class="row mb-2">
										<div class="col-sm-5 col-12">
											<label >Propietario:</label>
										</div>	
										<div class="col-sm-7 col-12">			
											
											<input class="form-control" value="<?= $unidad["nombre_propietarios"]?>" readonly>
										</div>
									</div>
									<div class="row mb-2">
										<div class="col-sm-5 col-12">
											<label >Derrotero:</label>
										</div>	
										<div class="col-sm-7 col-12">			
											
											<input class="form-control" value="<?= $unidad["nombre_derroteros"]?>" readonly>
										</div>
									</div>
									<div class="row mb-2"><div class="col-sm-5 col-12">
										<label for="fecha_ingreso">Fecha de Ingreso:</label>
									</div>	
									<div class="col-sm-7 col-12">			
										<input class="form-control" type="date" value="<?= $unidad["fecha_ingreso"]?>" readonly>
										
									</div>
									</div>
									<div class="row mb-2"><div class="col-sm-5 col-12">
										<label for="censo">Censo:</label>
									</div>	
									<div class="col-sm-7 col-12">			
										<input class="form-control" value="<?= $unidad["censo"]?>" readonly>
									</div>
									</div>
									<div class="row mb-2"><div class="col-sm-5 col-12">
										<label for="concesion">Concesión:</label>
									</div>	
									<div class="col-sm-7 col-12">			
										<input class="form-control" value="<?= $unidad["concesion"]?>" readonly>
									</div>
									</div>
									<div class="row mb-2">
										<div class="col-sm-5 col-12">
											<label for="tel_unidades">Teléfono:</label>
										</div>	
										<div class="col-sm-7 col-12">			
											<input class="form-control" type="tel" value="<?= $unidad["tel_unidades"]?>" readonly>
										</div>
									</div>
									<div class="row mb-2">
										<div class="col-sm-5 col-12">
											<label for="tel_unidades">Num Cuenta:</label>
										</div>	
										<div class="col-sm-7 col-12">			
											<input class="form-control" value="<?= $unidad["num_cuenta"]?>" readonly>
										</div>
									</div>
									<div class="row mb-2">
										<div class="col-sm-5 col-12">
											<label for="placas">Placas:</label>
										</div>	
										<div class="col-sm-7 col-12">			
											<input class="form-control" value="<?= $unidad["placas"]?>" readonly>
										</div>
									</div>
									<div class="row mb-2">
										<div class="col-sm-5 col-12">
											<label >Tipo Vehículo:</label>
										</div>	
										<div class="col-sm-7 col-12">			
											<input class="form-control" value="<?= $unidad["tipo_unidad"]?>" readonly>
										</div>
									</div>
									<div class="row mb-2">
										<div class="col-sm-5 col-12">
											<label >Estatus:</label>
										</div>	
										<div class="col-sm-7 col-12">			
											<input class="form-control" value="<?= $unidad["estatus_unidades"]?>" readonly>
										</div>
									</div>
									
									<div class="row mb-2">
										<div class="col-sm-5 col-12">
											<label for="serie">Serie:</label>
										</div>	
										<div class="col-sm-7 col-12">			
											<input class="form-control" value="<?= $unidad["serie"]?>" readonly>
											
										</div>
									</div>
									<div class="row mb-2">
										<div class="col-sm-5 col-12">
											<label for="motor">Motor:</label>
										</div>	
										<div class="col-sm-7 col-12">			
											<input class="form-control" value="<?= $unidad["motor"]?>" readonly>
											
										</div>
									</div>
									<div class="row mb-2">
										<div class="col-sm-5 col-12">
											<label for="nombre_propietario">Modelo:</label>
										</div>	
										<div class="col-sm-7 col-12">			
											<input class="form-control" value="<?= $unidad["modelo"]?>" readonly>
											
										</div>
									</div>
									<div class="row mb-2">
										<div class="col-sm-5 col-12">
											<label for="nombre_propietario">Poliza:</label>
										</div>	
										<div class="col-sm-7 col-12">			
											<input class="form-control" value="<?= $unidad["poliza"]?>" readonly>
											
										</div>
									</div>
									<div class="row mb-2">
										<div class="col-sm-5 col-12">
											<label for="nombre_propietario">Aseguradora:</label>
										</div>	
										<div class="col-sm-7 col-12">			
											<input class="form-control" value="<?= $unidad["aseguradora"]?>" readonly>
											
										</div>
									</div>
									<div class="row mb-2">
										<div class="col-sm-5 col-12">
											<label for="nombre_propietario">Vigencia:</label>
										</div>	
										<div class="col-sm-7 col-12">			
											<input class="form-control" type="date" value="<?= $unidad["vigencia"]?>" readonly>
											
										</div>
									</div>
									<div class="row mb-2">
										<div class="col-sm-5 col-12">
											<label for="nombre_propietario">Mutualidad:</label>
										</div>	
										<div class="col-sm-7 col-12">			
											<input class="form-control" value="<?= $unidad["mutualidad"]?>" readonly>
											
										</div>
									</div>
									
									<div class="col-12 text-center">
										
										Fecha de Impresión: <?php echo date("d/m/Y H:i:s");?><br>
									</div>
								</div>
							</div> 
						</div> 
					</div> 
				</div> 
			</div> 
			
			<?php include("../../scripts.php")?>
		</body>
	</html>	
	
	
	<?php    
	}
	else {
		echo "Error en ".$consulta.mysqli_Error($link);
		
	}
	
	
?>																																																		