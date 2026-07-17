<?php 
	include_once("../login/login_check.php");
	include("../../conexi.php");
	include("../../funciones/generar_select.php");
	
	$link = Conectarse();
	
	
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">
		<title>Usuarios</title>
		<?php include('../../styles.php')?>
	</head>
	<body id="page-top">
		<?php include("../../navbar.php")?>
		<div id="wrapper">
			<?php include("../../menu.php")?>	
			<div id="content-wrapper">		
				<div class="container-fluid">		
					<!-- Breadcrumbs-->
					<ol class="breadcrumb">
						<li class="breadcrumb-item">
							<a href="#">Facturación</a>
						</li>
						<li class="breadcrumb-item active">Emisor</li>
					</ol>
					
					<div class="container mt-4">
						<form id="frmEmisor" enctype="multipart/form-data">
							<div class="card shadow">
								
								<div class="card-header bg-primary text-white">
									
									<h4>Configuración del Emisor CFDI</h4>
									
								</div>
								
								<div class="card-body">
									
									
									
									<div class="row">
										
										<div class="col-md-4">
											
											<label>RFC</label>
											
											<input type="text"
											class="form-control"
											id="rfc"
											name="rfc_emisores"
											readonly>
											
										</div>
										
										<div class="col-md-8">
											
											<label>Razón Social</label>
											
											<input
											type="text"
											class="form-control"
											id="razon_social"
											name="razon_social_emisores"
											readonly>
											
										</div>
										
									</div>
									
									<hr>
									
									<div class="row">
										
										<div class="col-md-6">
											
											<label>Certificado (.cer)</label>
											
											<input
											type="file"
											accept=".cer"
											name="certificado"
											class="form-control">
											
										</div>
										
										<div class="col-md-6">
											
											<label>Llave Privada (.key)</label>
											
											<input
											type="file"
											accept=".key"
											name="key"
											class="form-control">
											
										</div>
										
									</div>
									
									<div class="row mt-3">
										
										<div class="col-md-6">
											
											<label>Password de la llave</label>
											
											<input
											type="password"
											class="form-control"
											name="password">
											
										</div>
										
										<div class="col-md-6">
											
											<button
											type="button"
											class="btn btn-primary mt-4"
											id="leerCertificado">
												
												Leer Certificado
												
											</button>
											
										</div>
										
									</div>
									
									<hr>
									
									<div class="row">
										
										<div class="col-md-3">
											
											<label>Fecha Validez</label>
											
											<input
											type="text"
											readonly
											id="fecha"
											class="form-control">
											
										</div>
										
										<div class="col-md-3">
											
											<label>No. Certificado</label>
											
											<input
											type="text"
											readonly
											id="numero"
											class="form-control">
											
										</div>
										<div class="col-md-3">
											
											<div class="form-group">
												<label for="id_niveles">Régimen:</label>
												<select id="regimen_emisores" required name="regimen_emisores" class="form-control">
													<option value="">Seleccione...</option>
													<option <?php echo is_selected($regimen_emisores, "601");?> value="601">601	General de Ley Personas Morales</option>
													<option <?php echo is_selected($regimen_emisores, "603");?> value="603">603	Personas Morales con Fines no Lucrativos</option>
													<option <?php echo is_selected($regimen_emisores, "605");?> value="605">605	Sueldos y Salarios e Ingresos Asimilados a Salarios</option>
													<option <?php echo is_selected($regimen_emisores, "606");?> value="606">606	Arrendamiento</option>
													<option <?php echo is_selected($regimen_emisores, "607");?> value="607">607	Régimen de Enajenación o Adquisición de Bienes</option>
													<option <?php echo is_selected($regimen_emisores, "608");?>  value="608">608	Demás ingresos</option>
													<option <?php echo is_selected($regimen_emisores, "609");?> value="609">609	Consolidación</option>
													<option <?php echo is_selected($regimen_emisores, "610");?> value="610">610	Residentes en el Extranjero sin Establecimiento Permanente en México</option>
													<option <?php echo is_selected($regimen_emisores, "611");?> value="611">611	Ingresos por Dividendos (socios y accionistas)</option>
													<option <?php echo is_selected($regimen_emisores, "612");?> value="612">612	Personas Físicas con Actividades Empresariales y Profesionales</option>
													<option <?php echo is_selected($regimen_emisores, "614");?> value="614">614	Ingresos por intereses</option>
													<option <?php echo is_selected($regimen_emisores, "615");?> value="615">615	Régimen de los ingresos por obtención de premios</option>
													<option <?php echo is_selected($regimen_emisores, "616");?> value="616">616	Sin obligaciones fiscales</option>
													<option <?php echo is_selected($regimen_emisores, "620");?> value="620">620	Sociedades Cooperativas de Producción que optan por diferir sus ingresos</option>
													<option <?php echo is_selected($regimen_emisores, "621");?> value="621">621	Incorporación Fiscal</option>
													<option <?php echo is_selected($regimen_emisores, "622");?> value="622">622	Actividades Agrícolas, Ganaderas, Silvícolas y Pesqueras</option>
													<option <?php echo is_selected($regimen_emisores, "623");?> value="623">623	Opcional para Grupos de Sociedades</option>
													<option <?php echo is_selected($regimen_emisores, "624");?> value="624">624	Coordinados</option>
													<option <?php echo is_selected($regimen_emisores, "628");?> value="628">628	Hidrocarburos</option>
													<option <?php echo is_selected($regimen_emisores, "629");?> value="629">629	De los Regímenes Fiscales Preferentes y de las Empresas Multinacionales</option>
													<option <?php echo is_selected($regimen_emisores, "630");?> value="630">630	Enajenación de acciones en bolsa de valores</option>
												</select>
											</div>
										</div>
										
									</div>
									
									<hr>
									
									<div class="row">
										
										<div class="col-md-6">
											
											<label>Logo</label>
											
											<input
											type="file"
											accept="image/*"
											name="logo"
											class="form-control">
											
										</div>
										
										<div class="col-md-6">
											
											<img
											id="preview"
											class="img-thumbnail"
											style="max-height:150px;">
											
										</div>
										
									</div>
									
									
									
								</div>
								
							</div>
							
							<!-- SERIES Y FOLIOS -->
							<div class="card mb-3">
								<div class="card-header bg-info text-white">
									Series y Folios
								</div>
								
								<div class="card-body">
									
									<div class="form-row">
										
										<div class="form-group col-md-3">
											<label>Serie Facturas</label>
											<input type="text"
											class="form-control"
											name="serie"
											maxlength="10"
											value="A">
										</div>
										
										<div class="form-group col-md-3">
											<label>Folio Facturas</label>
											<input type="number"
											class="form-control"
											name="folio"
											min="1"
											value="1">
										</div>
										
										<div class="form-group col-md-3">
											<label>Serie Pagos</label>
											<input type="text"
											class="form-control"
											name="serie_pago"
											maxlength="10"
											value="P">
										</div>
										
										<div class="form-group col-md-3">
											<label>Folio Pagos</label>
											<input type="number"
											class="form-control"
											name="folio_pago"
											min="1"
											value="1">
										</div>
										
									</div>
									
								</div>
							</div>
							
							
							<!-- CONFIGURACIÓN DEL PAC Y CORREO -->
							<div class="card mb-3">
								<div class="card-header bg-secondary text-white">
									Configuración de Correo y PAC
								</div>
								
								<div class="card-body">
									
									<div class="form-row">
										
										<div class="form-group col-md-4">
											<label>Correo para Enviar Facturas</label>
											<input type="email"
											class="form-control"
											name="correo_emisores"
											placeholder="">
										</div>
										<div class="form-group col-md-4">
											<label>Contraseña del Correo</label>
											<input type="password"
											class="form-control"
											name="password_correo"
											autocomplete="new-password">
										</div>
										<div class="form-group col-md-4">
											<label>Servidor SMTP</label>
											<input type="text"
											class="form-control"
											name="host_correo"
											placeholder="smtp.midominio.com">
										</div>
										
										
										
									</div>
									
									<div class="form-row">
										
										<div class="form-group col-md-6">
											<label>Token Producción</label>
											<textarea class="form-control"
											name="token_produccion"
											rows="3"
											placeholder="Token de producción"></textarea>
										</div>
										
										<div class="form-group col-md-6">
											<label>Token Pruebas</label>
											<textarea class="form-control"
											name="token_pruebas"
											rows="3"
											placeholder="Token de pruebas"></textarea>
										</div>
										
									</div>
									
								</div>
							</div>
							
							<input type="hidden" name="id_emisores" id="id_emisores" value="">
							
							<div class="text-right mt-4 mb-4">
								
								<button type="submit" class="btn btn-success btn-lg">
									
									<i class="fa fa-save"></i> Guardar
									
								</button>
								
							</div>
							
						</div>
						
					</form>
					
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
		
		
		<!-- The Modal -->
		
		
		
		
		<?php include("../../scripts.php")?>
		<script >
			$("#leerCertificado").click(function(){
				
				var cer=$("input[name='certificado']")[0].files[0];
				
				if(cer==undefined){
					
					alert("Seleccione el certificado (.cer)");
					
					return;
					
				}
				
				var formData=new FormData();
				
				formData.append("certificado",cer);
				
				$.ajax({
					
					url:"funciones/leer_certificado.php",
					
					type:"POST",
					
					data:formData,
					
					processData:false,
					
					contentType:false,
					
					dataType:"json",
					
					success:function(resp){
						
						if(resp.error){
							
							alert(resp.error);
							
							return;
							
						}
						
						$("#rfc").val(resp.rfc);
						
						$("#razon_social").val(resp.razon_social);
						
						$("#fecha").val(resp.fecha_vencimiento);
						
						$("#numero").val(resp.numero_certificado);
						
						if(resp.tipo_persona=="MORAL")
						$("#tipo_moral").prop("checked",true);
						else
						$("#tipo_fisica").prop("checked",true);
						
					}
					
				});
				
			});
			
			$(document).on("change","input[name='logo']",function(){
				
				var archivo=this.files[0];
				
				if(!archivo)
				return;
				
				if(!archivo.type.match("image.*")){
					alert("Seleccione una imagen.");
					$(this).val('');
					return;
				}
				
				var reader=new FileReader();
				
				reader.onload=function(e){
					
					$("#preview").attr("src",e.target.result);
					
				}
				
				reader.readAsDataURL(archivo);
				
			});
			
			
			
			$("#frmEmisor").submit(function(e){
				e.preventDefault();
				
				var datos = new FormData(this);
				
				$.ajax({
					url: "consultas/guardar_emisor.php",
					type: "POST",
					data: datos,
					processData: false,
					contentType: false,
					dataType: "json"
				})
				.done(function(r){
					
					if(r.success){
						$("#id_emisores").val(r.id_emisores);
						alert("Configuración guardada correctamente.");
						}else{
						alert(r.mensaje);
					}
					
				})
				.fail(function(xhr, status, error){
					
					console.log(xhr.responseText);
					alert("Error: " + error);
					
				})
				.always(function(){
					
					console.log("Proceso finalizado.");
					
				});
				
			});
		</script>
		
	</body>
</html>
