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
            Create/Edit Lecture
        </title>
        <?php
            include 'css/css.html';
        ?>
    </head>
    <body>
        <?php
            require 'navbar.php';
        ?>
        <form class='form searchbar' name='edit_user' action='editLecturesAdmin.php' method='post' autocomplete='off'>
            <h2 style="color: #fff">Create/Edit Lecture</h2>
        <?php
            function date_convert_to_web($date)
            {
                $date_part = explode(" ", $date);
                $date = $date_part[0] . "T" . $date_part[1];
                $date = substr($date, 0, -3);
                if (strlen($date) != 11)
                {
                    $_SESSION['message'] = "Invalid date format";
                    return false;
                }

                return $date;
            }

            function date_convert_to_db($date)
            {
                $date = str_replace("T", " ", $date);
                $date = $date . ":00";
                if (strlen($date) != 20)
                {
                    $_SESSION['message'] = "Invalid date format";
                }

                return $date;
            }

            // Obtaining values
            $name = "";

            if (isset($_POST['name']))
            {
                $name = $_POST['name'];
            }

            if (isset($_POST['new_name']))
            {
                $name = $mysqli->escape_string($_POST['new_name']);
            }

            $capacity = 0;

            if (isset($_POST['capacity']))
            {
                $capacity = $_POST['capacity'];
            }

            if (isset($_POST['new_capacity']))
            {
                $capacity = $mysqli->escape_string($_POST['new_capacity']);
            }
            
            $time_start = "";

            if (isset($_POST['time_start']))
            {
                $time_start = date_convert_to_web($_POST['time_start']);
            }

            if (isset($_POST['new_time_start']))
            {
                $time_start = $mysqli->escape_string($_POST['new_time_start']);
            }

            $time_end = "";

            if (isset($_POST['time_end']))
            {
                $time_end = date_convert_to_web($_POST['time_end']);
            }

            if (isset($_POST['new_time_end']))
            {
                $time_end = $mysqli->escape_string($_POST['new_time_end']);
            }

            $type = "";

            if (isset($_POST['new_type']))
            {
                $type = $mysqli->escape_string($_POST['new_type']);
            }

            $description = "";

            if (isset($_POST['description']))
            {
                $description = $_POST['description'];
            }

            if (isset($_POST['new_description']))
            {
                $description = $mysqli->escape_string($_POST['new_description']);
            }

            $room_id = "";

            if (isset($_POST['new_room']))
            {
                $room_id = $mysqli->escape_string($_POST['new_room']);
            }

            $lecturer_id = "";

            if (isset($_POST['new_lecturer']))
            {
                $lecturer_id = $mysqli->escape_string($_POST['new_lecturer']);
            }

            $lecture_type = "";

            if (isset($_POST['new_lecture_type']))
            {
                $lecture_type = $mysqli->escape_string($_POST['new_lecture_type']);
            }

            // Update if lecture_id is posted
            if (isset($_POST['lecture_id']) && isset($_POST['new_name']))
            {
                $lecture_id = $_POST['lecture_id'];
                // Update DB values when input is OK
                if (!ctype_digit($capacity))
                {
                    $_SESSION['message'] = "Kapacita musí být celé kladné číslo!";
                }
                else if (!($time_start = date_convert_to_db($time_start)));
                else if (!($time_end = date_convert_to_db($time_end)));
                else
                {
                    $mysqli->query("UPDATE lecture SET `name`='$name', capacity=$capacity, time_start='$time_start', time_end='$time_end', `type`='$type', `description`='$description', room_id='$room_id', lecturer_id='$lecturer_id', lecture_type=$lecture_type WHERE id='$lecture_id'")
                    or die($mysqli->error);
                    $_SESSION['message'] = "Editace proběhla úspěšně";
                }
            }
            
            if (isset($_POST['new_name']) && !(isset($_POST['lecture_id'])))
            {
                // Insert into DB when input is OK
                if (!ctype_digit($capacity))
                {
                    $_SESSION['message'] = "Kapacita musí být celé kladné číslo!";
                }
                else if (!($time_start = date_convert_to_db($time_start)));
                else if (!($time_end = date_convert_to_db($time_end)));
                else
                {
                    $mysqli->query("INSERT INTO lecture (`name`, capacity, time_start, time_end, `type`, `description`, room_id, lecturer_id, lecture_type) VALUES ('$name', $capacity, '$time_start', '$time_end', '$type', '$description', $room_id, $lecturer_id, $lecture_type)")
                    or die($mysqli->error);
                    $_SESSION['message'] = "Lekce byla úspěšně vytvořena";
                }
            }

            // Generating forms
            echo ("
                <div class='field-wrap'>
                    <label>
                        Name<span class='req'>*</span>
                    </label>
                    <input value='$name' required type='text' autocomplete='off' name='new_name'/>
                </div>
                
                 <div class='field-wrap'>
                    <label>
                        Capacity<span class='req'>*</span>
                    </label>
                    <input value='$capacity' required type='text' autocomplete='off' name='new_capacity'/>
                </div>      
                
                <div class='field-wrap'>
                    <label>
                        From<span class='req'>*</span>
                    </label>
                    <input value='$time_start' required type='datetime-local' autocomplete='off' name='new_time_start'/>
                </div>         
                
                <div class='field-wrap'>
                    <label>
                        To<span class='req'>*</span>
                    </label>
                    <input value='$time_end' required type='datetime-local' autocomplete='off' name='new_time_end'/>
                </div>   
                
                <div class='field-wrap'>
                    <label>
                        Description<span class='req'></span>
                    </label>
                    <input value='$description' required type='text' autocomplete='off' name='description'/>
                </div>  
                
                <div class='field-wrap'>
                    <label class='selectLabel'>
                        Type<span class='req'>*</span>
                    </label>
                    <select name='new_type'>
                        <option value='Group'>Skupinová</option>
                        <option value='Individual'>Individuální</option>
                    </select>
                </div>   
                
                <div class='field-wrap'>
                    <label class='selectLabel'>
                        Room<span class='req'>*</span>
                    </label>
                    <select name='new_room'>

            ");
            
            $result_rooms = $mysqli->query("SELECT * FROM rooms") or die($mysqli->error);
            while ($row = $result_rooms->fetch_array())
            {
                $room_id = $row['id'];
                $room_name = $row['name'];
                echo "<option value='$room_id'>$room_name</option>";
            }
            
            echo "</select></div>";
            echo "<div class='field-wrap'>
                  <label class='selectLabel'>
                        Trainer<span class='req'>*</span>
                    </label> 
                    <select name='new_lecturer'>";

            $result_lecturers = $mysqli->query("SELECT * FROM employees WHERE role='trainer'") or die($mysqli->error);
            while ($row = $result_lecturers->fetch_array())
            {
                $lecturer_id = $row['id'];
                $lecturer_first_name = $row['first_name'];
                $lecturer_last_name = $row['last_name'];

                echo "<option value='$lecturer_id'>$lecturer_first_name $lecturer_last_name</option>";
            }

            echo "</select></div>";
            
            echo "<div class='field-wrap'>
                  <label class='selectLabel'>
                        Category<span class='req'>*</span>
                    </label>
                    <select name='new_lecture_type'> ";


            $result_lecturers = $mysqli->query("SELECT * FROM lecture_types") or die($mysqli->error);
            while ($row = $result_lecturers->fetch_array())
            {
                $lecture_type_id = $row['id'];
                $lecture_name = $row['name'];

                echo "<option value='$lecture_type_id'>$lecture_name</option>";
            }

            echo "</select></div>";
            
            if (isset($_POST['lecture_id']))
            {
                $lecture_id = $_POST['lecture_id'];
                echo "<input type='hidden' value='$lecture_id' name='lecture_id' />";
                echo "<button name='edit_button'>Editovat</button>";
                echo "<input form='remove' type='hidden' value='$lecture_id' name='lecture_id' />";
                echo "<button form='remove' name='remove_button'>Remove</button>";
            }
            else
            {
                echo "<button name='edit_button'>Vytvořit</button>";
            }
        ?>
        </form>
        <form id='remove' name='remove_user' action='removeLecturesAdmin.php' method='post' autocomplete='off'></form>
        <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

        <script src="js/index.js"></script>
    </body>
<html>
