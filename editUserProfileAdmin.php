<?php
include "db.php";
session_start();
// FIXME remove for release version
ini_set('display_errors', 1);

/**
 * Check if user is logged in
 */
if ($_SESSION['logged_in'] != 1)
{
    $_SESSION['message'] = "Please log in to view this page!";
    header("location: error.php");
}
if(!$_SESSION['logged_in_e']){
    $_SESSION['message'] = "Sorry, this page is only for employees.";
    header("location: error.php");
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>
            Edit user's profile
        </title>
        <?php
            include 'css/css.html'
        ?>
    </head>
    <body>
    <?php
    include "navbar.php";
    ?>
    <div class="form">
        <?php
            // Editing by e-mail
            if (isset($_POST['new_email']))
            {
                $first_name = $mysqli->escape_string($_POST['first_name']);
                $last_name = $mysqli->escape_string($_POST['last_name']);
                $email = $_POST['email'];
                $new_email = $mysqli->escape_string($_POST['new_email']);

                if (filter_var($new_email, FILTER_VALIDATE_EMAIL))
                {
                    $mysqli->query("UPDATE users SET first_name='$first_name', last_name='$last_name', email='$new_email' WHERE email='$email'")
                    or die($mysqli->error);
                    $_SESSION['message'] = "Editace proběhla úspěšně";
                }
                else
                {
                    $_SESSION['message'] = "E-mail je ve špatném formátu!";
                }
            }

            // Generating form
            if (isset($_POST['email']))
            {
                $email = $_POST['email'];

                $result = $mysqli->query("SELECT * FROM users WHERE email='$email'") or die($mysqli->error);
                $row = $result->fetch_array();
                $first_name = $row['first_name'];
                $last_name = $row['last_name'];
                
                echo ("
                    <form name='edit_user' action='editUserProfileAdmin.php' method='post' autocomplete='off'>
                        <div class='field-wrap'>
                            <label>
                              First Name<span class='req'></span>
                            </label>
                            <input type='text' value='$first_name' required autocomplete='on' name='first_name'/>
                        </div>
                        
                        
                        <div class='field-wrap'>
                            <label>
                              Surname<span class='req'></span>
                            </label>
                            <input type='text' value='$last_name' required autocomplete='on' name='last_name'/>
                        </div>
                    
                    
                        <div class='field-wrap'>
                            <label>
                              E-mail<span class='req'></span>
                            </label>
                            <input type='email' value='$email' required autocomplete='on' name='new_email'/>
                        </div>
                        <input value='$email' type='hidden' name='email' />
                        <button name='edit_button'>Edit</button>
                    </form>
                    
                    <form name='deleteUser' action='removeUser.php' method='post' autocomplete='off'>
                    <input value='$email' type='hidden' name='email' />
                    <button name='edit_button'>Delete</button>                    
                    </form>

                ");
            }
        ?>
    </div>
    <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

    <script src="js/index.js"></script>
    </body>
</html>
