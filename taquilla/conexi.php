<?php
	$cat_empresas = [1=> "EMPRESA DEMO", 2=> "AUTOTRANSPORTISTAS MEXIQUENSES SANROD, S. A. DE C. V." ];
	
	
	if($_SERVER["SERVER_NAME"] == "localhost"){
		
		$config = require 'D:\wamp\www\t30\config_local.php';
	}
	elseif($_SERVER["SERVER_NAME"] == "t30.mx"){
		
		$config = require "/home/ieileqp9kdni/public_html/t30mx/app/config.php";
	}
	
	
	function Conectarse()
	{
				
		$set_local = "SET time_zone = '-06:00'";
		$set_names = "SET NAMES 'utf8'";
		
		date_default_timezone_set('America/Mexico_City');
		setlocale(LC_ALL,"es_MX"); 
		setlocale(LC_NUMERIC, 'en_US'); 
		
		$link = mysqli_connect(
        DB_HOST,
        DB_USER,
        DB_PASS,
        DB_NAME
		);
		
		if(!$link){
			die(mysqli_connect_error());
		}
		
		if (!mysqli_select_db($link, DB_NAME))
		{
			die( "Error seleccionando la base de datos.". mysqli_error($link));
		}
		
		if (!mysqli_query( $link, $set_local))	
		{
			die( "Error cambiando TimeZone.". mysqli_error($link));
		} 
		
		// mysqli_query($link, "SET NAMES 'utf8'") or die("Error Cambiando charset").mysqli_error($link);
		
		// if($_SERVER["SERVER_NAME"] != "localhost"){
		mysqli_query($link, "SET CHARACTER SET utf8") or die("Error en charset UTF8".mysqli_error($link));
		
		// }
		
		
		
		//ACTIVAR SI LA BASE DE DATOS NO ESTA EN UTF-8
		//mysqli_query($set_names, $link) or die( "Error cambiando Charset". mysqli_error());
		// mysqli_query ("set character_set_client='utf8'"); 
		// mysqli_query ("set character_set_results='utf8'"); 
		// mysqli_query ("set collation_connection='utf8_general_ci'");
		// mysqli_query("SET NAMES 'utf8'");
		/* mysqli_query("SET CHARACTER SET utf8") or die(MYSQL_ERROR());
			mysqli_query("SET SESSION collation_connection = 'utf8_unicode_ci'");
			mysqli_set_charset('utf8', $link) or die(MYSQL_ERROR());
		*/
		
		return $link;
	}
	
	
	
	$link = Conectarse();
	
?>