<?php
require 'db.php';
session_start();


if ( $_SESSION['logged_in_e'] != 1 ) {
    $_SESSION['message'] = "Only staff members can see this";
    header("location: error.php");    
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    if(isset($_POST['radio'])){
        if($_POST['radio'] == "employee"){
            $currMail = $_SESSION['email'];
            $result = $mysqli->query("SELECT * FROM employees WHERE email='$currMail'") or die($mysqli->error);
            $result = $result->fetch_assoc();
            $result = $result['role'];
            if($result == "admin"){
                require 'test.php';
            }
            else{
                $_SESSION['message'] = "You cannot do this. Please contact you system administrator.";
                header('location: error.php');
            }
        }
        else{
            require 'test.php';
        }
    }


}
?>
<html>
    <head>
        <title>Create New Member</title>
        <?php include 'css/css.html'; ?>
    </head>
    <body>
    <?php
        require 'navbar.php';
    ?>
        <div class="tab-content">
            <form class="form searchbar" action="createMember.php" method="POST" autocomplete="off">
                <h1 style="margin-bottom: 20px">Create new Member</h1>
                <div class="field-wrap" style="margin-bottom: 20px">
                    <label>
                        Customer<span class="req">*</span>
                    </label>
                    <input type="radio" checked="checked" id="user" name="radio" value="user">
                </div>

                <div class="field-wrap" style="margin-bottom: 20px">
                    <label>
                        Employee<span class="req">*</span>
                    </label>
                    <input type="radio" id="user" name="radio" value="employee">
                </div>

                <div class="field-wrap">
                    <label>
                        First Name<span class="req">*</span>
                    </label>
                    <input type="text" required autocomplete="off" name="firstname"/>
                </div>

                <div class="field-wrap">
                    <label>
                        Lastname<span class="req">*</span>
                    </label>
                    <input type="text" required autocomplete="off" name="lastname"/>
                </div>

                <div class="field-wrap">
                    <label>
                        PID (personal identification number)<span class="req"></span>
                    </label>
                    <input type="text" autocomplete="off" name="pid"/>
                </div>

                <div class="field-wrap">
                    <label>
                        Role<span class="req"></span>
                    </label>
                    <input type="text" autocomplete="off" name="role"/>
                </div>

                <div class="field-wrap">
                    <label>
                        E-mail<span class="req">*</span>
                    </label>
                    <input type="email" required autocomplete="off" name="email"/>
                </div>

                <div class="field-wrap">
                    <label>
                        Phone Number<span class="req"></span>
                    </label>
                    <input type="text" autocomplete="off" name="phone"/>
                </div>

                <div class="field-wrap">
                    <label>
                        Password<span class="req">*</span>
                    </label>
                    <input type="password" required autocomplete="off" name="password" value="12345"/>
                    <p>Note: Default password is 12345. User can change this later via reset form.</p>
                </div>
                <!--For testing purposes, PID, PHONE & role is only used for employees!-->
            <button type="submit" class="button button-block" name="" />Registrovat</button>
            </form>
        </div>
        <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

        <script src="js/index.js"></script>
    </body>
</html>