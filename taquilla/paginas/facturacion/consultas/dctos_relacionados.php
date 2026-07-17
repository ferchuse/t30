<?php
	include ("../../../conexi.php");
	
	
	$link=Conectarse();
	
	$respuesta  =array() ;
	
	
	$consulta = "SELECT * FROM facturas 
	WHERE id_facturas IN({$_GET["folios"]}) ";
	
	$result= mysqli_query($link,$consulta);
	
	if($result){
		while($fila=mysqli_fetch_assoc($result)){
			$dctos_relacionados[]  = $fila;
		}
	}
	else $respuesta["result"] = "Error". mysqli_error($link);
	
	
	
	foreach($dctos_relacionados as $i => $factura){ ?>
	
	<tr>
		<td>
			<i><?php echo $factura["folio_facturas"]?></i> <br>
			<small><b><?php echo strtoupper($factura["uuid"])?></b></small>
			
			<input type="hidden" name="dctos[<?php echo $i?>][id_facturas]" value="<?php echo $factura["id_facturas"]?>" >
			<input type="hidden" name="dctos[<?php echo $i?>][MetodoDePagoDR]" value="<?php echo $factura["metodo_pago"]?>" >
			<input type="hidden" name="dctos[<?php echo $i?>][uuid]" value="<?php echo $factura["uuid"]?>" >
			<input type="hidden" name="dctos[<?php echo $i?>][subtotal]" value="<?php echo $factura["subtotal"]?>" >
		</td>
		<td>
			<input type="number" step="any" class="form-control text-right ImpSaldoAnt" name="dctos[<?php echo $i?>][ImpSaldoAnt]" value="<?php echo $factura["saldo_actual"]?>">
			
		</td>
		<td>
			<input type="number" step="any" class="form-control text-right ImpPagado" name="dctos[<?php echo $i?>][ImpPagado]" value="<?php echo $factura["saldo_actual"]?>"> 
		</td>
		<td>
			<input type="number" step="any" class="form-control text-right ImpSaldoInsoluto" name="dctos[<?php echo $i?>][ImpSaldoInsoluto]" value="0" readonly>
			
		</td>
	</tr>
	
	<?php
	}
?>	

