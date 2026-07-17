<?php 
	session_start();
	session_destroy();
	
	date_default_timezone_set("America/Mexico_City");
?>

<!DOCTYPE html>
<html lang="es_mx">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">
		<meta name="robots" content="noindex">
		<title>Iniciar Sesión</title>
		
		<?php include("../../styles.php")?>
		
	</head>
	
	<body class="">
		
		<div class="container">
			<div class="card card-login mx-auto mt-5">
				<div class="card-header text-center">
					INICIAR SESIÓN
				</div>
				<div class="card-body">
					<div class="text-center mx-3 my-3" >
						<img class="img-fluid" src="../../img/logo.png">
					</div>
					<form id="form_login">
						<div class="form-group">
							<div class="form-label-group">
								<input  hidden id="retorno" value="<?php echo $_GET["retorno"]?>">
								<input name="nombre_usuarios" type="input" id="inputEmail" class="form-control" placeholder="Usuario" required="required" autofocus="autofocus" autocomplete="off">
								<label for="inputEmail">Usuario</label>
							</div>
						</div>
						<div class="form-group">
							<label for="inputPassword">Contraseña</label>
							<div class="input-group">
								<input type="password" name="pass_usuarios" id="password" class="form-control" placeholder="Password" required="required">
								<button class="btn btn-outline-secondary" type="button" id="btn_mostrar">
									<i class="fas fa-eye"></i>
								</button>
							</div>
							
							
						</div>
						<button type="submit" class="btn btn-primary btn-block"><i class="fas fa-sign-in-alt"></i> Iniciar Sesión</button>
					</form>
					<div class="text-center">
					</div>
				</div>
			</div>
		</div>
		
		<?php include("../../scripts.php")?>
		<script src="login.js?v=<?= date("d-m-Y-H-i-s")?>"></script>
	</body>
</html>
