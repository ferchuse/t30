<?php
	include('../../../taquilla/conexi.php');
	$link = Conectarse();
	
	$consulta = "SELECT * FROM emisores WHERE id_emisores = {$_GET["id_emisores"]}";
	
	$result = mysqli_query($link, $consulta) or die(mysqli_error($link));
	
	while ($fila = mysqli_fetch_assoc($result)) {
		$empresa = $fila;
	}
	
	print_r($empresa);
	
	// $recipients = explode(",", $empresa["correos_notificaciones"]);
	// print_r("empresa".$empresa);
	// print_r($recipients);
	// print_r( $empresa["correos_notificaciones"]);
	//Import PHPMailer classes into the global namespace
	//These must be at the top of your script, not inside a function
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;
	
	//Load Composer's autoloader
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
		$mail->addAttachment("../../facturacion/".$_GET["url_xml"]);         //Add attachments
		$mail->addAttachment("../../facturacion/".$_GET["url_pdf"]);    //Optional name
		
		//Content
		$mail->isHTML(true);                                  //Set email format to HTML
		$mail->Subject = "Se adjunta la factura {$_GET["folio"]}";
		$mail->Body    = "Saludos, se envia la factura  <b>{$_GET["folio"]}</b> ";
		// $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
		
		$mail->send();
		echo 'Message has been sent';
		} catch (Exception $e) {
		echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
	}			