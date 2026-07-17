<?php 
	session_start();
	include('../../../conexi.php');
	include('../../../funciones/generar_select.php');
	$link = Conectarse();
	$filas = array();
	$respuesta = array();
	
	
	$consulta = "##Importes por Usuario
	SELECT
	*
	FROM
	desglose_dinero
	LEFT JOIN 
	usuarios
	USING (id_usuarios)
	WHERE 
	 DATE(fecha_desglose) BETWEEN '{$_GET["fecha_inicial"]}'
	AND '{$_GET["fecha_final"]}'
	";
	
	IF($_GET["id_usuarios"] != ""){
		
		$consulta.=" AND id_usuarios = '{$_GET["id_usuarios"]}'";
		
	}
	
	
	$result = mysqli_query($link,$consulta);
	
	if($result){
		
		if( mysqli_num_rows($result) == 0){
			die("<div class='alert alert-danger'>No hay registros </div>");
			
		}
		
		
		
	?>  
	<table class="table table-bordered table-condensed">
		<thead>
			<tr>
				<th>Imprimir</th>
				<th>Folio</th>
				<th>Usuario</th>
				<th>Fecha</th>
				<th>Importe</th>
			</tr>
		</thead>
		<tbody>
			<?php 
				
				while($fila = mysqli_fetch_assoc($result)){
					
					
					$totales[0]+= $fila["importe_desglose"];
					
				?>
				
				<tr>
					<td class="text-center d-print-none">
						<button class="btn btn-outline-primary imprimir " title="Imprimir" data-id_registro='<?= $fila["id_desglose"]?>'>
						<i class="fas fa-print"></i></button>
					</td>
					<td class="text-center"><?= $fila["id_desglose"]?></td>
					<td class="text-center"><?= $fila["nombre_usuarios"]?></td>
					<td class="text-center"><?= $fila["fecha_desglose"]?></td>
					<td class="text-center">$ <?= number_format($fila["importe_desglose"])?></td>
				</tr>
				
				<?php
				}
			?>
			
			
		</tbody>
		<tfoot>
			<tr class="h5">
				<td colspan="4" ><b> <?= mysqli_num_rows($result);?> Registros</b></td>
	
				<td  class="text-center"><b>$ <?php echo number_format($totales[0])?></b></td>
			</tr>
		</tfoot>
	</table>
	
	<pre hidden>
		<?php echo $consulta;?>
	</pre>
	<?php
		
	}
	
	else {
		echo "<pre>Error en ".$consulta.mysqli_Error($link)."</pre>";
		
	}
?>
