<?php
/* Displays all error messages */
session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Error</title>
  <?php include 'css/css.html'; ?>
  <style>
  body{
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-size: cover;
        }
        </style>
</head>
<body>
<div class="form">
    <h1>Error</h1>
    <p>
    <?php 
    if(isset($_SESSION['message']) and !empty($_SESSION['message'])){
        echo $_SESSION['message'];    
    }else{
        require_once 'utils.php';
        redirect('login.php');
    }
    ?>
    </p>     
    <a href="profile.php"><button class="button button-block"/>Home</button></a>
</div>
</body>
</html>
