<?php 
	
	$tmp=$_FILES["certificado"]["tmp_name"];
	
	$contenido=file_get_contents($tmp);
	
	/*
		Convertir DER -> PEM
	*/
	
	$pem="-----BEGIN CERTIFICATE-----\n";
	
	$pem.=chunk_split(base64_encode($contenido),64,"\n");
	
	$pem.="-----END CERTIFICATE-----\n";
	
	$cert=openssl_x509_read($pem);
	
	if(!$cert){
		
		echo json_encode([
        "error"=>"No se pudo leer el certificado."
		]);
		
		exit;
		
	}
	
	$datos=openssl_x509_parse($cert);
	
	$rfc=$datos["subject"]["x500UniqueIdentifier"] ?? "";
	
	$razon=$datos["subject"]["CN"] ?? "";
	
	$numero=$datos["serialNumber"] ?? "";
	
	$fecha=date("Y-m-d",$datos["validTo_time_t"]);
	
	$tipo="MORAL";
	
	if(strlen($rfc)==13)
    $tipo="FISICA";
	
	echo json_encode([
	
    "rfc"=>$rfc,
	
    "razon_social"=>$razon,
	
    "numero_certificado"=>$numero,
	
    "fecha_vencimiento"=>$fecha,
	
    "tipo_persona"=>$tipo
	
	]);
?>