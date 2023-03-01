<?php
require 'db.php';
session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page</title>
    <link rel="stylesheet" href="css/LandingPageStyle.css">
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
    <a href="testCale.php#room_booking">Book a Room</a>
    <a href="testCale.php">Calendar</a>
    <a class="active" href="index.php">Home</a>
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
    <div class="picture">
    </div>
    <div class="intro">
        <p class="aboutme">Hello there, my name is <b>Martin Pech</b> and I am the founder of PB Gym.</p>
        <p><b>We are opened everyday from <?php
                $workingHoursFile = fopen("others/workingHours.txt", "r") or die("Unable to open file.");
                $fromTo = fread($workingHoursFile, filesize("others/workingHours.txt"));
                fclose($workingHoursFile);
                $time = explode(" ", $fromTo);
                echo $time[0] . " to " . $time[1];
                ?></b></p>
        <p>Please note that this content is fictional. It is part of a University project.</p>
        <br>

        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2606.7953514938035!2d16.592583751220957!3d49.20444167922146!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47129440c1428101%3A0x775a22c7ad125178!2sd%C5%AFm%20Tivoli!5e0!3m2!1scs!2scz!4v1596031764439!5m2!1scs!2scz" width="100%" height="300" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
    </div>
    </div>
    
</body>
<script>
    function redirectToProfile(){
        window.location.href = "profile.php";
    }
</script>
</html>

