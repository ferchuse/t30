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
							<a href="#">Administración</a>
						</li>
						<li class="breadcrumb-item active">Emisor</li>
					</ol>
					
					<div class="container mt-4">
						
						<div class="card shadow">
							
							<div class="card-header bg-primary text-white">
								
								<h4>Configuración del Emisor CFDI</h4>
								
							</div>
							
							<div class="card-body">
								
								<form id="frmEmisor" enctype="multipart/form-data">
									
									<div class="row">
										
										<div class="col-md-4">
											
											<label>RFC</label>
											
											<input type="text"
											class="form-control"
											id="rfc"
											readonly>
											
										</div>
										
										<div class="col-md-8">
											
											<label>Razón Social</label>
											
											<input
											type="text"
											class="form-control"
											id="razon_social"
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
									
								</form>
								
							</div>
							
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
					
					url:"control/leer_certificado.php",
					
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
		</script>
		
	</body>
</html>
