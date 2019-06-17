<?php 

  require_once dirname(__FILE__) . '/autoload.php';

  if ( $_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["reCAP"])) {

		//using the Validation Class for data validate
		$val = new Validation();
 
		$name = $val->sanitize($_POST['name']);
		$email = $val->sanitize($_POST['email']);
		$subject = $val->sanitize($_POST['subject']);		
		$msg = $val->sanitize($_POST["msg"]);

		$val->field_name('Full Name')->value($name)->required()->pattern('contact-name');
		$val->field_name('Email Address')->value($email)->required()->is_email($email);
		$val->field_name('Subject')->value($subject);
		$val->field_name('Your Message')->value($msg)->required();

		//input data is valid
		if($val->isSuccess()){

			//Build POST request for reCAPTCHA:
			$recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
			$recaptcha_secret = env('recap_SECRET_KEY');
			$recaptcha_response = $_POST['reCAP'];
			
			// Make and decode POST request:
			$recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
			$recaptcha = json_decode($recaptcha);
			
			// Take action based on the score returned:
			if ($recaptcha->score >= 0.5) {
							
						unset($_POST['submit']);
						$recipient = "info@goinghometomysoul.com"; 
						$EmailSubject = "Contact Form: " . $subject; 
						$mailheader = "From: ".$email."\r\n"; 
						$mailheader .= "Reply-To: ".$email."\r\n"; 
						$mailheader .= "Content-type: text/html; charset=iso-8859-1\r\n"; 
						$MESSAGE_BODY = "Name: ".$name."<br>"; 
						$MESSAGE_BODY .= "Email: ".$email."<br>"; 
						$MESSAGE_BODY .= "Message: ".nl2br($msg)."";

						//send email
						if( mail($recipient, $EmailSubject, $MESSAGE_BODY, $mailheader) ){
							//if ( mail("youremail@somesite.com","First Test Email From site221 n9d","Message Body") ){ //test
								echo "success^^^Message sent successfully! <br>Thank you for contacting DR. THOMAS!";
						}else{
							echo "error^^^Sorry! Failed to send Message.";	}					

						// http_response_code(200);
						// echo "success^^^Thank you!<br> Message sent successfully!";
			}else{
				echo "error^^^Not verified - reCAPTCHA score is low, the system thinks that you are a robot. Please resend your message!";
			}

		}
		else { //input data is not valid then list out the errors
    	echo "error^^^Sorry, Message didn't send: <br>";
			$errors = $val->getErrors();
			foreach($errors as $eachone){
				echo $eachone . "<br>";
			}
		}
	}
	else {
		echo "error^^^there is an error!";
  }
?>