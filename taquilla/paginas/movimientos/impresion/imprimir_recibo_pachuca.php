<?php 
	session_start();
	include('../../../conexi.php');
	include('../../../funciones/generar_select.php');
	include('../../../funciones/numero_a_letras.php');
	include('../../../funciones/console_log.php');
	$link = Conectarse();
	$filas = array();
	$respuesta = array();
	
	
	
	$consulta = "SELECT * FROM recibos_pachuca 
	LEFT JOIN empresas USING(id_empresas)
	LEFT JOIN beneficiarios USING(id_beneficiarios) 
	LEFT JOIN motivos_salida USING(id_motivosSalida) 
	LEFT JOIN usuarios USING(id_usuarios)
	WHERE id_reciboSalidas= '{$_GET['id_registro']}'";
	
	
	$result = mysqli_query($link,$consulta); 
	if($result){
		
		if( mysqli_num_rows($result) == 0){
			
			die("<div class='alert alert-danger'>Registro No Encontrado</div>");
			
			
		}
		
		while($fila = mysqli_fetch_assoc($result)){
		
			$filas = $fila ;
			
		}
		
	?> 
	<div class="media_carta">
		<div class="row">
			<div class="col-2 text-center" >
				<img  src="../../img/logo.jpg" class="img-fluid">
			</div>
			<div class="col-7 text-center">
				<h4>Coordinadora de Transporte Grupo AAZ AC</h4>
			<legend>Recibo de Salida</legend> 
			</div>
		</div>
		
		<div class="row">
			<div class="col-6">
				<h5>
					Empresa: <?php echo $filas["nombre_empresas"]?><br>
					Motivo: <?php echo $filas["nombre_motivosSalida"]?><br>
					Referencia: <?php echo $filas["referencia"]?><br>
				</h5>
			</div>	 
			<div class="col-6 text-right">	
				<h4>Folio: <?php echo $filas["id_reciboSalidas"]?></h4>
				<h5>
					Bueno por: $  <?php echo number_format($filas["monto_reciboSalidas"], 2)?><br>
					Fecha: <?php echo $filas["fecha_reciboSalidas"]?><br>
					
				</h5>
			</div>
		</div>
		<div class="row">
			<div class="col-12">
				<p>Recibi la cantidad de $<?=$filas["monto_reciboSalidas"]?>
					(<?php echo NumeroALetras::convertir($filas["monto_reciboSalidas"], 'PESOS', 'centavos')?>)
				</p>
				<br>
				<p>Por concepto de: <?php echo $filas["observaciones_reciboSalidas"];?></p>
			</div>	 
		</div>
		
		
		<div class="row text-center">
			<div class="col-4 ">
			</div>
			<div class="col-4 border-bottom">
			 <?php echo $filas["nombre_beneficiarios"];?>
			</div>
		</div>
		<div class="row text-center">
			<div class="col-4 ">
			</div>
			<div class="col-4 ">
			 Recibí
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-6 border-top">
				Impreso por: <?php echo $_COOKIE["nombre_usuarios"];?><br>
				Fecha Impresión: <?php echo date("Y-m-d h:i:s");?>
			</div>
			<div class="col-6 text-right">
				Creado por: <?php echo $filas["nombre_usuarios"];?><br>
				Fecha Creacion: <?php echo $filas["fecha_reciboSalidas"];?><br>
			</div>
		</div> 
	</div> 
	
	<br>
	<br>
	<br>
	<br>
	<hr>
	
	<div class="media_carta">
		<div class="row">
			<div class="col-2 text-center" >
				<img  src="../../img/logo.jpg" class="img-fluid">
			</div>
			<div class="col-7 text-center">
				<h4>Coordinadora de Transporte Grupo AAZ AC</h4>
			<legend>Recibo de Salida COPIA</legend> 
			</div>
		</div>
		 
		<div class="row">
			<div class="col-6">
				<h5>
					Empresa: <?php echo $filas["nombre_empresas"]?><br>
					Motivo: <?php echo $filas["nombre_motivosSalida"]?><br>
					Referencia: <?php echo $filas["referencia"]?><br>
				</h5>
			</div>	 
			<div class="col-6 text-right">	
				<h4>Folio: <?php echo $filas["id_reciboSalidas"]?></h4>
				<h5>
					Bueno por: $  <?php echo number_format($filas["monto_reciboSalidas"], 2)?><br>
					Fecha: <?php echo $filas["fecha_reciboSalidas"]?><br>
					
				</h5>
			</div>
		</div>
		<div class="row">
			<div class="col-12">
				<p>Recibi la cantidad de $<?=$filas["monto_reciboSalidas"]?>
					(<?php echo NumeroALetras::convertir($filas["monto_reciboSalidas"], 'PESOS', 'centavos')?>)
				</p>
				<br>
				<p>Por concepto de: <?php echo $filas["observaciones_reciboSalidas"];?></p>
			</div>	 
		</div>
		<div class="row text-center">
			<div class="col-4 ">
			</div>
			<div class="col-4 border-bottom">
			 <?php echo $filas["nombre_beneficiarios"];?>
			</div>
		</div>
		<div class="row text-center">
			<div class="col-4 ">
			</div>
			<div class="col-4 ">
			 Recibí
			</div>
		</div>
		<br>
		
		<div class="row">
			<div class="col-6 border-top">
				Impreso por: <?php echo $_COOKIE["nombre_usuarios"];?><br>
				Fecha Impresión: <?php echo date("Y-m-d h:i:s");?>
			</div>
			<div class="col-6 text-right">
				Creado por: <?php echo $filas["nombre_usuarios"];?><br>
				Fecha Creacion: <?php echo $filas["fecha_reciboSalidas"];?><br>
			</div>
		</div> 
	</div> 
	
	
	
	
	<?php    
		
		
	}
	else {
		echo "Error en ".$consulta.mysqli_Error($link);
		
	}
	
	
?>																																				