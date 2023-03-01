<?php
include "db.php";
session_start();
//FIXME remove for release version
ini_set('display_errors', 1);


/**
 * Check if user is logged in
 */
if($_SESSION['logged_in'] != 1)
{
    $_SESSION['message'] = "Pro přístup na tuto stránku se prosím přihlaste";
    header("location: error.php");
}


/**
 * Update DB with POSTed data
 */
if(isset($_POST["first_name"]) && isset($_POST["last_name"]))
{
    $email = $_SESSION["email"];
    $new_first_name = $mysqli->escape_string($_POST["first_name"]);
    $new_last_name = $mysqli->escape_string($_POST["last_name"]);
    if($_SESSION['logged_in_e']){
        $mysqli->query("UPDATE employees 
                        SET 
                            first_name = '$new_first_name',
                            last_name = '$new_last_name'
                        WHERE
                            email = '$email'") or die($mysqli->error);
    }
    else{
        $mysqli->query("UPDATE users 
                        SET 
                            first_name = '$new_first_name',
                            last_name = '$new_last_name'
                        WHERE
                            email = '$email'") or die($mysqli->error);
    }


    $_SESSION['last_name'] = $new_last_name;
    $_SESSION['first_name'] = $new_first_name;
    $_SESSION['message'] = "Your account changes were saved successfully.";
    header('location: profile.php');

    // echo("
    // <div class='alert alert-success' role='alert'>
    //     Úprava se podařila!
    // </div>
    // ");
}



/**
 * Fetching data from DB
 */
$email = $_SESSION['email'];
if($_SESSION["logged_in_e"]){
    $result = $mysqli->query("SELECT * FROM employees WHERE email='$email'") or die($mysqli->error);
}
else{
    $result = $mysqli->query("SELECT * FROM users WHERE email='$email'") or die($mysqli->error);
}
$row = $result->fetch_assoc();
$first_name = $row['first_name'];
$last_name = $row['last_name'];



/**
 * Form HTML code
 */
?>

    <!DOCTYPE html>
    <html>
<head>
    <title>Edit my profile</title>
    <?php include 'css/css.html'; ?>
    <style>
        body{
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
        }
    </style>
</head>
<?php
echo ("
<body>
    <div class='form'>
        <div class='tab-content'>
            <h1>Edit your account information</h1>
            <br>
            <form name='editUser' action='editUserProfile.php' method='post'  autocomplete='off'>
                <div class='field-wrap'>
                    <label>
                        Name<span class='req'>*</span>
                    </label>
                    <input value='$first_name' required autocomplete='on' name='first_name'/>
                </div>
            
                <div class='field-wrap'>
                    <label>
                        Surname<span class='req'>*</span>
                    </label>
                    <input value='$last_name' required autocomplete='on' name='last_name'/>
                </div>
                <div style='padding-bottom: 20px'>
                    <button class='button button-block' name='login'>Save & leave</button>
                </div>
            </div>
        </form>
                <a href='profile.php'><button class='button button-block'>Leave</button></a>
    </div>
</body>
");

