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
if (isset($_POST['lecture_id'])) {
    $id = $_POST['lecture_id'];

    //ALL RES ID
    $result = $mysqli->query("SELECT * FROM reservation_lecture WHERE lecture_id=$id") or die($mysqli->error);
    if($result->num_rows !== 0){
        while($row = $result->fetch_array()){
            $resID = $row['reservation_id'];
            $vazID = $row['id'];

            $mysqli->query("DELETE FROM reservation WHERE id=$resID") or die($mysqli->error);
            $mysqli->query("DELETE FROM reservation_lecture WHERE id=$vazID") or die($mysqli->error);

        }
    }
    $mysqli->query("DELETE FROM lecture WHERE id=$id") or die($mysqli->error) ;

    header("location: profile.php");
}


?>