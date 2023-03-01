<?php
/* Registration process, inserts user info into the database 
   and sends account confirmation email message
 */

// Set session variables to be used on profile.php page
$_SESSION['email'] = $_POST['email'];
$_SESSION['first_name'] = $_POST['firstname'];
$_SESSION['last_name'] = $_POST['lastname'];

// Escape all $_POST variables to protect against SQL injections
$first_name = $mysqli->escape_string($_POST['firstname']);
$last_name = $mysqli->escape_string($_POST['lastname']);
$email = $mysqli->escape_string($_POST['email']);
$password = $mysqli->escape_string(password_hash($_POST['password'], PASSWORD_BCRYPT));
$hash = $mysqli->escape_string( md5( rand(0,1000) ) );

// Check the e-mail domain
$domain = explode("@", $email);
if($domain[1] == "fitness.mpech.net"){
    $_SESSION['message'] = 'Sorry, employee accounts can only be created by other employees.';
    redirect('profile.php');

    $domain = "employees";
}
else{
    $domain = "users";
    // Check if user with that email already exists
}
$result = $mysqli->query("SELECT * FROM $domain WHERE email='$email'") or die($mysqli->error());

// We know user email exists if the rows returned are more than 0
if ( $result->num_rows > 0 ) {
    
    $_SESSION['message'] = 'User with this email already exists!';
    redirect('profile.php');
    
}
else { // Email doesn't already exist in a database, proceed...

    // active is 0 by DEFAULT (no need to include it here)
    $sql = "INSERT INTO $domain (first_name, last_name, email, password, hash) " 
            . "VALUES ('$first_name','$last_name','$email','$password', '$hash')";

    // Add user to the database
    if ( $mysqli->query($sql) ){

        $_SESSION['active'] = 0; //0 until user activates their account with verify.php
        $_SESSION['logged_in'] = true; // So we know the user has logged in
        $_SESSION['logged_in_e'] = false;
        $_SESSION['LAST_ACTIVITY'] = time();
        $_SESSION['message'] =
                
                 "Confirmation link has been sent to $email, please verify
                 your account by clicking on the link in the message!";

        // Send registration confirmation link (verify.php)
        $to      = $email;
        $subject = 'Account Verification ( mpech.net )';
        $message_body = '
        Hello <b>'.$first_name.'</b>,<br>

        Thank you for signing up! <br><br>

        Please click this link to activate your account:<br>

        <a href="https://mpech.net/IS/verify.php?email='.$email.'&hash='.$hash.'">https://mpech.net/IS/verify.php?email='.$email.'&hash='.$hash.'</a>';

        require 'mailHandler.php';
        sendMail($message_body, $subject, $to);

        redirect('index.php');

    }

    else {
        $_SESSION['message'] = 'Registration failed!' . $sql;
        redirect('profile.php');

    }

}
function redirect($url)
{
    $string = '<script type="text/javascript">';
    $string .= 'window.location = "' . $url . '"';
    $string .= '</script>';

    echo $string;
}