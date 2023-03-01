<?php
include "db.php";
include "utils.php";
session_start();


/**
 * Check if user is logged in
 */
if ($_SESSION['logged_in'] != 1)
{
    $_SESSION['message'] = "Please log in to view this page!";
    header("location: error.php");
}
if(!$_SESSION['logged_in_e']){
    $_SESSION['message'] = "Sorry, this page is only for employees.";
    header("location: error.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>
        Reservation management
    </title>
    <?php
    include 'css/css.html';
    ?>
</head>
<body>
<?php
require 'navbar.php';

// Deleting reservation
if (isset($_POST['to_delete_id']))
{
    $reservation_id = $_POST['to_delete_id'];
    $mysqli->query("DELETE FROM reservation WHERE id='$reservation_id'") or die($mysqli->error);
    $mysqli->query("DELETE FROM reservation_lecture WHERE reservation_id='$reservation_id'") or die($mysqli->error);
    $_SESSION['message'] = "The reservation was successfully deleted";
}

if (isset($_POST['email']))
{
    $email = $_POST['email'];
    $id = $_POST['id'];

    echo "<h1>$email</h1>";

    $result = $mysqli->query("SELECT * FROM reservation WHERE client_id='$id'") or die($mysqli->error);
    if ($result->num_rows === 0){
        $_SESSION['message'] = "User has no reservations.";
    }

    // Generating form
    if ($result->num_rows !== 0)
    {
        $i = 0;

        echo "<div class='form searchbar listOfLectures'>";

        echo "<table>";
        echo "<th>Reservation ID</th><th>Reservation type</th><th>Room</th><th>From</th><th>To</th>";

        while ($row = $result->fetch_array())
        {
            $reservation_id = $row['id'];
            $reservation_type = "Room";
            $type_result = $mysqli->query("SELECT * FROM reservation_lecture WHERE reservation_id='$reservation_id'");
            if($type_result->num_rows != 0){
                $reservation_type = "Lecture";
            }
            $room_id = $row['room_id'];
            $date_start = $row['date_start'];
            $date_end = $row['date_end'];
            $expired_style = "grey";
            $delete_enable = "disabled";
            if(strtotime($date_start) - time() > 0){
                $expired_style = "white";
                $delete_enable = "";
            }

            $result_rooms = $mysqli->query("SELECT * FROM rooms WHERE id='$room_id'") or die($mysqli->error);
            if(mysqli_num_rows($result_rooms) != 0){
                $row = $result_rooms->fetch_array();
                $room_name = $row['name'];
            }

            echo "<form name='edit_user_$i' action='editUserLecturesAdmin.php' method='post' autocomplete='off'>";
            echo "<tr>";
            echo "<td style='color: $expired_style'>$reservation_id</td>";
            echo "<td style='color: $expired_style'>$reservation_type</td>";
            echo "<td style='color: $expired_style'>$room_name</td>";
            echo "<td style='color: $expired_style'>$date_start</td>";
            echo "<td style='color: $expired_style'>$date_end</td>";
            echo "<td>";
            echo "<input type='hidden' value='$email' name='email'>";
            echo "<input type='hidden' value='$id' name='id'>";
            echo "<input type='hidden' value='$reservation_id' name='to_delete_id'>";
            echo "<button $delete_enable name='delete_lecture_button_$i'>Delete</button>";
            echo "<td></tr>";
            echo "</form>";

            $i++;
        }

        echo "</table></div>";
    }
    else{
        $_SESSION['message'] = "User has no reservations.";
        require_once 'utils.php';
        redirect('error.php');

    }
}
?>
</body>
</html>
