<?php
session_start();

include "db.php";
include "utils.php";

if ($_SESSION['logged_in'] != 1) {
    $_SESSION['message'] = "Please log in to access this site";
    redirect("error.php");
}
if($_SESSION['logged_in_e'] == 1){
    $_SESSION['message'] = "Can't register lecture as an employee. Please create your own user profile";
    redirect("error.php");
}

if (isset($_POST['room_res_id'])) {
    $del_id = $_POST['room_res_id'];
    $mysqli->query("DELETE FROM reservation WHERE `reservation`.`id` = $del_id");
    echo("<h3>Reservation has been canceled</h3>");
    require_once 'utils.php';
    redirect('userReservations.php');
}

$email = $_SESSION['email'];
$user_result = $mysqli->query("SELECT id FROM users WHERE email='$email'") or die($mysqli->error());
$user_row = $user_result->fetch_assoc();
$user_id = $user_row['id'];

if (isset($_POST['lecture_id'])) {
    $lecture_id = $_POST['lecture_id'];
    $lecture_remove_result = $mysqli->query("SELECT reservation_lecture.id rlid, reservation.id rid
    FROM `reservation` 
    LEFT JOIN `users` ON `reservation`.`client_id` = `users`.`id` 
    LEFT JOIN `reservation_lecture` ON reservation_lecture.reservation_id = `reservation`.`id`
    LEFT JOIN lecture l on reservation_lecture.lecture_id = l.id
    WHERE client_id='$user_id' and lecture_id=$lecture_id");
    $res = $lecture_remove_result->fetch_assoc();
    $rl_del_id = $res['rlid'];
    $reservation_del_id = $res['rid'];
    $mysqli->query("DELETE FROM reservation_lecture WHERE `id` = $rl_del_id");
    $mysqli->query("DELETE FROM reservation WHERE `id` = $reservation_del_id");
    echo("<h3>You have been unsigned.</h3>");
    redirect('userReservations.php');
}




$room_reservation_result = $mysqli->query("SELECT `reservation`.id rid, reservation.*, `users`.*, `rooms`.*, l.name lname
FROM `reservation` 
    LEFT JOIN `users` ON `reservation`.`client_id` = `users`.`id` 
    LEFT JOIN `rooms` ON `reservation`.`room_id` = `rooms`.`id`
    LEFT JOIN `reservation_lecture` ON reservation_lecture.reservation_id = `reservation`.`id`
    LEFT JOIN lecture l on reservation_lecture.lecture_id = l.id
    WHERE client_id='$user_id' and l.id is null and DATE(date_start) > DATE(NOW())");

$lecture_reservation_result = $mysqli->query("SELECT `reservation`.*, `users`.*, `rooms`.*, l.id lid, l.name lname, l.description ldesc
    FROM `reservation` 
    LEFT JOIN `users` ON `reservation`.`client_id` = `users`.`id` 
    LEFT JOIN `rooms` ON `reservation`.`room_id` = `rooms`.`id` /*FIXME RMV ??*/
    LEFT JOIN `reservation_lecture` ON reservation_lecture.reservation_id = `reservation`.`id`
    LEFT JOIN lecture l on reservation_lecture.lecture_id = l.id
    WHERE client_id='$user_id' and l.id is not null and DATE(date_start) > DATE(NOW())");


?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <title>Sign-Up/Login Form</title>
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

<div class="firstSlide">
    <div class="pageHeader">
        <h1>Perfect Body Gym</h1>
    </div>
<div class='reservation-container' style='padding: 20px'>
    <div class="row">
        <h1>My room reservations</h1>
        <?php
        if ($lecture_reservation_result->num_rows == 0) {
            echo("You have no room reservations");
        }
        while ($row = $room_reservation_result->fetch_assoc()) {
            $reservation_start = date('H:i', strtotime($row['date_start']));
            $reservation_end = date('H:i', strtotime($row['date_end']));
            $day_cz = date('l d. m. o', strtotime($row['date_start']));
            $room = $row['name'];
            if ((strtotime($row['date_start']) - time()) <= 24 * 60 * 60) {
                $can_remove = "disabled";
            } else {
                $can_remove = "";
            }
            $reservation_id = $row['rid'];

            echo("
                <div class='col-sm-9'>
                    <div class='card'>
                       <h5 class='card-title'>$room - $day_cz</h5>
                       <ul class='list-group list-group-flush'>
                            <li class='list-group-item'>Od: $reservation_start - Do: $reservation_end</li>
                            <li class='list-group-item'>Místnost: $room</li>
                            <li class='list-group-item'>
                                <form name='remove_room_res' action='userReservations.php' method='post'>
                                    <input type='hidden' name='room_res_id' value=$reservation_id>
                                    <button type='submit' $can_remove>Zrušit rezervaci</button>
                                </form>
                            </li>
                       </ul>
                    </div>
                </div>
            ");
        } ?>
        <a class="link-btn" href="userExpiredRoomReservations.php">View past room reservations</a>
    </div>
    <div class="row">
        <h1>My lectures</h1>
        <?php
        if ($lecture_reservation_result->num_rows == 0) {
            echo("You have no lectures registered.");
        } else {
            while ($row = $lecture_reservation_result->fetch_assoc()) {
                $reservation_start = date('H:i', strtotime($row['date_start']));
                $reservation_end = date('H:i', strtotime($row['date_end']));
                $day_cz = translate_day(date('l d. m. o', strtotime($row['date_start'])));
                $room = $row['name'];
                $lecture_name = $row['lname'];
                $lecture_desc = $row['ldesc'];
                $lecture_id = $row['lid'];
                if ((strtotime($row['date_start']) - time()) <= 24 * 60 * 60) {
                    $can_remove = "disabled";
                } else {
                    $can_remove = "";
                }
                echo("
                <div class='col-sm-9'>
                    <div class='card'>
                       <h5 class='card-title'>Lecture $lecture_name - $day_cz</h5>
                       <ul class='list-group list-group-flush'>
                            <li class='list-group-item'>From: $reservation_start - to: $reservation_end</li>
                            <li class='list-group-item'>Room: $room</li>
                            <li class='list-group-item'>$lecture_desc</li>
                            <li class='list-group-item'>
                                <form name='remove_room_res' action='userReservations.php' method='post'>
                                    <input type='hidden' name='lecture_id' value=$lecture_id>
                                    <button type='submit' $can_remove>Unsign from lecture</button>
                                </form>
                            </li>
                       </ul>
                    </div>
                </div>
            ");
            }
        }

        ?>
        <a class="link-btn" href="userExpiredLectures.php">View past lectures</a>

    </div>
</div>
</div>
<script>
    function redirectToProfile(){
        window.location.href = "profile.php";
    }
</script>
</body>
</html>
