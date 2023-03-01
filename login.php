<?php 
/* Main page with two forms: sign up and log in */
require 'db.php';
session_start();


if(isset($_SESSION['logged_in'])){
  if ( $_SESSION['logged_in'] == true ) {
    header("location: index.php");
  }
}

?>
<!DOCTYPE html>
<html>
<head>
  <title>Sign-Up/Login Form</title>
  <?php include 'css/css.html'; ?>
  <style>

  </style>
</head>

<?php 
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    if (isset($_POST['login'])) { //user logging in

        require 'loginUser.php';

    }
    
    else{//if (isset($_POST['register'])) { //user registering
        
        require 'register.php';
        
    }
}
?>
<body>
    <!-- <?php // include "navbar.php";?> -->
  <div class="form">
      
      <ul class="tab-group">
        <li class="tab"><a href="#signup">Sign Up</a></li>
        <li class="tab active"><a href="#login">Sign In</a></li>
      </ul>
      
      <div class="tab-content">

         <div id="login">   
          <h1>Welcome Back!</h1>
          
          <form action="login.php" method="post" autocomplete="off">
          
            <div class="field-wrap">
            <label>
              E-mail address<span class="req">*</span>
            </label>
            <input type="email" required autocomplete="on" name="email"/>
          </div>
          
          <div class="field-wrap">
            <label>
              Password<span class="req">*</span>
            </label>
            <input type="password" required autocomplete="off" name="password"/>
          </div>
          
          <p class="forgot"><a href="forgot.php">Forgotten password?</a></p>
          
          <button class="button button-block" name="login">Sign In!</button>
          
          </form>

        </div>
          
        <div id="signup">   
          <h1>Sign Up</h1>
          
          <form action="login.php" method="post" autocomplete="off">
          
          <div class="top-row">
            <div class="field-wrap">
              <label>
                First Name<span class="req">*</span>
              </label>
              <input type="text" required autocomplete="off" name='firstname' />
            </div>
        
            <div class="field-wrap">
              <label>
                Surname<span class="req">*</span>
              </label>
              <input type="text"required autocomplete="off" name='lastname' />
            </div>
          </div>

          <div class="field-wrap">
            <label>
              E-mail address<span class="req">*</span>
            </label>
            <input type="email"required autocomplete="off" name='email' />
          </div>
          
          <div class="field-wrap">
            <label>
              Password<span class="req">*</span>
            </label>
            <input type="password"required autocomplete="off" name='password'/>
          </div>
          
          <button type="submit" class="button button-block" name="" />Create an Account</button>
          
          </form>

        </div>  
        
      </div><!-- tab-content -->
      
</div> <!-- /form -->
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

    <script src="js/index.js"></script>

</body>
</html>
