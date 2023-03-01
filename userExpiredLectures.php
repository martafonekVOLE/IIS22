<?php
session_start();

include "db.php";
include "utils.php";


if($_SESSION['logged_in_e'] == 1){
    $_SESSION['message'] = "Please sign in as user to reserve room";
    redirect("error.php");
}

if($_SESSION['logged_in'] != 1)
{
    $_SESSION['message'] = "Please log in to view this page";
    redirect("error.php");
}


$email = $_SESSION['email'];
$user_result = $mysqli->query("SELECT id FROM users WHERE email='$email'") or die($mysqli->error());
$user_row = $user_result->fetch_assoc();
$user_id = $user_row['id'];

$exp_lect_res_result = $mysqli->query("SELECT `reservation`.*, `users`.*, `rooms`.*, l.name lname, l.description ldesc
                FROM `reservation` 
                LEFT JOIN `users` ON `reservation`.`client_id` = `users`.`id` 
                LEFT JOIN `rooms` ON `reservation`.`room_id` = `rooms`.`id`
                LEFT JOIN `reservation_lecture` ON reservation_lecture.reservation_id = `reservation`.`id`
                LEFT JOIN lecture l on reservation_lecture.lecture_id = l.id
                WHERE client_id='$user_id' and l.id is not null and DATE(date_start) <= DATE(NOW())");


?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <title>My past lectures</title>
    <link rel="stylesheet" href="css/LandingPageStyle.css">
    <style>
        .row{
            text-align: center;
        }
        .card{
            width: 60%;
            border: 1px solid;
            background-color: #1ab188;
            margin: auto;
            margin-bottom: 20px;
            padding: 20px;
        }
        .card li{
            list-style: none;
        }
        .link-btn{
            border: none;
            background-color: #1ab188;
        }
    </style>

</head>
<body>

<div class="navbar">
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
<div class="burgerWrapper">
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

<div class="firstSlide" style="text-align: center">
    <div class="pageHeader">
        <h1>Perfect Body Gym</h1>
    </div>



<a class="link-btn" href="userReservations.php">Back to upcoming reservations</a>
<div class='reservation-container' style='padding: 20px'>
    <div class="row">
        <?php
        echo("<h1>My past lectures</h1>");
        if ($exp_lect_res_result->num_rows==0){
            $_SESSION['message'] = "You dont have any past reservations.";
            require_once 'utils.php';
            redirect('error.php');
        }
            while ($row = $exp_lect_res_result->fetch_assoc()) {
                $reservation_start = date('H:i', strtotime($row['date_start']));
                $reservation_end = date('H:i', strtotime($row['date_end']));
                $day_cz = date('l m o', strtotime($row['date_start']));
                $room = $row['name'];
                $lecture_name = $row['lname'];
                $lecture_desc = $row['ldesc'];
                echo("
                <div class='col-sm-9 custom-card'>
                    <div class='custom-card'>
                       <h5 class='card-title'>$lecture_name - $day_cz</h5>
                       <ul class='list-group list-group-flush custom-card'>
                            <li class='list-group-item' style='background: #1ab188'>From: $reservation_start - To: $reservation_end</li>
                            <li class='list-group-item' style='background: #1ab188'>Room: $room</li>
                            <li class='list-group-item' style='background: #1ab188'>$lecture_desc</li>
                       </ul>
                    </div>
                </div>
            ");
            }?>
    </div>
</div></div>
<script>
    function redirectToProfile(){
        window.location.href = "profile.php";
    }
</script>
</body>
</html>