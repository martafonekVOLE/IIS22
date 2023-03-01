<?php
/* User login process, checks if user exists and password is correct */
require 'db.php';
// Escape email to protect against SQL injections
$email = $mysqli->escape_string($_POST['email']);
$password = $mysqli->escape_string($_POST['password']); //?
$domain = explode("@", $email);

if($domain[1] == "fitness.mpech.net"){
    $domain = "employees";
}
else{
    //user exists
    $domain = "users";
}
$result = $mysqli->query("SELECT * FROM $domain WHERE email='$email'");

if ( $result->num_rows == 0 ){ // User doesn't exist
    $_SESSION['message'] = "User with that email doesn't exist!";
    redirect('error.php');
}
else { // User exists
    $user = $result->fetch_assoc();

    if ( password_verify($_POST['password'], $user['password']) ) {
        
        $_SESSION['email'] = $user['email'];
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['last_name'] = $user['last_name'];
        $_SESSION['active'] = $user['active'];
        $_SESSION['LAST_ACTIVITY'] = time();

        
        // This is how we'll know the user is logged in
        $_SESSION['logged_in'] = true;
        if($domain == "employees"){
            // pro správu uživatelů
            $_SESSION['logged_in_e'] = true;
        }
        else{
            $_SESSION['logged_in_e'] = false;
        }

        redirect('index.php');
    }
    else {
        $_SESSION['message'] = "You have entered wrong password, try again!";
        redirect('error.php');
    }


}

function redirect($url)
{
    $string = '<script type="text/javascript">';
    $string .= 'window.location = "' . $url . '"';
    $string .= '</script>';

    echo $string;
}

