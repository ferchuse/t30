<?php 
	include('../../conexi.php');
	include('../../funciones/generar_select.php');
	include("../../paginas/login/login_check.php");
	$nombre_pagina = "Importes por Usuario";
	$link = Conectarse();
	$filas = array();
	$respuesta = array();
	$totales = array_fill (  0 ,  1 , 0 ); //Llena el array totales con 0s
	
	
	
	$consulta = "SELECT * FROM abono_general 
	LEFT JOIN unidades USING (id_unidades) 
	LEFT JOIN empresas USING (id_empresas) 
	LEFT JOIN propietarios USING (id_propietarios)
	LEFT JOIN derroteros USING (id_derroteros) 
	LEFT JOIN motivosAbonoUnidades USING (id_motivosAbono) 
	LEFT JOIN usuarios USING (id_usuarios)
	
	WHERE abono_general.id_usuarios = {$_GET["id_usuarios"]}
	AND estatus_abono <> 'Cancelado'
	";
	
  
	$consulta.=  " AND  DATE(fecha_abonogeneral) BETWEEN '{$_GET['fecha_inicial']}' AND '{$_GET['fecha_final']}'";
	
	$result = mysqli_query($link,$consulta);
	if($result){
		
		if( mysqli_num_rows($result) == 0){
			
			// die("<div class='alert alert-danger'>No hay registros</div>");
			
		}
		
		while($fila = mysqli_fetch_assoc($result)){
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
							<?php echo "Abonos General del usuario  {$_GET["nombre_usuarios"]} del {$_GET["fecha_inicial"]} al {$_GET["fecha_final"]} ";?>  
						</div>
						<div class="card-body">
							<div class="table-responsive" id="tabla_registros">
								<table class="table table-bordered table-condensed" id="dataTable" width="100%" cellspacing="0">
									<thead>
										<tr>
											<th>Folio</th>
											<th>Fecha Abono</th>
											<th>Empresa</th>
											<th>Unidad</th>
											<th>Motivo</th>
											<th>Monto</th>
											<th>Usuario</th>
											<th>Estatus</th>
										</tr>
									</thead>
									<tbody id="tabla_DB">
										<?php 
											foreach($filas as $index=>$fila){
												if($fila["estatus_abonos"] != "Cancelado"){
													$totales[0]+= $fila["monto_abonogeneral"];
												}
											?>
											<tr>
												<td><?php echo $fila["id_abonogeneral"]?></td>
												<td><?php echo $fila["fecha_abonogeneral"]?></td>
												<td><?php echo $fila["nombre_empresas"]?></td>
												<td><?php echo $fila["num_eco"]?></td>
												<td><?php echo $fila["nombre_motivosAbono"]?></td>
												<td><?php echo $fila["monto_abonogeneral"]?></td>
												<td><?php echo $fila["nombre_usuarios"]?></td>
												<td><?php echo $fila["estatus_abono"]?></td>
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
