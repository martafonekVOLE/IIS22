<?php
require 'db.php';
session_start();
ini_set('display_errors', 1);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Page</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css"> -->

    <!--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    -->    
    <link rel="stylesheet" href="css/LandingPageStyle.css">
    <link rel="stylesheet" href="css/calendarStyle.css">
</head>
<body>
  <div class="navbar">
    <?php 
        if(isset($_SESSION['logged_in'])){
            if(($_SESSION['logged_in'] == true)){
                echo "<img class='roundedAvatar' onclick='redirectToProfile()' src='others/avatar.png' alt='Profile photo' width='30' height='30'><span class='roundedAvatarDetails'>". $_SESSION["first_name"] . " " . $_SESSION["last_name"] . "</span>";
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
    <a href="testCale.php#room_booking">Book a Room</a>
    <a class="active" href="testCale.php">Calendar</a>
    <a href="index.php">Home</a>
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
    </div>
  </div>

  <div class="calendarSpan">
            <h2>Lectures Calendar</h2>
            <ul class="colorPalette">
            <li class="colorLi"><span class="colorBox yellow"></span><span class="colorText">Your valid reservation</span></li>
            <li class="colorLi"><span class="colorBox red"></span><span class="colorText">This lecture is full</span></li>
            <?php
                $result = $mysqli->query("SELECT DISTINCT * FROM lecture_types") or die($mysqli->error);
                while($row = ($result->fetch_assoc())) {
                    if($row['color'] == ""){
                    }
                    else{
                        $req = $row['id'];
                        $printHint = "";
                        $res = $mysqli->query("SELECT name FROM lecture WHERE lecture_type ='$req'") or die($mysqli->error) ;
                        if(mysqli_num_rows($res) != 0){
                            $res = $res->fetch_assoc();
                            $printHint = "<li class='colorLi'><span class='colorBox " . $row['color'] . "'></span>";
                            $printHint .= "<span class='colorText'>" . $res['name'] . "</span></li>";
                        }
                        echo $printHint;
                    }
                }
            ?>
            </ul>
            <div class="moveContent">
                <div class="smallPhoneScale">
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
            </div>
    </div>

    <div class="bookRoomSpan">
            <h2 id="room_booking">Book a private room</h2>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Accusantium quas a at, asperiores debitis eligendi illum. Debitis nam non doloribus cum quo aliquam accusamus! Delectus blanditiis voluptatibus veniam odio a!</p>

                <?php
                $result = $mysqli->query("SELECT id, name, max, description, img FROM rooms");
                while ($row = $result->fetch_assoc())
                {
                    $room_id = $row['id'];
                    $room_name = $row['name'];
                    $room_description = $row['description'];
                    $room_image = "./img/" . $row['img'];
                    $room_max = $row['max'];

                    $room_post = 'roomReservation.php?room_id=' . $room_id;
                    echo("
                        <div class='userColCard' >
                            <div class='card'>
                                <img src='$room_image' class='card-img-top' alt='obrázek'>
                                <div class='card-body'>
                                    <h5 class='card-title'>$room_name</h5>
                                    <p class='card-text dark'>$room_description</p>
                                    <ul class='list-group list-group-flush'>
                                        <li class='list-group-item'>Capacity: $room_max </li>
                                        <li class='list-group-item'>
                                            <a href='$room_post'>
                                                Book room
                                            </a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    ");
                }
                ?>
    </div>
  <script>
  function redirectToProfile(){
  window.location.href = "profile.php";
  }
  </script>
</body>
</html>
