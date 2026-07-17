<?php 
    include("../login/login_check.php");
?> 

<!DOCTYPE html>
<html lang="es_mx">
	
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
		
    <title>Página principal</title> 
		
		<?php include("../../styles.php");?>  
	</head>
	
  <body id="page-top">
		
		<?php include("../../navbar.php")?>
		
		
    <div id="wrapper">
			
      <!-- Sidebar -->
      <?php include("../../menu.php")?>
			
      <div id="content-wrapper">
				
        <div class="container-fluid">
					
          <!-- Breadcrumbs-->
          <ol class="breadcrumb">
            <li class="breadcrumb-item">
              <a href="#">Principal</a>
						</li>
            <li class="breadcrumb-item active">Inicio</li>
						</ol>
						
						
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
		
    
		
		<?php include("../../scripts.php")?>
	</body>
	
</html>
