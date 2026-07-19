<?php 
	session_start();
	include("../../../conexi.php");
	$link = Conectarse();
	
	$id_emisores = 1;
	
	
	
	$suma_subtotal = 0 ; 
	$suma_iva = 0 ; 
	$suma_total = 0; 
	
	$query ="SELECT *, nombre_usuarios, facturas.total AS total_factura FROM facturas 
	LEFT JOIN boletos USING(id_facturas) 
	LEFT JOIN emisores USING(id_emisores) 
	LEFT JOIN clientes USING(id_clientes)
	LEFT JOIN usuarios ON facturas.id_usuarios = usuarios.id_usuarios
	
	";
	
	
	if(isset($_GET['year_facturas'])){
		
		$query.=" WHERE YEAR(fecha_facturas) = '".$_GET['year_facturas']."' ";
		if($_GET['mes_facturas'] != ""){
			$query.=" AND MONTH(fecha_facturas) = '".$_GET['mes_facturas']."' ";
			
		}
		}elseif(isset($_GET['mes_facturas'])){
		if($_GET['mes_facturas'] != ""){
			$query.=" WHERE  MONTH(fecha_facturas) = '".$_GET['mes_facturas']."' ";
		}
		
	}
	
	if($_GET['metodo_pago']){
		$query.=" AND  metodo_pago = '{$_GET['metodo_pago']}' ";
	}
	if($_GET['id_emisores'] != "") {
		$query.=" AND  id_emisores = '{$_GET['id_emisores']}' ";
	}
	
	if($_GET['tipo_comprobante']){
		$query.=" AND  tipo_comprobante = '{$_GET['tipo_comprobante']}' ";
	}
	
	
	if(isset($_GET['mostrar_pruebas'])){
		$query.=" AND  timbrado = '0' ";
	}
	else{
		$query.=" AND  timbrado = '1' ";
		
	}
	
	
	$query.=" ORDER BY  folio_facturas ";
	
	
	$result =mysqli_query($link,$query) or die("Error en: $query  ".mysqli_error($link));
	
?>

<?php 
	echo "<pre hidden>";
	echo $query;
	echo "</pre>";
	echo "<pre hidden>";
	print_r ($result);
	echo "</pre>";
	
?>

