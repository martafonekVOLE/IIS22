<?php

include "db.php";
session_start();
ini_set('display_errors', 1);

//check if the access to this site is valid
if(isset($_GET['room_id'])){
    $room_id = $_GET['room_id'];
}
else{
    header("viewRooms.php");
}

//check if the user is logged in
if($_SESSION['logged_in'] != 1)
{
    $_SESSION['message'] = "Please sign up to view this page.";
    header("location: error.php");
}


//get info about the room
$result = $mysqli->query("SELECT name, description, img FROM rooms WHERE id=$room_id");
$row = $result->fetch_assoc();
$room_name = $row['name'];
$room_description = $row['description'];
$room_img = $row['img'];

?>

<!DOCTYPE html>
<html>
<head>
    <title>Room Booking</title>
    <link rel="stylesheet" href="css/LandingPageStyle.css">
    <link rel="stylesheet" href="css/calendarStyle.css">
    <style>
        body{
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
        }
        .date{
            color: white;
            font-size: 1rem;
        }

    </style>
</head>

<body>

<div class="navbar" style="text-align: center">
    <?php
    if(isset($_SESSION['logged_in'])){
        if(($_SESSION['logged_in'] == true)){
            echo "<img title='Profile' onclick='redirectToProfile();' class='roundedAvatar' src='others/avatar.png' alt='Profile photo' width='30' height='30'><span class='roundedAvatarDetails'>". $_SESSION["first_name"] . " " . $_SESSION["last_name"] ."</span>";
            echo "<a href='logout.php'>Logout</a>";
        }
        else{
            echo "<a href='login.php'>Sign Up</a>";
        }
    }
    else{
        $_SESSION['logged_in'] = false;
        echo "<a href='login.php'>Sign Up</a>";
    }

    ?>
    <a href="coaches.php">Our coaches</a>
    <a class="active" href="testCale.php#room_booking">Book a Room</a>
    <a href="testCale.php">Calendar</a>
    <a  href="index.php">Home</a>
</div>

<!-- BURGER MENU -->
<div class="burgerWrapper" style="background-color: #fff">
    <label for="menu" class="burger"><span></span></label>
    <?php
    if($_SESSION['logged_in'] == true){
        echo "<img title='Profile' onclick='redirectToProfile();' class='roundedAvatar' src='others/avatar.png' alt='Profile photo' width='30' height='30'>";
    }
    ?>
    <div class="burgerMenu">
        <input type="checkbox" id="menu" class="burger-check">

        <ul>
            <li><a href="coaches.php">Our coaches</a></li>
            <li><a href="testCale.php#room_booking">Book a Room</a></li>
            <li><a href="testCale.php">Calendar</a></li>
            <li><a class="active" href="index.php">Home</a></li>
            <li>

                <?php
                if(isset($_SESSION['logged_in'])){
                    if(($_SESSION['logged_in'] == true)){
                        echo "<a href='logout.php'>Logout</a>";
                    }
                    else{
                        echo "<a href='login.php'>Sign Up</a>";
                    }
                }
                else{
                    $_SESSION['logged_in'] = false;
                    echo "<a href='login.php'>Sign Up</a>";
                }
                ?>

            </li>


        </ul>
    </div>
</div>

<div class="firstSlide">
    <div class="pageHeader">
        <h1>Perfect Body Gym</h1>
    </div>

    <div class='form' style="max-width: 100%; text-align: center">
        <div class='tab-content'>
            <h1 style="color: #fff; margin-top: 20px; margin-bottom: -20px">Room:  <?php echo($room_name) ?>     </h1>
            <!--TODO dat sem kalendar-->


            <div class="calendarSpan">
                <?php
                $dateComponents = getdate();
                if(isset($_GET['week']) && isset($_GET['year']) && isset($_GET['default']) && isset($_GET['way'])){
                    $week = $_GET['week'];
                    $year = $_GET['year'];
                    $currentWeek = $_GET['default'];
                    if($_GET['way'] == -1){
                        $currentWeek = $_GET['default'] - 1;
                    }
                    else if($_GET['way'] == 1){
                        $currentWeek = $_GET['default'] + 1;
                    }
                    else{
                        $currentWeek = 0;
                    }
                }else{
                    $week = date('W');
                    $year = $dateComponents['year'];
                    $currentWeek = 0;
                }

                include 'calendar.php';
                if(isset($room_id)){
                    echo build_calendar($week,$year,$currentWeek,$room_id);
                }else{
                    echo build_calendar($week,$year,$currentWeek, -1);
                }
                ?>
            </div>






            <br>
            
              <a href='testCale.php#book_rooms' style="background-color: #1ab188">  Home</a>
        </div></div>

    <script>
        function redirectToProfile(){
            window.location.href = "profile.php";
        }
    </script>
</body>
</html>
