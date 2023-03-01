<?php
include "db.php";
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


/**
 * Check if user is logged in
 */
if ($_SESSION['logged_in'] != 1)
{
    $_SESSION['message'] = "Please log in to view this page!";
    header("location: error.php");
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>
            Lecture Manager
        </title>
        <?php
            require 'css/css.html';
        ?>
    </head>

    <body>

        <?php
            require 'navbar.php';
            $result = $mysqli->query("SELECT * FROM lecture") or die($mysqli->error);
            
            if ($result->num_rows !== 0)
            {
                $i = 0;
                echo "<div class='form searchbar listOfLectures'>";
                echo "<table>";
                echo "<th>Name</th><th>Capacity</th><th>From</th><th>To</th><th>Manage Lecture</th>";

                while($row = $result->fetch_array())
                {
                    $lecture_id = $row['id'];
                    $lecture_name = $row['name'];
                    $lecture_description = $row['description'];
                    $lecture_capacity = $row['capacity'];
                    $lecture_time_start = $row['time_start'];
                    $lecture_time_end = $row['time_end'];
                    $lecture_room_id = $row['room_id'];

                    echo ("<form name='edit_lecture_$i' action='editLecturesAdmin.php' method='post' autocomplete='off'>");
                    echo "<tr><td>$lecture_name</td> <td>$lecture_capacity</td> <td>$lecture_time_start</td> <td>$lecture_time_end</td><td>";
                    echo ("
                        <input type='hidden' value='$lecture_id' name='lecture_id'>
                        <input type='hidden' value='$lecture_name' name='name'>
                        <input type='hidden' value='$lecture_capacity' name='capacity'>
                        <input type='hidden' value='$lecture_time_start' name='time_start'>
                        <input type='hidden' value='$lecture_time_end' name='time_end'>
                        <input type='hidden' value='$lecture_room_id' name='room_id'>
                        <input type='hidden' value='$lecture_description' name='decscription'>
                    ");
                    echo "<button name='edit_button_$i'>Edit</button></td></tr>";
                    echo "</form>";
                    $i++;
                }
                echo "</table></div>";

            }
        ?>
    </body>
<html>
