<?php 
	include('../../conexi.php');
	include('../../funciones/generar_select.php');
	include("../../paginas/login/login_check.php");
	$nombre_pagina = "Importes por Usuario";
	$link = Conectarse();
	$filas = array();
	$respuesta = array();
	$totales = array_fill (  0 ,  10 , 0 ); //Llena el array totales con 0s
	
	
	
	$consulta = "SELECT * FROM abonos_unidades 
	
	LEFT JOIN recaudaciones USING(id_recaudaciones)
	LEFT JOIN tarjetas USING(tarjeta)
	LEFT JOIN conductores  USING(id_conductores)
	LEFT JOIN unidades USING(id_unidades)
	LEFT JOIN usuarios ON abonos_unidades.id_usuarios = usuarios.id_usuarios 
	LEFT JOIN empresas ON empresas.id_empresas = tarjetas.id_empresas
	WHERE abonos_unidades.id_usuarios = {$_GET["id_usuarios"]}
	AND estatus_abonos <> 'Cancelado'
	AND abonos_unidades.id_administrador = '{$_SESSION["id_administrador"]}'
	";
	
  
	$consulta.=  " AND  DATE(fecha_abonos) BETWEEN '{$_GET['fecha_inicial']}' AND '{$_GET['fecha_final']}'";
	
	$result = mysqli_query($link,$consulta);
	if($result){
		
		if( mysqli_num_rows($result) == 0){
			
			// die("<div class='alert alert-danger'>No hay registros</div>");
			
			
		}
		
		while($fila = mysqli_fetch_assoc($result)){
			// console_log($fila);
			$filas[] = $fila ;
			
			
		}
		
	?>
	
	<pre hidden >
		<?php echo session_status();?>
	</pre>
	
	<?php
		
		
	}
	else {
		echo  "Error en ".$consulta.mysqli_Error($link);
	}
	
?>	

<?php
	
	
?>
<!DOCTYPE html>
<html lang="es_mx">
	<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?php echo $nombre_pagina;?></title>
		<?php include('../../styles.php')?>
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
							<a href="#">Recaudación</a>
						</li>
						<li class="breadcrumb-item active"><?php echo $nombre_pagina;?></li>
					</ol>
					
					
					<button type="button" id="btn_imprimir" onclick="window.print()" title="Imprimir" class="btn btn-primary mb-2">
						<i class="fas fa-print"></i> Imprimir
					</button>
					
					
					<div class="card mb-3" id="">
						<div class="card-header">
							<i class="fas fa-table"></i>
							<?php echo "Abonos de Unidades del usuario  {$_GET["nombre_usuarios"]} del {$_GET["fecha_inicial"]} al {$_GET["fecha_final"]} ";?>  
						</div>
						<div class="card-body">
							<div class="table-responsive" id="tabla_registros">
								<table class="table table-bordered table-condensed" id="dataTable" width="100%" cellspacing="0">
									<thead>
										<tr>
											<th>Folio</th>
											<th>Fecha Creación</th>
											<th>Tarjeta</th>
											<th>Fecha Cuenta</th>
											<th>Recaudacion</th>
											<th>Empresa</th>
											<th>Unidad</th>
											<th>Conductor</th>
											<th>Cuenta</th>
											<th>Condonacion</th>
											<th>Total Cuenta</th>
											<th>Termicos</th>
											<th>Tijera</th>
											<th>Total Boletos</th>
											<th>Efectivo</th>
											<th>Total Recaudado</th>
											<th>Abono Unidad</th>
											<th>Devolución</th>
											<th>Usuario</th>
											<th>Estatus</th>
										</tr>
									</thead>
									<tbody id="tabla_DB">
										<?php 
											foreach($filas as $index=>$fila){
												if($fila["estatus_abonos"] != "Cancelado"){
													$totales[0]+= $fila["cuenta"];
													$totales[1]+= $fila["condonacion"];
													$totales[2]+= $fila["cuenta"] - $fila["condonacion"];
													$totales[3]+= $fila["bol_termicos_importe"];
													$totales[4]+= $fila["importe_tijera"];
													$totales[5]+= $fila["total_boletos"];
													$totales[6]+= $fila["efectivo"];
													$totales[7]+= $fila["total_recaudado"];
												$totales[8]+= $fila["abono_unidad"];
												$totales[9]+= $fila["devolucion"];
												}
												
												?>
												<tr>
													
													<td><?php echo $fila["id_abonos_unidades"]?></td>
													<td><?php echo $fila["fecha_abonos"]?></td>
													<td><?php echo $fila["tarjeta"]?></td>
													<td><?php echo $fila["fecha_tarjetas"]?></td>
													<td><?php echo $fila["nombre_recaudaciones"]?></td>
													<td><?php echo $fila["nombre_empresas"]?></td>
													<td><?php echo $fila["num_eco"]?></td>
													<td><?php echo $fila["nombre_conductores"]?></td>
													<td><?php echo $fila["cuenta"]?></td>
													<td><?php echo $fila["condonacion"]?></td>
													<td><?php echo $fila["cuenta"] - $fila["condonacion"]?></td>
													<td><?php echo $fila["bol_termicos_importe"]?></td>
													<td><?php echo $fila["importe_tijera"]?></td>
													<td><?php echo $fila["total_boletos"]?></td>
													<td><?php echo $fila["efectivo"]?></td>
													<td><?php echo $fila["total_recaudado"]?></td>
													<td><?php echo $fila["abono_unidad"]?></td>
													<td><?php echo $fila["devolucion"]?></td>
													<td><?php echo $fila["nombre_usuarios"]?></td>
													<td><?php echo $fila["estatus_abonos"]?></td>
												</tr>
												<?
												}
											?>
										</tbody>
										<tfoot>
											<tr>
												
												<td></td> 
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<?php
													foreach($totales as $i =>$total){
													?>
													<td class="h6"><?php echo number_format($total)?></td>
													<?php	
													}
												?>
												
											</tr>
										</tfoot>
									</table>
								</div>
								
							</div>
							
							<!--<div class="card-footer small text-muted">Ultima Modificación Ayer 12pm</div>-->
						</div>
					</div>
					
				</div> 
				<!-- /.content-wrapper -->
			</div>
			<!-- /#wrapper -->
			
			<!-- Scroll to Top Button-->
			<a class="scroll-to-top rounded d-print-none" href="#page-top">
				<i class="fas fa-angle-up"></i>
			</a>
			
			
			<?php include("../../scripts.php")?>
			
		</body>
	</html>
