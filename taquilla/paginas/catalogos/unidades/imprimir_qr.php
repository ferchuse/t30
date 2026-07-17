<?php 
	
	$economicos = explode(",", $_GET["num_eco"] );
	// print_r($economicos);
?> 

<!DOCTYPE html>
<html lang="es_mx">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">
		<title>QR</title>
		<?php include('../../../styles.php')?>
	</head>
	<body >
		
		
		<div >		
			<div class="container text-center mt-5">		
				<div class="row">		
					
					
					<?php foreach($economicos as $num_eco){ ?>
						<div class="col-sm-3 mb-2">
							<div data-num_eco="<?= $num_eco?>"  class="qr_code"></div>
							
						</div>
						<?php	
						}
					?>
					
				</div> 
			</div> 
		</div> 
		
		<?php include("../../../scripts.php")?>
		<script type="text/javascript" src="../../../plugins/qr_code/jquery-qrcode-0.17.0.min.js"></script>
		<script>
			$(".qr_code").each(function(){
			
			
			$(this).qrcode({
			label: $(this).data("num_eco"),
				
			mode	: 2,	
			render	: "canvas",	
			text	: "https://rhgaaz.com/catemaco/paginas/catalogos/unidades/detalles_unidad.php?num_eco="+$(this).data("num_eco")
			});		
			});
			
		</script>
	</body>
</html>	


