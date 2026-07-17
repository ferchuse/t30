<?php 
	
	include('../../../conexi.php');
	include('../../../funciones/generar_select.php');
	include('../../../funciones/dame_permiso.php');
	$link = Conectarse();
	$fila = array();
	$respuesta = array();
	$casetas_tag = array();
	$filas =  array();
	$totales =  array();
	$total_viajes =  0;
	
	$consulta_casetas_tag = "SELECT * FROM casetas_tag 
	
	LEFT JOIN boletos ON boletos.id_boletos = casetas_tag.folio_boleto
	WHERE DATE(fecha_viaje) BETWEEN '{$_GET["fecha_inicial"]}'
	AND '{$_GET["fecha_final"]}' ";
	
	if($_GET["num_eco"] != ""){
		
		$consulta_casetas_tag.= " AND num_eco = '{$_GET["num_eco"]}'";
	}
	
	$consulta_casetas_tag .= " ORDER BY fecha_viaje";
	
	$result_casetas_tag = mysqli_query($link,$consulta_casetas_tag) or die("Error en $consulta_casetas_tag ". mysqli_error($link));
	
	while($row = mysqli_fetch_assoc($result_casetas_tag)){	
		$casetas_tag[] = $row ;
	}
	
	
	
	
	
	$consulta_importe_unidad = "SELECT *  FROM unidades  
	
	LEFT JOIN 
	(
	SELECT tag, SUM(importe) AS importe_unidad
	FROM
	casetas_tag 
	WHERE 
	DATE(fecha_viaje) BETWEEN '{$_GET["fecha_inicial"]}'
	AND '{$_GET["fecha_final"]}'
	GROUP BY tag
	
	) AS t_viajes USING (tag)
	
	WHERE estatus_unidades = 'Activo'
	AND id_empresas = '{$_COOKIE["empresa_asignada"]}'
	GROUP BY num_eco
	
	ORDER BY num_eco ASC";
	
	$result_importe_unidad = mysqli_query($link,$consulta_importe_unidad) or die("Error en $consulta_importe_unidad ". mysqli_error($link));
	
	while($row = mysqli_fetch_assoc($result_importe_unidad)){
		$filas[] = $row;
	}
	
	
	
?>  
<pre  hidden>
	<?php echo $consulta_gastos;?>
</pre>

<div class="row">
	<div class="col-sm-6">
		
		
		
		<table class="table table-bordered table-sm" id="tabla_tag">
			<thead>
				<tr>
					<th>Concesionaria</th>
					<th>Fecha Viaje</th>
					<th>Tag</th>
					<th>Operador</th>
					<th>Num Eco</th>
					<th>Entrada</th>
					<th>Folio Boleto</th>
					<th>Importe</th>
				</tr>
			</thead>
			<tbody>
				<?php 
					foreach($casetas_tag AS $fila){ 
						$totales[0]+=  $fila["importe"];
					?>
					<tr class="focusable text-right" >
						
						<td><?php echo $fila["concesionaria"]?></td>
						<td ><?php echo date("d-m-Y H:i:a" , strtotime($fila["fecha_viaje"]))?></td>
						<td ><?php echo $fila["tag"]?></td>
						<td ><?php echo $fila["num_eco_tag"]?></td>
						<td ><?php echo $fila["num_eco"]?></td>
						<td ><?php echo $fila["entrada"]?></td>
						<td ><?php echo $fila["folio_boleto"]?></td>
						<td >$<?php echo number_format($fila["importe"])?></td>
						
						
					</tr>
					
					<?php
					}
				?>
				
				
			</tbody>
			<tfoot>
				<tr class="text-right">
					
					<td><?php echo mysqli_num_rows($result_casetas_tag);?> Registros</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td colspan=""> TOTALES</td>
					<?php
						foreach($totales AS $i => $total){  ?>
						<td>$<?php echo number_format($total,2);?></td>
						<?php
						}
					?>
					
				</tr>
			</tfoot>
		</table>
		
	</div>
	
	
	
	<div class="col-sm-6">
		
		
		<table class="table table-bordered table-sm">
			<thead>
				<tr>
					<th>TAG</th>
					<th>Unidad</th>
					<th>Casetas</th>
					
					
					
					
				</tr>
			</thead>
			<tbody>
				<?php 
					foreach($filas AS $i => $fila){ 
						$total_viajes+=  $fila["viajes"];
					?>
					<tr class="focusable text-right" >
						
						<td><b><?php echo $fila["tag"]?></b></td>
						<td ><?php echo $fila["num_eco"]?></td>
						<td >$<?php echo number_format($fila["importe_unidad"],2)?></td>
						
					</tr>
					
					<?php
					}
				?>
				
				<tr class="text-right">
					
					<td><?php echo mysqli_num_rows($result_casetas_tag);?> Registros</td>
					<td colspan=""> TOTALES</td>
					<?php
						foreach($totales AS $i => $total){  ?>
						<td>$<?php echo number_format($total,2);?></td>
						<?php
						}
					?>
					
				</tr>
			</tbody>
		</table>
	</div>	
</div>


