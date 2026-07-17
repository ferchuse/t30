<?php
	$nombre_pagina = "abonar_unidades";
	$id= "id_abonarunidades";
	$tabla = "abonar_unidades"; 
	
	include("../../conexi.php");
	include("../../funciones/generar_select.php");
	include("../../paginas/login/login_check.php");
	$link = Conectarse();
	$fecha_inicial = date("Y-m-01");
	
	
	
	
	$consulta= 
	"
	SELECT 
	1 AS orden,
	'{$_GET["fecha_inicial"]}' AS fecha,
	'SALDO ANTERIOR' AS motivo,
	0 AS cargo,
	COALESCE(total_boletos,0) +
	COALESCE(total_abonos,0) - 
	COALESCE(total_cargos,0) - 
	COALESCE(total_cargos_fijos,0) - 
	COALESCE(total_gastos,0) -
	COALESCE(total_traspasos,0)  -
	COALESCE(total_casetas,0)  -
	COALESCE(total_comision_tarjetas,0)  
	AS abono,
	'' AS observaciones
	
	FROM 
	
	(
	SELECT '{$_GET["num_eco"]}' AS num_eco
	) AS t_num_eco 
	
	LEFT JOIN
	(
	SELECT num_eco, SUM(total) AS total_boletos 
	FROM boletos 
	WHERE num_eco = '{$_GET["num_eco"]}'
	AND DATE(fecha_boletos) < '{$_GET["fecha_inicial"]}'
	AND estatus_boletos = 'Activo'
	) AS t_boletos  USING (num_eco)
	
	LEFT JOIN (
	SELECT num_eco, SUM(monto) AS total_cargos
	FROM cargos 
	WHERE num_eco = '{$_GET["num_eco"]}'
	AND DATE(fecha_cargos) < '{$_GET["fecha_inicial"]}'
	AND estatus = 'Activo'
	) AS t_cargos
	USING(num_eco)
	
	LEFT JOIN (
	SELECT num_eco, SUM(monto) AS total_cargos_fijos
	FROM cargos_fijos
	WHERE num_eco = '{$_GET["num_eco"]}'
	AND DATE(fecha_cargos) < '{$_GET["fecha_inicial"]}'
	AND estatus = 'Activo'
	) AS t_cargos_fijos
	USING(num_eco)
	
	LEFT JOIN (
	SELECT num_eco, SUM(importe) AS total_gastos
	FROM gastos_corrida 
	LEFT JOIN boletos
	USING(id_boletos)
	WHERE num_eco = '{$_GET["num_eco"]}'
	AND DATE(fecha_gastos) < '{$_GET["fecha_inicial"]}'
	AND estatus_gastos = 'Activo'
	#AND estatus_boletos= 'Activo'
	) AS t_gastos
	USING(num_eco)
	
	LEFT JOIN (
	SELECT num_eco, SUM(monto) AS total_traspasos
	FROM traspasos_utilidad
	LEFT JOIN traspasos_utilidad_unidades USING(id_traspaso)
	WHERE num_eco = '{$_GET["num_eco"]}'
	AND DATE(fecha_aplicacion) < '{$_GET["fecha_inicial"]}'
	AND estatus_traspaso = 'Activo'
	) AS t_traspasos
	USING(num_eco)
	
	LEFT JOIN (
	SELECT num_eco, SUM(monto) AS total_abonos
	FROM recibos_entradas
	WHERE num_eco = '{$_GET["num_eco"]}'
	AND DATE(fecha_aplicacion) < '{$_GET["fecha_inicial"]}'
	AND estatus_deposito = 'Activo'
	) AS t_abonos
	USING(num_eco)
	
	LEFT JOIN (
	SELECT num_eco, SUM(importe) AS total_casetas
	FROM casetas_tag
	LEFt JOIN unidades USING(tag)
	WHERE num_eco = '{$_GET["num_eco"]}'
	AND DATE(fecha_viaje) < '{$_GET["fecha_inicial"]}'
	) AS t_casetas_anterior
	USING(num_eco)
	
	LEFT JOIN (
	SELECT
	num_eco,
	SUM(tarjeta) * 0.04  AS total_comision_tarjetas
	FROM boletos
	WHERE num_eco = '{$_GET["num_eco"]}'
	AND DATE(fecha_boletos) < '{$_GET["fecha_inicial"]}'
	AND DATE(fecha_boletos) > '2023-11-30'
	AND estatus_boletos = 'Activo'
	) as t_comision_tarjeta_anterior
	USING (num_eco)
	
	
	
	
	#######################SALDO NUEVO########################
	
	
	
	UNION
	
	SELECT
	2 AS orden,
	fecha_boletos AS fecha, 
	CONCAT(
	'BOLETO #',
	id_boletos
	)  AS motivo,
	0 AS cargo,
	total AS abono,
	destino AS observaciones
	FROM
	boletos
	WHERE
	num_eco = '{$_GET['num_eco']}' 
	AND estatus_boletos = 'Activo'
	AND DATE(fecha_boletos) BETWEEN '{$_GET["fecha_inicial"]}' AND '{$_GET["fecha_final"]}'
	
	UNION 
	
	SELECT
	3 AS orden,
	DATE(fecha_cargos) AS fecha, 
	CONCAT('Cargo por ', concepto ) AS motivo,
	monto AS cargo,
	0 AS abono,
	'' AS observaciones
	FROM
	cargos_fijos
	WHERE
	num_eco = '{$_GET['num_eco']}' 
	AND estatus = 'Activo'
	AND DATE(fecha_cargos) BETWEEN '{$_GET["fecha_inicial"]}' AND '{$_GET["fecha_final"]}'
	
	UNION
	
	SELECT
	3 AS orden,
	DATE(fecha_cargos) AS fecha, 
	CONCAT('Cargo por ', concepto ) AS motivo,
	monto AS cargo,
	0 AS abono,
	'' AS observaciones
	FROM
	cargos
	WHERE
	num_eco = '{$_GET['num_eco']}' 
	AND estatus = 'Activo'
	AND DATE(fecha_cargos) BETWEEN '{$_GET["fecha_inicial"]}' AND '{$_GET["fecha_final"]}'
	
	
	UNION
	
	SELECT 
	4 AS orden,
	DATE(fecha_gastos) AS fecha, 
	CONCAT('Gasto #', id_gastos , 
	' ', descripcion_gastos ) AS motivo, 
	importe AS cargo,
	0 AS abono,
	'' AS observaciones
	FROM
	gastos_corrida
	LEFT JOIN boletos USING(id_boletos)
	LEFT JOIN cat_gastos USING(id_cat_gastos)
	WHERE
	num_eco = '{$_GET['num_eco']}' 
	AND DATE(fecha_gastos) BETWEEN '{$_GET["fecha_inicial"]}' AND '{$_GET["fecha_final"]}'
	AND estatus_gastos = 'Activo'
	#AND estatus_boletos= 'Activo'
	
	
	UNION
	
	SELECT
	5 AS orden,
	DATE(fecha_aplicacion) AS fecha,
	CONCAT(
	'Abono #',
	id_deposito
	) AS motivo,
	0 AS cargo,
	monto AS abono,
	'' AS observaciones
	FROM
	recibos_entradas
	WHERE
	num_eco = '{$_GET['num_eco']}' 
	AND DATE(fecha_aplicacion) BETWEEN '{$_GET["fecha_inicial"]}' AND '{$_GET["fecha_final"]}'
	AND estatus_deposito = 'Activo'
	
	
	UNION
	
	
	SELECT
	6 AS orden,
	DATE(fecha_aplicacion) AS fecha,
	CONCAT(
	'Traspaso #',
	id_traspaso
	) AS motivo,
	monto AS cargo,
	0 AS abono,
	observaciones
	FROM
	traspasos_utilidad
	LEFT JOIN traspasos_utilidad_unidades USING (id_traspaso)
	WHERE
	num_eco = '{$_GET['num_eco']}' 
	AND DATE(fecha_aplicacion) BETWEEN '{$_GET["fecha_inicial"]}' AND '{$_GET["fecha_final"]}'
	AND estatus_traspaso <> 'Cancelado'
	
	UNION
	
	SELECT
	7 AS orden,
	DATE(fecha_viaje) AS fecha,
	'Casetas TELEVIA ' AS motivo,
	SUM(importe) AS cargo,
	0 AS abono,
	'' as observaciones
	FROM
	casetas_tag
	LEFT JOIN unidades USING (tag)
	WHERE
	num_eco = '{$_GET['num_eco']}' 
	AND DATE(fecha_viaje) BETWEEN '{$_GET["fecha_inicial"]}' AND '{$_GET["fecha_final"]}'
	
	GROUP BY DATE(fecha_viaje)
	
	
	
	UNION
	
	SELECT
	3.1 AS orden,
	fecha_boletos AS fecha, 
	CONCAT(
	'COMISION TARJETA #',
	id_boletos
	)  AS motivo,
	tarjeta * 0.04 AS cargo,
	0 AS abono,
	'' as observaciones
	FROM
	boletos
	WHERE
	num_eco = '{$_GET['num_eco']}' 
	AND estatus_boletos = 'Activo'
	AND tarjeta > 0 
	AND DATE(fecha_boletos) BETWEEN '{$_GET["fecha_inicial"]}' AND '{$_GET["fecha_final"]}'
	AND DATE(fecha_boletos) > '2023-11-30'
	
	
	ORDER BY fecha,orden
	";
	
	
	$result_detalle = mysqli_query($link, $consulta) or die("<pre>$consulta</pre>". mysqli_error($link));
	
	while($row = mysqli_fetch_assoc($result_detalle)){
		
		$filas[] = $row ;
		
	}
	
	// echo "<pre>";
	// echo $consulta;
	// echo "</pre>";
