<?php

class Email
{
	/**
	* Envia email.
	*
	* Métode que s'encarga d'enviar els correus electrònic de la plataforma.
	*
	*
	* @param string $email Direcció de correu del destinatari
	* @param string $subject Asunte que apareixera en el email
	* @param string $body Cos del correu. S'envia sempre códi html.
	*
	*/
	public static function sendMail($email, $subject, $body)
	{
		$mail = new PHPMailer();
		$mail ->SMTPDebug  = 0;

		try
		{
			$mail -> IsSMTP();
			$mail -> Host 		= SMTP_HOST;
			$mail -> SMTPAuth 	= true;
			$mail -> Username 	= SMTP_USERNAME;
			$mail -> Password 	= SMTP_PSSWD;
			$mail -> Port 		= SMTP_PORT;
			$mail -> SMTPSecure = SMTP_ENCRYPTION;

			$mail -> From 		= "info@metropolitana.net";
			$mail -> FromName 	= "METROPOLITANA INTRANET";
			$mail -> AddAddress($email);
			$mail -> CharSet 	= 'UTF-8';

			$mail -> Subject 	= $subject;
			$mail -> Body 		=  $body;
			$mail -> IsHTML(true);

			if($mail -> Send())
			{
				$msg = "mail enviado correctamente";
			}
			else
			{
				$msg = $mail->ErrorInfo;
			}
		}
		catch(phpmailerException $ex)
		{
			$msg = $ex->errorMessage();
		}

		return $msg;
	}
}
?>
