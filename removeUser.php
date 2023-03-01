<?php
session_start();

if ($_SESSION['logged_in'] != 1)
{
    $_SESSION['message'] = "Please log in to view this page!";
    header("location: error.php");
}
if(!$_SESSION['logged_in_e']){
    $_SESSION['message'] = "Sorry, this page is only for employees.";
    header("location: error.php");
}
include 'db.php';
if(isset($_POST['email'])){
    $email = $_POST['email'];

    $result = $mysqli->query("SELECT * FROM users WHERE email='$email'") or die($mysqli->error);
    $id = $result->fetch_assoc()['id'];

    if($id != NULL){
        $result = $mysqli->query("SELECT * FROM reservation WHERE client_id=$id") or die($mysqli->error);
    }

    if($result->num_rows !== 0){
        while($row = $result->fetch_array()){
            $resId = $row['id'];

            $mysqli->query("DELETE FROM reservation_lecture WHERE reservation_id=$resId") or die($mysqli->error);
            $mysqli->query("DELETE FROM reservation WHERE id=$resId") or die($mysqli->error);

        }
    }
    $mysqli->query("DELETE FROM users WHERE id=$id") or die($mysqli->error);

    header("location: profile.php");
}


?>