<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Errors display
require 'timeout.php';

if(!isset($_SESSION['logged_in'])){
    $_SESSION['logged_in'] = false;
}

// Check if user is logged in using the session variable
if ( $_SESSION['logged_in'] != true ) {
}
else{
}

if(!isset($_SESSION['logged_in_e'])){
    $_SESSION['logged_in_e'] = false;
}

error_reporting(E_ALL ^ E_NOTICE);
setlocale(LC_ALL, 'cs_CZ.UTF-8');

function build_calendar($week, $year, $currentWeek, $room_id) {

    // DB connection is required for this
    include 'db.php';

    if(isset($_POST['reservation'])) {
        $timestamp = $_POST['reservation'];
        $timestamp = explode("_", $timestamp);
        $timestamp = $timestamp[0] . " " . $timestamp[1];

        $timestamp = explode("!", $timestamp);
        $roomId = $timestamp[1];
        $showTime = $timestamp[2];
        $timestamp = $timestamp[0];

        if(strpos($roomId, "+") != false){
            $roomId = explode("+", $roomId);
            $roomId = $roomId[0];
            $tokenRoom = true;
        }
        else{
            $tokenRoom = false;
        }


        $timestampTo = date_create_from_format('Y-m-d H:i:s', $timestamp);
        $timestampTo->getTimestamp();


        if($showTime == 2){
            $timestampTo->add(new DateInterval('PT30M'));
        }
        else{
            $timestampTo->add(new DateInterval('PT1H'));

        }
        $timestampTo = $timestampTo->format('Y-m-d H:i:s');


        $userEmail = $_SESSION['email'];
        if($_SESSION['logged_in_e'] == false){
            $userId = $mysqli->query("SELECT id FROM users WHERE email='$userEmail'");
        }
        else{
            $userId = $mysqli->query("SELECT id FROM employees WHERE email='$userEmail'");
        }

        $userId = $userId->fetch_assoc()['id'];
        if($tokenRoom == true){


            //TODO nepovolit registrace zpětné
            //TODO odregistrace do 24h??

            $mysqli->query("INSERT INTO reservation (date_start, date_end, room_id, client_id) VALUES ('$timestamp', '$timestampTo', $roomId, $userId)");
        }
        else{
            $result = $mysqli->query("SELECT id FROM lecture WHERE time_start='$timestamp'");
            $result = $result->fetch_assoc()['id'];
            $actualCapacity = $mysqli->query("SELECT COUNT(id) FROM reservation_lecture WHERE lecture_id='$result'");
            $actualCapacity = $actualCapacity->fetch_row();

            $result = $mysqli->query("SELECT capacity FROM lecture WHERE time_start='$timestamp'");

            $checkExistence = $mysqli->query("SELECT * FROM reservation WHERE date_start='$timestamp' AND client_id=$userId");

            if($actualCapacity[0] < $result && mysqli_num_rows($checkExistence) == 0){
                $mysqli->query("INSERT INTO reservation (date_start, date_end, room_id, client_id) VALUES ('$timestamp', '$timestampTo', $roomId, $userId)");

                $reservationId = $mysqli->query("SELECT id FROM reservation WHERE date_start='$timestamp' AND client_id=$userId");
                $reservationId = $reservationId->fetch_assoc()['id'];

                $lectureId = $mysqli->query("SELECT id FROM lecture WHERE time_start='$timestamp'");
                $lectureId = $lectureId->fetch_assoc()['id'];

                $mysqli->query("INSERT INTO reservation_lecture (reservation_id, lecture_id) VALUES ($reservationId, $lectureId)");
            }
            else{
                if(mysqli_num_rows($checkExistence) != 0){
                    $_SESSION['message'] = "Whoops! <br>Something went wrong - You can only register from one tab!";
                }
                else{
                    $_SESSION['message'] = "Whoops! <br>Something went wrong - lecture is already full!";
                }
                ?>
                <script type="text/javascript">
                window.location.href = 'error.php';
                </script>
                <?php
            }
        }


    }


    // Todays date
    $datetoday = date('Y-m-d');

    $calendar = "<table class='table-bordered contentTable calendar'>";
    //$calendar .= "<center><h2>$month_Name $year</h2>";

    // Zobraz aktuální týden
    if(!isset($week)){
        $week = date('W', strtotime($datetoday));
        $week = intval($week);
    }
    if(!isset($currentWeek)){
        $currentWeek = 0;
    }

    $firstMondayOfTheWeek = date("d-m-Y", strtotime('monday '. $currentWeek .'week'));

    $calendar .= "<tr>";
    $calendar .= "<th class='header'> </th>";

    if($room_id == -1){
        $navigation = "</table><div class='calendarNavi'><a class='naviLeft' href='?week=".($week-1)."&default=".$currentWeek."&way=".-(1)."&year=".$year."'>Previous Week</a> ";
        $navigation.= " <a class='naviCenter' href='?week=".date('W')."&default=".$currentWeek."&way=".(0)."&year=".date('Y')."'>Current Week</a> ";
        $navigation.= "<a class='naviRight' href='?week=".($week+1)."&default=".$currentWeek."&way=".(1)."&year=".$year."'>Upcoming Week</a></center></div><br>";
    }
    else{
        $navigation = "</table><div class='calendarNavi'><a class='naviLeft' href='?week=".($week-1)."&default=".$currentWeek."&way=".-(1)."&year=".$year."&room_id=".$room_id."'>Previous Week</a> ";
        $navigation.= " <a class='naviCenter' href='?week=".date('W')."&default=".$currentWeek."&way=".(0)."&year=".date('Y')."&room_id=".$room_id."'>Current Week</a> ";
        $navigation.= "<a class='naviRight' href='?week=".($week+1)."&default=".$currentWeek."&way=".(1)."&year=".$year."&room_id=".$room_id."'>Upcoming Week</a></center></div><br>";
    }


    // Responsible for calendar hours / show half hours
    $workingHours = array(
        "starts" => new DateTime('08:00:00'),
        "ends" => new DateTime('20:00:00'),
    );

    if(file_exists("others/workingHours.txt")){
        $workingHoursFile = fopen("others/workingHours.txt", "r") or die("Unable to open file.");
    }

    if(isset($workingHoursFile)){
        $fromTo = fread($workingHoursFile, filesize("others/workingHours.txt"));
        fclose($workingHoursFile);

        $fromTo = explode(" ", $fromTo);
    }

    if(isset($fromTo[0])){
        $workingHours["starts"] = new DateTime($fromTo[0]);
    }
    if(isset($fromTo[1])){
        $workingHours["ends"] = new DateTime($fromTo[1]);
    }
    $showHalfHour = false;
    if(isset($fromTo[2])){
        if($fromTo[2] == "false"){
            $showHalfHour = false;
        }
        else if($fromTo[2] == true){
            $showHalfHour = true;
        }
        else{
            $showHalfHour = $showHalfHour;
        }
    }

    $multiplier = 1;
    if($showHalfHour == true){
        $multiplier = 2;
    }

    // Processing working hours
    $startingHour = $workingHours["starts"];
    $startingHour = $startingHour->format("m");
    if($startingHour == 30){
        $halftime = 1;
    }
    else{
        $halftime = 0;
    }
    $amountOfWorkingHours = $workingHours["ends"]->diff($workingHours["starts"]);

    // Create the calendar headers based on time slots given
    for($i = 0; $i < $multiplier * $amountOfWorkingHours->h; $i++){
        $halftimeSwitch = false;

        if($showHalfHour == false){
            $startingHour = $workingHours["starts"];
            $startingHour = $startingHour->format("h");
            $startingHour = $startingHour + ($i);
        }
        else{
            if($halftime % 2 == 0){
                $startingHour = $workingHours["starts"];
                $startingHour = $startingHour->format("h");
                $startingHour = $startingHour + ($i / 2);
            }
            else{
                $halftimeSwitch = true;
            }
        }

        if($halftimeSwitch == true){
            $calendar .= "<th class='header'> $startingHour:30 </th>";
        }
        else{
            $calendar .= "<th class='header'> $startingHour </th>";
        }
        $halftime += 1;

    }

    // Create the rest of the calendar

    // Initiate the day counter, starting with the 1st.

    $currentDay = 1;

    $calendar .= "</tr><tr>";

    // The variable $dayOfWeek is used to
    // ensure that the calendar
    // display consists of exactly 7 columns.
    $daysOfWeekNames = array("Sunday", "Saturday", "Friday", "Thursday", "Wednesday", "Tuesday", "Monday");
    $daysOfWeekNamesEn = array("Sunday", "Saturday", "Friday", "Thursday", "Wednesday", "Tuesday", "Monday");


    $startingHour = $workingHours["starts"];
    $startingHour = $startingHour->format("m");
    if($startingHour == 30){
        $halftime = 1;
    }
    else{
        $halftime = 0;
    }


    for($j = 0; $j < 7; $j++){
        if ($amountOfWorkingHours->h > 0) {
            for($i = 0; $i < ($amountOfWorkingHours->h * $multiplier) + 1; $i++){
                if($i == 0){

                    $newDate = date('d-m-Y');
                    $newDate = date('l', strtotime($newDate));
                    $day = array_pop($daysOfWeekNamesEn);

                    $dayNumber = compareDays($day);
                    $newDate = compareDays($newDate);

                    if($dayNumber < $newDate){
                        $dateToday = date("d-m-Y", strtotime($day.' '. ($currentWeek - 1) .'week'));
                        $calendar .= "<td><div>".array_pop($daysOfWeekNames)."<br><span class='date'>".$dateToday."</span></div></td>";
                    }
                    else{
                        $dateToday = date("d-m-Y", strtotime($day.' '. $currentWeek .'week'));
                        $calendar .= "<td><div>".array_pop($daysOfWeekNames)."<br><span class='date'>".$dateToday."</span></div></td>";
                    }

                   
                }
                else{
                    $halftimeSwitch = false;

                    if($showHalfHour == false){
                        $startingHour = $workingHours["starts"];
                        $startingHour = $startingHour->format("h");
                        $startingHour = $startingHour + ($i) - 1;
                    }
                    else{
                        if($halftime % 2 == 0){
                            $startingHour = $workingHours["starts"];
                            $startingHour = $startingHour->format("h");
                            $startingHour = $startingHour + ($i - 1) / 2;
                        }
                        else{
                            $halftimeSwitch = true;
                        }
                    }
                    if($halftimeSwitch == true){
                        $datetoday = $dateToday . " " . $startingHour . ":30";
                    }
                    else{
                        $datetoday = $dateToday. " " . $startingHour . ":00";
                    }
                    $halftime += 1;

                    // Fixing date format
                    $datetoday = fixFormat($datetoday);

                    // Checking if any lesson is set
                    if($room_id == -1){
                        $result = $mysqli->query("SELECT id FROM lecture WHERE time_start='$datetoday'");
                    }
                    else{
                        $result = $mysqli->query("SELECT id FROM lecture WHERE time_start='$datetoday' and room_id='$room_id'");
                    }
                    $name = $mysqli->query("SELECT name FROM lecture WHERE time_start='$datetoday'");
                    $lectureType = $mysqli->query("SELECT type FROM lecture WHERE time_start='$datetoday'");
                    $capacity = $mysqli->query("SELECT capacity FROM lecture WHERE time_start='$datetoday'");
                    $cap = $mysqli->query("SELECT capacity FROM lecture WHERE time_start='$datetoday'");
                    $time_start = $mysqli->query("SELECT time_start FROM lecture WHERE time_start='$datetoday'");
                    $time_end = $mysqli->query("SELECT time_end FROM lecture WHERE time_start='$datetoday'");
                    $lecturerId = $mysqli->query("SELECT lecturer_id FROM lecture WHERE time_start='$datetoday'");
                    $description = $mysqli->query("SELECT description FROM lecture WHERE time_start='$datetoday'");
                    $roomId = $mysqli->query("SELECT room_id FROM lecture WHERE time_start='$datetoday'");


                    if($_SESSION['logged_in'] == true && $_SESSION['logged_in_e'] == false){
                        $userEmail = $_SESSION['email'];
                        $userId = $mysqli->query("SELECT id FROM users WHERE email='$userEmail'");
                        $userId = $userId->fetch_assoc()['id'];
                        $reservationId = $mysqli->query("SELECT id FROM reservation WHERE date_start='$datetoday' AND client_id=$userId");    
                    }
                    else if($_SESSION['logged_in'] == true && $_SESSION['logged_in_e'] == true){
                        $userEmail = $_SESSION['email'];
                        $userId = $mysqli->query("SELECT id FROM employees WHERE email='$userEmail'");
                        $userId = $userId->fetch_assoc()['id'];
                        $reservationId = $mysqli->query("SELECT id FROM reservation WHERE date_start='$datetoday' AND client_id=$userId");
                    }

                    $tokenRoomAvailable = true;
                    if($room_id != -1){
                        $roomResult = $mysqli->query("SELECT * FROM reservation WHERE room_id=$room_id AND date_start='$datetoday'");
                        if(mysqli_num_rows($roomResult) != 0){
                            $tokenRoomAvailable = false;
                        }
                    }

                    $datetoday = explode(" ", $datetoday);
                    $datetoday = $datetoday[0] ."_". $datetoday[1];

                    // div id = DATE_HOUR
                    $calendar .= "<td class='timeBlock ";

                    if($tokenRoomAvailable == false){
                        $calendar .= " lectureAlreadyRegistered'";
                    }
                    // if record is set, assign class
                    if (mysqli_num_rows($result) != 0) {
                        $result = $result->fetch_assoc()['id'];
                        $actualCapacity = $mysqli->query("SELECT COUNT(id) FROM reservation_lecture WHERE lecture_id='$result'");
                        $actualCapacity = $actualCapacity->fetch_row();

                        if($_SESSION['logged_in'] == true){
                            if(mysqli_num_rows($reservationId) != 0 ){
                                $calendar .= " lectureAlreadyRegistered'>";
                            }
                            else{
                                $cap = $cap->fetch_assoc()['capacity'];
                                if($actualCapacity[0] >= $cap){
                                    $calendar .= " lectureFull ";
                                }
                                else{
                                    $calendar .= " lectureEmpty ";
                                    $roomColor = $mysqli->query("SELECT lecture_type FROM lecture WHERE id='$result'");
                                    $roomColor = $roomColor->fetch_assoc()['lecture_type'];
                                    $roomColor = $mysqli->query("SELECT color FROM lecture_types WHERE id='$roomColor'");
                                    if(mysqli_num_rows($roomColor) != 0){
                                        $roomColor = $roomColor->fetch_assoc()['color'];
                                        $calendar .= "$roomColor";
                                    }
                                }
                                $calendar .= "'><div class='lectureType'>";
                            }
                        }
                        else{
                            $cap = $cap->fetch_assoc()['capacity'];
                            if($actualCapacity[0] > $cap){
                                $calendar .= " lectureFull ";
                            }
                            else{
                                $calendar .= " lectureEmpty ";
                                $roomColor = $mysqli->query("SELECT lecture_type FROM lecture WHERE id='$result'");
                                $roomColor = $roomColor->fetch_assoc()['lecture_type'];
                                $roomColor = $mysqli->query("SELECT color FROM lecture_types WHERE id='$roomColor'");
                                if(mysqli_num_rows($roomColor) != 0){
                                    $roomColor = $roomColor->fetch_assoc()['color'];
                                    $calendar .= "$roomColor";
                                }
                            }
                            $calendar .= "'><div class='lectureType'>";
                        }




                        if(mysqli_num_rows($lectureType) != 0){
                            $lectureType = $lectureType->fetch_assoc()['type'];
                            if($lectureType == "Group"){
                                $calendar .= "Skupinová Lekce</div>";
                            }
                            else if($lectureType == "Individual"){
                                $calendar .= "Individuální lekce</div>";
                            }
                            else{
                                $calendar .= "</div>";
                            }
                        }
                        if(mysqli_num_rows($name) != 0){
                            $name = $name->fetch_assoc();
                            $calendar .= "<div class='lectureName'>" . $name['name'] . "</div>";
                        }

                        if($room_id == -1){
                            $calendar .= "<span class='timeBlockDetails'><h2>";
                        }
                        else{
                            $calendar .= "<span class='displayNo'><h2>";
                        }

                        if ($name != NULL){
                            $calendar .= $name['name'] . "</h2><p>";
                        }
                        if(mysqli_num_rows($time_start) != 0){
                            $time = explode(" ", $time_start->fetch_assoc()['time_start']);
                            $date = explode("-", $time[0]);
                            $calendar .= "<br>" . $date[2] . "." . $date[1] . "." . $date[0] . "<br>";
                            $calendar .= $time[1];
                        }
                        if(mysqli_num_rows($time_end) != 0){
                            $time = explode(" ", $time_end->fetch_assoc()['time_end']);
                            $calendar .= " - " . $time[1];
                        }
                        if(mysqli_num_rows($lecturerId) != 0){
                            $lecturerId = $lecturerId->fetch_assoc()['lecturer_id'];
                            $thisName = $mysqli->query("SELECT * FROM employees WHERE id=$lecturerId");
                            $thisSurname = $mysqli->query("SELECT * FROM employees WHERE id=$lecturerId");
                            $thisName = $thisName->fetch_assoc()['first_name'];
                            $thisSurname = $thisSurname->fetch_assoc()['last_name'];
                                $calendar .= "<br>Trainer: <b>" . $thisName . " " . $thisSurname . "</b>";// - TODO FK do tabulky trenéru (bude provedeno)";
                        }
                        if(mysqli_num_rows($capacity) != 0){
                            $capacity = $capacity->fetch_assoc()['capacity'];
                            $calendar .= "<br>Capacity: $actualCapacity[0]/" . $capacity;
                        }
                        if(mysqli_num_rows($description) != 0){
                            $calendar .= "<br>" . $description->fetch_assoc()['description'] . "<hr>";
                        }
                        if(mysqli_num_rows($roomId) != 0){
                            $roomId = $roomId->fetch_assoc()['room_id'];
                        }

                        if($_SESSION['logged_in_e'] == true){
                            $calendar .= "<br><button title='You cannot book a room while being in employee account.' disabled>Book</button><div>You cannot book a room while being in employee account.</div></form>";
                        }
                        else if(($_SESSION['logged_in'] == true) && ($_SESSION['active'] == true) && (mysqli_num_rows($reservationId) == 0) && ($actualCapacity[0] < $capacity)){
                            $calendar .= "<br><form method='post'><button type='submit' class='reservation' name='reservation' value='$datetoday!$roomId!$multiplier'>Book</button></form>";
                        }
                        else if(($_SESSION['logged_in'] == true) && ($_SESSION['active'] == false) && (mysqli_num_rows($reservationId) == 0) && ($actualCapacity[0] < $capacity)) {
                            $calendar .= "<br><button title='In order to book lesson, please verify your account.' disabled>Book</button><div>In order to book lesson, please verify your account.</div></form>";
                        }
                        else if(($actualCapacity[0] >= $capacity)){
                            $calendar .= "<br><button title='Lecture is full.' disabled>Book</button><div>Lecture is full.</div></form>";
                        }
                        else{
                            $calendar .= "<br><button title='In order to book lesson, please log into your account first.' disabled>Book</button><div>In order to book lesson, please log into your account first.</div></form>";
                        }

                            $calendar .= "</p>
                        </span>";
                    }
                    else{
                        if($room_id == -1){
                            $calendar .= "'>";
                        }
                        else{
                            $roomResult = $mysqli->query("SELECT * FROM reservation WHERE room_id=$room_id AND date_start='$datetoday'");
                            if(mysqli_num_rows($roomResult) == 0){
                                if($_SESSION['logged_in_e'] == true){
                                    $calendar .= "'>";
                                }
                                else{
                                    if($_SESSION['active']) {
                                        $calendar .= "registerRoom'> <span><form method='post'><button type='submit' class='reservation' name='reservation' value='$datetoday!$room_id+room!$multiplier'>Book</button></form></span>";
                                    }
                                    else{
                                        $calendar .= "registerRoom'>";
                                    }
                                }
                            }
                            else{
                                $calendar .= "registerRoom'>";
                            }

                        }
                    }

                    $calendar .= "<div id='$datetoday'></div>
                                 </td>";
                }
            }
            $calendar .= "<tr></tr>";
        }
    }

    echo $calendar;
    echo $navigation;

}
function fixFormat($datetoday){
    $datetoday = explode(" ", $datetoday);
    $datetoday[0] = explode("-", $datetoday[0]);
    $datetoday[1] = explode(":", $datetoday[1]);

    $datetoday[0] = $datetoday[0][2] ."-". $datetoday[0][1] ."-". $datetoday[0][0];
    if(strlen($datetoday[1][0]) == 1){
        $datetoday[1] = " 0" . $datetoday[1][0] .":". $datetoday[1][1] . ":00";
    }
    else{
        $datetoday[1] = " ".$datetoday[1][0] .":". $datetoday[1][1] . ":00";
    }
    $datetoday = $datetoday[0] . $datetoday[1];
    return $datetoday;
}
function compareDays($day){
    switch ($day){
        case "Monday":
            $day = 1;
            break;
        case "Tuesday":
            $day = 2;
            break;
        case "Wednesday":
            $day = 3;
            break;
        case "Thursday":
            $day = 4;
            break;
        case "Friday":
            $day = 5;
            break;
        case "Saturday":
            $day = 6;
            break;
        case "Sunday":
            $day = 7;
            break;
        default: 
            $day = 0;
            break;
    }
    return ($day);
}
?>
