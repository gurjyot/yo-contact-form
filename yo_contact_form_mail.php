<?php
if(isset ($_POST['submit'])) {
	$errors = array();

	if(!empty ($_POST ['name'])) {
		$name = $_POST ['name'];
		} else {
			$errors[] = "You forgot to enter your Name.";
		}

	if(!empty ($_POST ['email'])) {
		$email = $_POST ['email'];
		} else {
			$errors[] = "You forgot to enter your Email.";
		}

	if(!empty ($_POST ['message'])) {
		$message = $_POST ['message'];
		} else {
			$errors[] = "You forgot to enter your Message.";
		}

$phone_number = $_POST['phone_number'];
$website = $_POST['website'];
$formcontent = "From: $name \n Email: $email \n Phone Number: $phone_number \n Website: $website \n Message: $message";
$recipient = "gurjyottheman@gmail.com";
$subject = "Contact Form";
$mailheader = "From: $email \r\n";
	if(isset($_POST['submit'])) {
		if(!empty($errors)) {
			foreach ($errors as $msg) 
				{ 
					echo '<li>'. $msg . '</li>';
				}
		} else {
			mail($recipient, $subject, $formcontent, $mailheader);
			echo "Thank You";
		}
	}
}
?>
