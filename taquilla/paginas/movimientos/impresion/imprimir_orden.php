<?php 
	include('../../../conexi.php');
	include('../../../funciones/numero_a_letras.php');
	include('../../../funciones/console_log.php');
	$link = Conectarse();
	$filas = array();
    $respuesta = array();
    
    $fecha_trabajo = $_POST['fecha_trabajo'];
    $num = $_POST['num_eco'];

	$tabla = $_POST['tabla'];
    $group = $_POST['group'];
    $subconsulta = $_POST['subconsulta'];
    $campo = $_POST['campo'];
    $id_campo = $_POST['id_campo'];

    $consulta = "SELECT * FROM $tabla $subconsulta WHERE $campo=$id_campo GROUP BY $group";
    
	
	
    $result = mysqli_query($link,$consulta);
    
	if($result){
		
		if( mysqli_num_rows($result) == 0){
			
			die("<div class='alert alert-danger'>No encontrado</div>");
			
			
		}
		
		while($fila = mysqli_fetch_assoc($result)){
			console_log($fila);
			$filas = $fila ;
			//TODO
			///Tarjeta Cancelada
			///Tarjeta Ya recaudada
			
		}
		
	?> 
	<div class="media_carta">
		<div class="row">
			<div class="col-12 text-center" >
				<img  hidden 
				src="../../img/amt.jpg" class="img-fluid">
			</div>
			<div class="col-12 text-center">
				<h4>CORDINADORA DE TRANSPORTE GRUPO AAZ A.C.</h4>
			</div>
		</div>
		<hr>
		<legend class="text-center">ÓRDEN DE TRABAJO</legend> 
		
		<div class="row">
			<div class="col-12 text-center">
				<p>YO TITULAR DE LA UNIDAD AUTORIZO TRABAJAR <?php echo $num;?> DIAS EN LAS UNIDADES (<?php echo $filas["num_eco"];?>)</p>
                <p>NOMBRE EN LA RUTA () A PARTIR DEL FECHA AL <?php echo $fecha_trabajo;?></p>
			</div>	 
		</div>
        <legend>LICENCIA: </legend>
        <legend>NUM.LIC: <?php echo $filas['noLicencia_conductores'];?> <p>TEL:</p></legend>
        <div class="row">
            <div class="col-5">
                <p>TITULAR:NOMBRE DEL TITULAR</p>
                <p>TITULAR:LIC.NOMBRE DEL LIC</p>
            </div>
        </div>
	</div>
<?php    
	}
	else {
		echo "Error en ".$consulta.mysqli_Error($link);
		
	}
?>																																				