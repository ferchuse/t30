<?php 
	session_start();
	include('../../../conexi.php');
	include('../../../funciones/generar_select.php');
	include('../../../funciones/numero_a_letras.php');
	include('../../../funciones/console_log.php');
	$link = Conectarse();
	$filas = array();
	$respuesta = array();
	
	
	
	$consulta = "SELECT * FROM traspasos_utilidad 
	LEFT JOIN traspasos_utilidad_unidades 
	USING(id_traspaso)
	LEFT JOIN unidades USING(num_eco)
	LEFT JOIN propietarios USING(id_propietarios)
	LEFT JOIN beneficiarios USING(id_beneficiarios)
	LEFT JOIN usuarios USING(id_usuarios)
	WHERE id_traspaso IN ({$_GET['folios']})";
	
	
	$result = mysqli_query($link,$consulta); 
	if($result){
		
		if( mysqli_num_rows($result) == 0){
			
			die("<div class='alert alert-danger'>No encontrada</div>");
			
			
		}
		
		while($fila = mysqli_fetch_assoc($result)){
			
			$filas[] = $fila ;
			
		}
		
	?> 
	<div class="media_carta">
		<div class="row">
			<div class="col-12 text-center" >
				<img  hidden 
				src="../../img/<?php echo $empresa["logo"]?>" class="img-fluid">
			</div>
			<div class="col-12 text-center">
				<h4><?php// echo $filas["nombre_empresas"]?></h4>
			</div>
		</div>
		
		<legend>Traspaso de Utilidad</legend> 
		
		<div class="row">
			<div class="col-6">
				<h5 hidden>
					Referencia Bancaria: <?php echo $filas[0]["referencia_bancaria"]?><br>
				</h5>
			</div>	 
			<div class="col-6 text-right">	
				<h4>Folio: <?php echo $filas[0]["id_traspaso"]?></h4>
				<h5>
					Fecha Aplic: <?php echo date("d-m-Y" , strtotime($filas[0]["fecha_aplicacion"]))?><br>
				</h5>
			</div>
		</div>
		
		<legend>Unidades</legend>
		<?php 
		foreach($filas as $i => $item){ ?>
			<div class="row text-center">
				<div class="col-4">
					<?php echo $item["num_eco"]?>
				</div>	
				<div class="col-4">
					<?php echo $item["nombre_propietarios"]?>
				</div>	
				<div class="col-4">
					$<?php echo number_format($item["monto"],2)?>
				</div>	 
			</div>
			<?php 	
			}
		?>
		
		<div class="row">
			<div class="col-12 text-right">
				<b>Monto:</b> $<?php echo $filas[0]["importe_traspaso"];?>
				<p>( <?php echo NumeroALetras::convertir($filas[0]["importe_traspaso"], 'PESOS', 'CENTAVOS')." 00/100 M.N."?></p><br>
				
			</div>	
		</div>
		<div class="row">
			<div class="col-12 text-left">
				<p>Concepto: <?php echo $filas[0]["observaciones"];?></p>
				<p>Forma de Pago: <?php echo $filas[0]["forma_pago"];?></p>
			</div>	
		</div>
		<br>
		<div class="row text-center">
			<div class="col-4 ">
			</div>
			<div class="col-4  border-top text-center">
				BENEFICIARIO<br>
				<?php echo $filas[0]["nombre_beneficiarios"];?>
			</div>
			
		</div>
		<br>
		<div class="row">
			<div class="col-6 border-top text-center">
				AUTORIZA<br>
					
			</div>
			<div class="col-6 text-center">
				ENTREGA: <br><?php echo $filas[0]["nombre_usuarios"];?><br>
				Fecha Elaboracion: <?php echo $filas[0]["fecha_traspaso"];?><br>
			</div>
		</div> 
	</div> 
	
	<br>
	<div class="media_carta">
		<div class="row">
			<div class="col-12 text-center" >
				<img  hidden 
				src="../../img/<?php echo $_SESSION["logo"]?>" class="img-fluid">
			</div>
			<div class="col-12 text-center">
				<h4><?php// echo $filas["nombre_empresas"]?></h4>
			</div>
		</div>
		
		<legend>Traspaso de Utilidad</legend> 
		
		<div class="row">
			<div class="col-6">
				<h5 class="d-none">
					Referencia Bancaria: <?php echo $filas[0]["referencia_bancaria"]?><br>
				</h5>
			</div>	 
			<div class="col-6 text-right">	
				<h4>Folio: <?php echo $filas[0]["id_traspaso"]?></h4>
				<h5>
					Fecha Aplic: <?php echo date("d-m-Y" , strtotime($filas[0]["fecha_aplicacion"]))?><br>
				</h5>
			</div>
		</div>
		
		<legend>Unidades</legend>
		<?php foreach($filas as $i => $item){ ?>
			<div class="row text-center">
				<div class="col-4">
					<?php echo $item["num_eco"]?>
				</div>	
				<div class="col-4">
					<?php echo $item["nombre_propietarios"]?>
				</div>	
				<div class="col-4 text-right">
					$<?php echo number_format($item["monto"],2)?>
				</div>	 
			</div>
			<?php 	
			}
		?>
		 
		<div class="row">
			<div class="col-12 text-right">
				<b>Monto:</b> $<?php echo $filas[0]["importe_traspaso"];?>
				<p>( <?php echo NumeroALetras::convertir($filas[0]["importe_traspaso"], 'PESOS', 'CENTAVOS')." 00/100 M.N."?></p><br>
				
			</div>	 
		</div>
		<div class="row">
			<div class="col-12 text-left">
				<p>Concepto: <?php echo $filas[0]["observaciones"];?></p>
				<p>Forma de Pago: <?php echo $filas[0]["forma_pago"];?></p>
			</div>	
		</div>
		<br>
		
		<div class="row text-center">
			<div class="col-12 border-top text-center">
				BENEFICIARIO<br>
				<?php echo $filas[0]["nombre_beneficiarios"];?>
			</div>
			
		</div>
		<br>
	
		<div class="row">
			<div class="col-6 border-top text-center">
				AUTORIZA<br>
				
			</div>
			<div class="col-6 text-center">
				ENTREGA: <br><?php echo $filas[0]["nombre_usuarios"];?><br>
				Fecha Elaboracion: <?php echo $filas[0]["fecha_traspaso"];?><br>
			</div>
		</div> 
	</div> 
	
	
	
	
	<?php    
		
		
	}
	else {
		echo "Error en ".$consulta.mysqli_Error($link);
		
	}
	
	
?>																																					