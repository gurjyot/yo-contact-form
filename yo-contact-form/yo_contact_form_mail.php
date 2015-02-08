<?php 
if(isset ($_POST['submit'])) {
	$errors = array();

	//Check if Name is provided
	if(!empty($_POST['name'])) {
	$name = $_POST['name'];
	} else { $errors[]= 'You forgot to enter your name.';}

	//Check if Email is provided
	if(!empty($_POST['email'])) {
	$email = $_POST['email'];
	} else { $errors[]= 'You forgot to enter your Email.';}

	//Check if Message is provided
	if(!empty($_POST['message'])) {
	$message = $_POST['message'];
	} else { $errors[]= 'You forgot to enter your Message.';}

}

$dropdown = $_POST['dropdown'];
$radio = $_POST['radio'];
$checkbox = $_POST['checkbox'];
$phone_number = $_POST['phone_number'];
$website = $_POST['website'];
$formcontent = "From: $name \n Email: $email \n Dropdown: $dropdown \n Radio Button: $radio-button \n Checkbox: $checkbox \n Phone Number: $phone-number \n Website: $website \n Message: $message";
$recipient = "gurjyottheman@gmail.com";
$subject = "Contact Form";
$mailheader = "From: $email \r\n";
mail($recipient, $subject, $formcontent, $mailheader);

if(isset($_POST['submit'])) {
	if(!empty($errors)) {
		echo "The following error occured";
		foreach ($errors as $msg) { echo '<li>'. $msg . '</li>';}
	} 
	else {
		echo "Thank You"; //Thank You Message Displayed
		echo "<br>";

		echo "<h2>Your Input:</h2>"; //Submitted form details displayed
		echo $name;
		echo "<br>";
		echo $email;
		echo "<br>";
		echo $dropdown;
		echo "<br>";
		echo $radio;
		echo "<br>";
		echo $checkbox;
		echo "<br>";
		echo $phone_number;
		echo "<br>";
		echo $website;
		echo "<br>";
		echo $message;
	}
}

?>
