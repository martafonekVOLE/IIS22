<?php
// Escape all $_POST variables to protect against SQL injections
$first_name = $mysqli->escape_string($_POST['firstname']);
$last_name = $mysqli->escape_string($_POST['lastname']);
$email = $mysqli->escape_string($_POST['email']);
$password = $mysqli->escape_string(password_hash($_POST['password'], PASSWORD_BCRYPT));
$hash = $mysqli->escape_string( md5( rand(0,1000) ) );
$pid = $mysqli->escape_string($_POST['pid']);
$radio = ($_POST['radio']);
$phone = $mysqli->escape_string($_POST['phone']);
$role = $mysqli->escape_string($_POST['role']);

if($radio == 'user'){
    $result = $mysqli->query("SELECT * FROM users WHERE email='$email'") or die($mysqli->error());
    if ( $result->num_rows > 0 ) {
    
        $_SESSION['message'] = 'User with this email already exists!';
        redirect('error.php');
        
    }
    else{
        // active is 0 by DEFAULT (no need to include it here)
        $sql = "INSERT INTO users (first_name, last_name, email, password, hash) " 
        . "VALUES ('$first_name','$last_name','$email','$password', '$hash')";

        // Add user to the database
        if ( $mysqli->query($sql) ){


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

            $_SESSION['message'] = "User was created succesfully.";
            redirect('profile.php');
        }

        else {
            $_SESSION['message'] = 'Registration failed!' . $sql;
            redirect('error.php');
        }
    }
}
else{
    $result = $mysqli->query("SELECT * FROM employees WHERE email='$email'") or die($mysqli->error());
    if ( $result->num_rows > 0 ) {
    
        $_SESSION['message'] = 'User with this email already exists!';
        redirect('error.php');
    }
    else{
        // active is 0 by DEFAULT (no need to include it here)
        $sql = "INSERT INTO employees (first_name, last_name, email, password, hash, pid, role, phone) " 
        . "VALUES ('$first_name','$last_name','$email','$password', '$hash', '$pid', '$role', '$phone')";

        // Add user to the database
        if ( $mysqli->query($sql) ){

        $_SESSION['active'] = 0; //0 until user activates their account with verify.php

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

            $_SESSION['message'] = "Employee was created succesfully.";
            redirect('profile.php');
        }

        else {
            $_SESSION['message'] = 'Registration failed!' . $sql;
            redirect('error.php');
        }
    }

}
function redirect($url)
{
    $string = '<script type="text/javascript">';
    $string .= 'window.location = "' . $url . '"';
    $string .= '</script>';

    echo $string;
}
?>