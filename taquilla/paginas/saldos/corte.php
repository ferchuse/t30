<?php
	include("../../paginas/login/login_check.php");
	include('../../conexi.php');
	$link = Conectarse();
	$nombre_pagina = "Corte del dia";
	
?>
<!DOCTYPE html>
<html lang="es_mx">
	<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?php echo $nombre_pagina;?></title>
        <?php include('../../styles.php')?>
	</head>
	<body id="page-top">
    <?php include("../../navbar.php")?>
    <div id="wrapper" class="">
			<?php include("../../menu.php")?>	
			<div id="content-wrapper">		
				<div class="container-fluid">		
					<!-- Breadcrumbs-->
					<ol class="breadcrumb d-print-none">
						<li class="breadcrumb-item active"><?php echo $nombre_pagina;?></li>
					</ol>
					
					<div class="d-print-block" hidden id="formato_imprimir">
					</div>
					<div class="card mb-3" id="tableCard">
						<div class="card-header">
							<i class="fas fa-table"></i>
							<?php echo $nombre_pagina;?>
						</div>
						<div class="card-body">
							<div class="row">
                                <div class="col-md-6 " id="ingresos">
                                </div>
								<div class="col-md-6" id="egresos">
                                </div>
							</div>
						</div>
						<!--<div class="card-footer small text-muted">Ultima Modificación Ayer 12pm</div>-->
					</div>
				</div>
				<!-- /.container-fluid -->
				
			
			</div> 
			<!-- /.content-wrapper -->
		</div>
		<!-- /#wrapper -->
		
    <?php include("../../scripts.php")?>
    <script>
        document.addEventListener('DOMContentLoaded', async ()=>{ 
            let id_usuarios = document.getElementById('id_usuarios').value;
            
            let datosCorte = await obtenerCorte(id_usuarios);
        });
        
        //=======OBTENER EL CORTE DE CAJA DEL USUARIO ACTIVO======
        const obtenerCorte = (id_usuarios)=>{
            let objeto = {
                id_usuarios: id_usuarios
            };
            let datos = JSON.stringify(objeto);
            return new Promise((resolve, reject)=>{
                let http = new XMLHttpRequest();
                http.open('POST','control/obtenerCorte.php',true);
                http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                http.send('datos='+datos);

                http.onreadystatechange = ()=>{
                    if(this.readyState == 4 && this.status == 200){
                        resolve(this.responseText);
                    }
                };
            });
        };
        //==========GENERAR TABLA DESPUES DE OBTENER LOS DATOS=======
        const generarTabla = ()=>{

        };
    </script>
	</body>
</html>