?>


<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">
		<title>Detalle Estado de Cuenta</title>
		<?php include('../../styles.php')?>
	</head>
	<body id="page-top">
		<?php include("../../navbar.php")?>
		<div id="wrapper" class="">
			<?php include("../../menu.php")?>	
			<div id="content-wrapper">		
				<div class="container-fluid">
					<div class="row d-print-none">
						<div class="col-sm-12">
							<a  class="btn btn-success" href="estado_cuenta.php">
								<i class="fas fa-arrow-left"></i> Regresar
							</a>
							<button  class="btn btn-info" onclick="window.print();">
								<i class="fas fa-print"></i> Imprimir
							</button>
							<button type="button" class="btn btn-primary  " id="btn_exportar" >
							<i class="fa fa-file-excel"></i> Exportar  
							</button>
							</div>
					</div>
					
					<form id="form_filtro" hidden autocomplete="off">
						<div class="row"> 
							
							<div  class="col-sm-2">
								<label>Fecha Inicial:</label>
								<input type="date" name="fecha_inicial" id="fecha_inicial" class="form-control" value="<?= $_GET["fecha_inicial"]?>" >
							</div>
							<div  class="col-sm-2">
								<label>Fecha Final:</label>
								<input type="date" name="fecha_final" id="fecha_final" class="form-control" value="<?= $_GET["fecha_final"]?>" >
							</div>
							
							<div class="col-sm-2">
								<label >Num Eco:</label>
								<input type="number" name="num_eco" id="num_eco" class="form-control" value="<?= $_GET["num_eco"]?>" >
							</div>
						</div>
					</form>
					
					
					<div class="d-print-block" id="reporte_impresion">
						<legend>
							Estado de Cuenta de la Unidad <b><?php echo $_GET["num_eco"]?></b>
							
							Del <b><?php echo date("d-m-Y", strtotime($_GET["fecha_inicial"]))?></b> al <b><?php echo  date("d-m-Y", strtotime($_GET["fecha_final"]))?></b>
						</legend>
						
						
						<div class="table-responsive" id="tabla_registros">
							<table class="table table-bordered table-condensed table-sm">
								<thead>
									<tr>
										<th>#</th>
										<th>Fecha</th>
										<th>Concepto</th>
										<th>Cargo</th>
										<th>Abono</th>
										<th>Saldo</th>
										<th>Observaciones</th>
									</tr>
								</thead>
								<tbody> 
									
									<?php 
										$saldo = $filas[0]["saldo_anterior"];
										
										foreach($filas as $i=>$fila){
											$total_cargos+= $fila["cargo"];
											$total_abonos+= $fila["abono"];
											
											if($fila["cargo"] > 0){
												$saldo-= $fila["cargo"];
											}
											else{
												$saldo+= $fila["abono"];
											}
										?>
										<tr class="text-right">
											<td><?php echo $i + 1?></td>
											<td class="text-nowrap"><?php echo date("d-m-Y", strtotime($fila["fecha"]))?></td>
											<td><?php echo $fila["motivo"]?></td>
											<td><?php echo $fila["cargo"] > 0 ? "$ ".number_format($fila["cargo"],2) : ""?></td>
											<td><?php echo $fila["abono"] > 0  ? "$ ".number_format($fila["abono"],2) : "";?></td>
											<td>$<?php echo number_format($saldo,2);?></td>
											<td class="text-left "><?php echo $fila["observaciones"]?></td>
										</tr>
										<?php
										}
									?>
									
									<tr class="h5 bg-secondary text-light text-right">
										<td > </td>
										<td colspan="2"> TOTALES</td>
										<td>$<?php echo number_format($total_cargos,2);?></td>
										<td>$<?php echo number_format($total_abonos,2);?></td>
										<td>$<?php echo number_format($filas[0]["saldo_anterior"] + $total_abonos - $total_cargos,2);?></td>
										<td > </td>
									</tr>
								</tr>
							</tbody>
						</table>
					</div>
					
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
	
	<div class="d-print-block  p-2 " id="ticket" >
	</div>
	
	<?php
		// include("forms/form_tarjetas.php");
		include("../../scripts.php")
	?>
	<script>
		$("#btn_exportar").click(function(event){
			window.open("consultas/exportar_estado_cuenta_detalle.php?"+ $("#form_filtro").serialize())
		});
		
	</script>
</body>
</html>