<table class="table table-bordered table-hover" id="tabla_reporte">
	<thead> 
		<tr class="text-center">
			<th>
				<label>
					Folio
					<input type="checkbox" id="check_all">
				</label>
			</th>
			<th>Boleto</th>
			<th>Fecha</th>
			<th>Razon Social</th>
			<th>Subtotal</th>
			<th>IVA</th>
			<th>Total</th>
			<th>Saldo</th>
			<th>M Pago</th>
			<th>Usuario</th>
			<th class="hidden-print">Estatus SAT</th>
			<th class="hidden-print">Acciones</th>
		</tr>
	</thead>
	<tbody id=""> 
		
		<?php
			
			$suma_saldo = 0;
			$suma_subtotal = 0;
			$suma_iva = 0;
			$suma_total = 0;
			
			
			while($row = mysqli_fetch_assoc($result)){
				// print_r($row );
				$cobrado = $row["cobrado"];
				$id_facturas = $row["id_facturas"];
				$folio_facturas = $row["folio_facturas"];
				$fecha_facturas = date("d/m/Y", strtotime($row["fecha_facturas"]));
				$razon_social_clientes = $row["razon_social_clientes"];
				$rfc_clientes = $row["rfc_clientes"];
				$correo_clientes = $row["correo_clientes"];
				$alias_clientes = $row["alias_clientes"];
				$url_pdf = $row["url_pdf"];
				$url_xml = $row["archivo_xml"];
				$subtotal = $row["subtotal"];
				$saldo_actual = $row["saldo_actual"];
				$metodo_pago = $row["metodo_pago"];
				$iva = $row["total_traslados"];
				$total = $row["total"];
				$cancelada = $row["cancelada"];
				$timbrado = $row["timbrado"];
				$prueba = $row["timbrado"] == 0 ? "<span class='badge badge-warning' >PRUEBA</ span>" : "";
				$motivo_cancelacion = $row["motivo_cancelacion"];
				
				if($cancelada != 1 && $timbrado == 1){
					$suma_subtotal+= $subtotal ; 
					$suma_iva+= $iva ; 
					$suma_total+= $total; 
					
				}
				
				$span_cancelado ="<span class='badge badge-danger'>CANCELADO<br>{$row["motivo_cancelacion"]}</span>"; 
				$span_activo ="<span class='badge badge-success'>ACTIVO</span>"; 
				$span_timbrado ="<span class='badge badge-success'>SI</span>"; 
				$span_prueba ="<span class='badge badge-warning'>PRUEBA</span>"; 
				$span_cobrado ="<span class='badge badge-success'>SI</span>"; 
				$span_pendiente ="<span class='badge badge-danger'>NO</span>"; 
				
			?>
			<tr>
				<td class="text-center">
					<label>
						<input type="checkbox" class="seleccionar" value='<?php echo $row['id_facturas']?>'>
						<?php echo $folio_facturas; ?>
					</label>
					
				</td>
				<td class="text-center"><?php echo $row["id_boletos"];?></td>
				<td class="text-center"><?php echo $fecha_facturas;?></td>
				<td class="text-left"><?php echo $razon_social_clientes;?></td>
				<td class="text-right">$<?php echo number_format($subtotal,2); ?></td>
				<td class="text-right">$<?php echo number_format($iva,2); ?></td>
				<td class="text-right">$<?php echo number_format($row["total_factura"],2); ?></td>
				<td class="text-center">
					<?php 
						if($row["saldo_actual"] > 0 && $cancelada != '1'){
							$suma_saldo += $row["saldo_actual"];
							echo "<b><span class='text-danger' >$". number_format($row["saldo_actual"],2)."</span ></b>";
						}
						
					?>
					<input hidden type="number" value="<?php echo $row["saldo_actual"]?>" class="saldo_actual">
					
					<?php if($_COOKIE["nombre_usuarios"] == "sistemas"){
						// echo "<pre>";
						// print_r($row);
						// echo "</pre>";
						
					}?>
				</td>
				<td class="text-left"><?php echo $row["metodo_pago"] ;?></td>
				<td class="text-left"><?php echo $row["nombre_usuarios"]; ?></td>
				
				<td class="text-center">
					
					<?php echo $cancelada == '1' ? $span_cancelado : $span_activo; ?>
					</br>
					<?php echo $prueba; ?>
					
				</td>
				<td class="text-center hidden-print"> 
					<div class="btn-group">
						<a  class="btn btn-default btn_vista <?php echo $cancelada == '1' ? "hidden" : ''; ?>" target="_blank" title="Vista Previa"  href="facturacion/plantilla_pdf.php?id_facturas=<?= $id_facturas;?>">
							<i class="fa fa-eye" ></i>
						</a>
						
						<button class="btn btn-danger btn_cancelar " <?php echo $cancelada == '1' ? "hidden" : ''; ?> type="button" title="Cancelar Factura" 
						data-uuid="<?= $row["uuid"]; ?>" 
						data-id_facturas="<?php echo $id_facturas; ?>"
						data-id_emisores="<?php echo $row["id_emisores"]; ?>"
						
						>
							<i class="fa fa-times" ></i>
						</button>
						<button class="btn btn-primary btn_correo" type="button" title="Enviar por Correo" 
						data-id_emisores="<?php echo ($id_emisores); ?>"  
						data-correo="<?php echo strtolower($correo_clientes); ?>"  
						data-folio="<?php echo $row["folio_facturas"];?>"
						data-nombre="<?php echo $razon_social_clientes;?>" 
						data-url_xml="<?php echo $url_xml;?>" 
						data-url_pdf="<?php echo $url_pdf;?>"> 
							<i class="fa fa-envelope" ></i>
						</button>
						<a class="btn btn-info" target="_blank" type="button" title="Ver PDF" href="../../../facturacion/facturacion/<?php echo $url_pdf; ?>">
							<i class="fa fa-file-pdf"></i>
						</a> 
						<a class="btn btn-default" target="_blank" type="button" title="Ver XML" href="../../../facturacion/facturacion/<?php echo $url_xml; ?>">
							<i class="fa fa-qrcode"></i>
						</a>
						<a class="btn btn-default" target="_blank" title="Descargar"  href="<?php echo  URL_SISTEMA."facturacion/facturacion/generar_pdf.php?descargar=SI&id_facturas={$id_facturas}";?>">
							<i class="fa fa-download" ></i>
						</a>
						
						
					</div>
				</td>
			</tr>
			<?php
			}
		?>
	</tbody>
	<tfoot claSS="bg-secondary text-white h5">
		<tr>
			<td ><?php echo mysqli_num_rows($result); ?> Registros</td>
			<td ></td>
			<td ></td>
			<td ></td>
			<td class="text-center">$<?php echo number_format($suma_subtotal, 2); ?></td>
			<td class="text-center">$<?php echo  number_format($suma_iva, 2);?></td>
			<td class="text-center">$<?php echo  number_format($suma_total, 2);?></td>
			<td ></td>
			<td > <?php // echo  number_format($suma_saldo, 2); ?></td>
			<td ></td>
		</tr>
	</tfoot>
	
	
</table>
