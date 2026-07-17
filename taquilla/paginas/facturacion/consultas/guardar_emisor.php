<?php
	
	require("../../../conexi.php");
	
	$link = Conectarse();
	
	$id_emisores = intval($_POST["id_emisores"]);
	
	$rfc_emisores = mysqli_real_escape_string($link,$_POST["rfc_emisores"]);
	$razon_social_emisores = mysqli_real_escape_string($link,$_POST["razon_social_emisores"]);
	$password = mysqli_real_escape_string($link,$_POST["password"]);
	$regimen_emisores = mysqli_real_escape_string($link,$_POST["regimen_emisores"]);
	$lugar_expedicion_emisores = mysqli_real_escape_string($link,$_POST["lugar_expedicion_emisores"]);
	$correo_emisores = mysqli_real_escape_string($link,$_POST["correo_emisores"]);
	$tipo_persona = mysqli_real_escape_string($link,$_POST["tipo_persona"]);
	
	$serie = mysqli_real_escape_string($link,$_POST["serie"]);
	$folio = intval($_POST["folio"]);
	
	$serie_pago = mysqli_real_escape_string($link,$_POST["serie_pago"]);
	$folio_pago = intval($_POST["folio_pago"]);
	
	$password_correo = mysqli_real_escape_string($link,$_POST["password_correo"]);
	$host_correo = mysqli_real_escape_string($link,$_POST["host_correo"]);
	
	$token_produccion = mysqli_real_escape_string($link,$_POST["token_produccion"]);
	$token_pruebas = mysqli_real_escape_string($link,$_POST["token_pruebas"]);
	
	$fecha_validez_certificado = mysqli_real_escape_string($link,$_POST["fecha_validez_certificado"]);
	
	$url_certificado_emisores="";
	$url_llave_privada_emisores="";
	$url_logo="";
	
	
	
	/**************************************************
		SUBIR CERTIFICADO
	**************************************************/
	
	if(isset($_FILES["certificado"]) && $_FILES["certificado"]["error"]==0){
		
		if(!is_dir("certificados"))
        mkdir("certificados",0777,true);
		
		$nombre=time()."_".$_FILES["certificado"]["name"];
		
		move_uploaded_file($_FILES["certificado"]["tmp_name"],"certificados/".$nombre);
		
		$url_certificado_emisores="certificados/".$nombre;
		
	}
	
	
	
	/**************************************************
		SUBIR KEY
	**************************************************/
	
	if(isset($_FILES["llave"]) && $_FILES["llave"]["error"]==0){
		
		if(!is_dir("certificados"))
        mkdir("certificados",0777,true);
		
		$nombre=time()."_".$_FILES["llave"]["name"];
		
		move_uploaded_file($_FILES["llave"]["tmp_name"],"certificados/".$nombre);
		
		$url_llave_privada_emisores="certificados/".$nombre;
		
	}
	
	
	
	/**************************************************
		SUBIR LOGO
	**************************************************/
	
	if(isset($_FILES["logo"]) && $_FILES["logo"]["error"]==0){
		
		if(!is_dir("logos"))
        mkdir("logos",0777,true);
		
		$nombre=time()."_".$_FILES["logo"]["name"];
		
		move_uploaded_file($_FILES["logo"]["tmp_name"],"logos/".$nombre);
		
		$url_logo="logos/".$nombre;
		
	}
	
	
	
	if($id_emisores==0){
		
		$sql="INSERT INTO emisores SET
		rfc_emisores='$rfc_emisores',
		razon_social_emisores='$razon_social_emisores',
		password='$password',
		regimen_emisores='$regimen_emisores',
		folios_restantes_emisores='0',
		serie_actual_emisores='',
		url_certificado_emisores='$url_certificado_emisores',
		url_llave_privada_emisores='$url_llave_privada_emisores',
		fecha_validez_certificado='$fecha_validez_certificado',
		lugar_expedicion_emisores='$lugar_expedicion_emisores',
		correo_emisores='$correo_emisores',
		tipo_persona='$tipo_persona',
		url_logo='$url_logo',
		serie='$serie',
		folio='$folio',
		serie_pago='$serie_pago',
		folio_pago='$folio_pago',
		password_correo='$password_correo',
		host_correo='$host_correo',
		token_produccion='$token_produccion',
		token_pruebas='$token_pruebas'";
		
		if(!mysqli_query($link,$sql)){
			die("Error al insertar emisor: ".mysqli_error($link)."<br><br>".$sql);
		}
		
		$id_emisores=mysqli_insert_id($link);
		
		}else{
		
		$sql="UPDATE emisores SET
		rfc_emisores='$rfc_emisores',
		razon_social_emisores='$razon_social_emisores',
		password='$password',
		regimen_emisores='$regimen_emisores',
		fecha_validez_certificado='$fecha_validez_certificado',
		lugar_expedicion_emisores='$lugar_expedicion_emisores',
		correo_emisores='$correo_emisores',
		tipo_persona='$tipo_persona',
		serie='$serie',
		folio='$folio',
		serie_pago='$serie_pago',
		folio_pago='$folio_pago',
		password_correo='$password_correo',
		host_correo='$host_correo',
		token_produccion='$token_produccion',
		token_pruebas='$token_pruebas'";
		
		if($url_certificado_emisores!="")
        $sql.=", url_certificado_emisores='$url_certificado_emisores'";
		
		if($url_llave_privada_emisores!="")
        $sql.=", url_llave_privada_emisores='$url_llave_privada_emisores'";
		
		if($url_logo!="")
        $sql.=", url_logo='$url_logo'";
		
		$sql.=" WHERE id_emisores='$id_emisores'";
		
		if(!mysqli_query($link,$sql)){
			die("Error al actualizar emisor: ".mysqli_error($link)."<br><br>".$sql);
		}
		
	}
	
	echo json_encode(
	
	array(
	
	"success"=>true,
	
	"id_emisores"=>$id_emisores
	
	)
	
	);
	
?>