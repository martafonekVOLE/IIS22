<?php
/* Displays user information and some useful messages */
session_start();

require 'timeout.php';

// Check if user is logged in using the session variable
if ( $_SESSION['logged_in'] != true ) {
  $_SESSION['message'] = "You must log in before viewing your profile page!";
  header("location: error.php");    
}
else {
  // Makes it easier to read
  $first_name = $_SESSION['first_name'];
  $last_name = $_SESSION['last_name'];
  $email = $_SESSION['email'];
  $active = $_SESSION['active'];
}
?>
<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>Welcome <?= $first_name.' '.$last_name ?></title>
  <?php include 'css/css.html'; ?>
  <style>
  </style>
</head>

<body>
  <div class="form maxForm">
      <div class="backArrow"><a href="index.php"><img src="others/left_arrow.png" height="60px" width="60px"></a></div>
          <h1>Account Management</h1>
          
          <p>
          <?php 
     
          // Display message about account verification link only once
          if ( isset($_SESSION['message']) )
          {
              echo $_SESSION['message'];
              
              // Don't annoy the user with more messages upon page refresh
              unset( $_SESSION['message'] );
          }
          
          ?>
          </p>
          
          <div class="profileInfo">
            <img src="others/avatar.png" alt="Profile photo" class="profilePhoto">
            <h2><?php echo $first_name.' '.$last_name; ?></h2>
            <p><?= $email ?></p>
          </div>
          

          <?php
          
          // Keep reminding the user this account is not active, until they activate
          if ( !$active ){
              echo
              '<div class="info">
              Account has not been verified yet. Some functions might not be enabled for you. 
              </div>';
          }
          
          ?>

      <?php
          if($_SESSION["logged_in_e"] == true){
              $adminView = "<div class='userCol'><h2>User view</h2>
              <a href='editUserProfile.php'><button class='button button-block' style='margin-bottom: .4rem' name='editinfo'>Edit my profile information</button></a>
              <a href='testCale.php'><button class='button button-block' style='margin-bottom: .4rem' name='order'>Booking Calendar</button></a>
              <a href='testCale.php#room_booking'><button class='button button-block' style='margin-bottom: .4rem' name='order'>Book a room</button></a>


              </div>
              <div class='userCol'><h2>Admin view</h2>
              <a href='searchUserProfileAdmin.php'><button class='button button-block' style='margin-bottom: .4rem' name='adduser'>Edit customers</button></a>
              <a href='createMember.php'><button class='button button-block' style='margin-bottom: .4rem' name='adduser'>Create member</button></a>
              <a href='editLecturesAdmin.php'><button class='button button-block' style='margin-bottom: .4rem' name='adduser'>Create lectures</button></a>
              <a href='searchLecturesAdmin.php'><button class='button button-block' style='margin-bottom: .4rem' name='adduser'>Edit Calendar</button></a>
              <a href='createLectureType.php'><button class='button button-block' style='margin-bottom: .4rem' name='adduser'>Manage lecture types</button></a>
              
              <a href='adminRoomDashboard.php'><button class='button button-block' style='margin-bottom: .4rem' name='adduser'>Manage Rooms</button></a>

              </div>
              <a href='logout.php'><button class='button button-block' style='margin-bottom: .4rem' name='order'>Log out</button></a>

              ";
              echo $adminView;
          }
          else if($_SESSION["logged_in"]){
              $userView = "
              <a href='editUserProfile.php'><button class='button button-block' style='margin-bottom: .4rem' name='editinfo'>Edit profile information</button></a>
              <a href='userReservations.php'><button class='button button-block' style='margin-bottom: .4rem' name='order'>Show my reservations</button></a>
              <a href='testCale.php'><button class='button button-block' style='margin-bottom: .4rem' name='order'>Booking Calendar</button></a>
              <a href='testCale.php#room_booking'><button class='button button-block' style='margin-bottom: .4rem' name='order'>Book a room</button></a>
              <a href='logout.php'><button class='button button-block' style='margin-bottom: .4rem' name='order'>Log out</button></a>
              ";
              echo $userView;
          }
      ?>
    </div>
    
<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script src="js/index.js"></script>

</body>
</html>
