<?php

// Information to be modified

$your_email = "cemdesignsinfo@gmail.com"; // email address to which the form data will be sent
$subject = "Contact Message"; // subject of the email that is sent
$thanks_page = "thankyou.html"; // path to the thank you page following successful form submission
$contact_page = "contact.html"; // path to the HTML contact page where the form appears


// Nothing needs to be modified below this line

if (!isset($_POST['submit'])) {
    header( "Location: $contact_page" );
  }

if (isset($_POST["submit"])) {
	$nam = $_POST["name"];
    $ema = trim($_POST["email"]);
    $pho = $_POST["phone"];
	$com = $_POST["comments"];
	$spa = $_POST["spam"];

	if ("get_magic_quotes_gpc"()) { 
	$nam = stripslashes($nam);
    $ema = stripslashes($ema);
    $pho = stripslashes($pho);
	$com = stripslashes($com);
	}

$error_msg=array(); 

if (empty($nam) || !preg_match("/^[\s.'\-\pL]{1,60}$/u", $nam)) { 
$error_msg[] = "The name field must contain only letters, spaces and basic punctuation (.&nbsp;-&nbsp;')";
}

if (empty($ema) || !filter_var($ema, FILTER_VALIDATE_EMAIL)) {
	$error_msg[] = "Your email must have a valid format, such as name@mailhost.com";
}

if (empty($pho) || !preg_match("/^(\d[\s-]?)?[\(\[\s-]{0,2}?\d{3}[\)\]\s-]{0,2}?\d{3}[\s-]?\d{4}$/i",$pho)) {
	$error_msg[] = "Invalid phone format";
}

$limit = 1000;

if (empty($com) || !preg_match("/^[0-9\/\-\s'\(\)!\?\.,:;\pL]+$/u", $com) || (strlen($com) > $limit)) { 
$error_msg[] = "The Comments field must contain only letters, digits, spaces and basic punctuation (&nbsp;'&nbsp;-&nbsp;,&nbsp;.&nbsp;:&nbsp;;&nbsp;/ and parentheses), and has a limit of 1000 characters";
}

if (!empty($spa) && !($spa == "4" || strtolower($spa) == "four")) {
    echo "You failed the bot test!";
    exit ();
}

// Assuming there's an error, refresh the page with error list and repeat the form

if ($error_msg) {
echo '<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Error</title>
<link rel="stylesheet" href="style.css">
<style>
	.hide {display: none;}
	.err {color: red; font-size: 0.875em; margin: 1em 0; padding: 0 2em;}
</style>
</head>
<body>
	<div class="content">
		<h1>Error!</h1>
		<p>Unfortunately, your message could not be sent. The form as you filled it out is displayed below. Make sure each field completed, and please also address any issues listed below:</p>
		<ul class="err">';
foreach ($error_msg as $err) {
echo '<li>'.$err.'</li>';
}
echo '</ul>
	<form method="post" action="', $_SERVER['PHP_SELF'], '">
		<div>
			<label for="name">Name</label>
			<input name="name" type="text" size="40" maxlength="60" id="name" value="'; if (isset($_POST["name"])) {echo $nam;}; echo '">
		</div>
		<div>
			<label for="email">Email Address</label>
			<input name="email" type="email" size="40" maxlength="60" id="email" value="'; if (isset($_POST["email"])) {echo $ema;}; echo '">
        </div>
        <div>
			<label for="pho">Phone</label>
			<input name="phone" type="phone" size="40" maxlength="60" id="phone" value="'; if (isset($_POST["phone"])) {echo $pho;}; echo '">
		</div>
		<div>
			<label for="comm">Comments</label>
			<textarea name="comments" rows="10" cols="50" id="comm">'; if (isset($_POST["comments"])) {echo $com;}; echo '</textarea>
		</div>
		<div class="hide">
			<label for="spam">What is two plus two?</label>
			<input name="spam" type="text" size="4" id="spam">
		</div>
		<div>
			<input type="submit" name="submit" value="Send">
		</div>
	</form>
</body>
</html>';
exit();
} 

$email_body = 
	"Name of sender: $nam\n\n" .
    "Email of sender: $ema\n\n" .
    "Phone of sender: $pho\n\n" .
    "COMMENTS:\n\n" .
	"$com" ; 

// Assuming there's no error, send the email and redirect to Thank You page

if (isset($_REQUEST['comments']) && !$error_msg) {
mail ($your_email, $subject, $email_body, "From: $nam <$ema>" . "\r\n" . "Reply-To: $nam <$ema>" . "Content-Type: text/plain; charset=utf-8");
header ("Location: $thanks_page");
exit();
}  
}