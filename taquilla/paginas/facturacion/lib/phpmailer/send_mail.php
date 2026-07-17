<?php
	include('../../../../conexi.php');
	$link = Conectarse();
	
	$id_emisores = isset($_GET["id_emisores"]) ? $_GET["id_emisores"] : 1;
	
	$consulta = "SELECT * FROM emisores WHERE id_emisores = {$id_emisores}";
	
	$result = mysqli_query($link, $consulta) or die(mysqli_error($link));
	
	while ($fila = mysqli_fetch_assoc($result)) {
		$empresa = $fila;
	}
	
	// print_r($empresa);
	
	$recipients = explode(",", $empresa["correo_emisores"]);
	
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;
	
	require 'vendor/autoload.php';
	
	//Create an instance; passing `true` enables exceptions
	$mail = new PHPMailer(true);
	
	try {
		//Server settings
		$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
		$mail->isSMTP();                                            //Send using SMTP
		$mail->Host       = $empresa["host_correo"];                     //Set the SMTP server to send through
		$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
		$mail->Username   = $empresa["correo_emisores"];                         //SMTP username
		$mail->Password   = $empresa["password_correo"];                                   //SMTP password
		$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
		$mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
		
		//Recipients
		$mail->setFrom($empresa["correo_emisores"], "Facturacion {$empresa["razon_social_emisores"]}");
		// foreach($recipients as $recipient){
		// $arr_recipient = explode(":", $recipient);
		// $mail->addAddress($arr_recipient[0], $arr_recipient[1]);  
		
		// echo $arr_recipient[0], "correo", $arr_recipient[1];
		// }
		
		$mail->addAddress($_GET["correo"]);               //Name is optional
		// $mail->addReplyTo('info@example.com', 'Information');
		// $mail->addCC('cc@example.com');
		// $mail->addBCC('bcc@example.com');
		
		//Attachments
		$mail->addAttachment("../../../../../facturacion/facturacion/timbrados/".$_GET["url_xml"]);         //Add attachments
		$mail->addAttachment("../../../../../facturacion/facturacion/timbrados/".$_GET["url_pdf"]);    //Optional name
		
		//Content
		$mail->isHTML(true);                                  //Set email format to HTML
		$mail->Subject = "Se adjunta la factura con Folio {$_GET["folio"]}";
		$mail->Body    = "Buen dia, se envia la factura  <b>{$_GET["folio"]}</b> ";
		// $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
		
		$mail->send();
		echo 'Message has been sent';
	} 
	catch (Exception $e) {
		echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
	}			