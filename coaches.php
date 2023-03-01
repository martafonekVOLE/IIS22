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
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>
            List of Trainers
        </title>
        <?php
        ?>
        <link rel="stylesheet" href="css/LandingPageStyle.css">

        <style>
            body{
                background-color: #333333;
            }
            .form {
                background: rgba(19, 35, 47, 0.97);
                padding: 40px;
                max-width: 600px;
                margin: 40px auto;
                border-radius: 4px;
                box-shadow: 0 4px 10px 4px rgba(19, 35, 47, 0.3);
            }
            .searchbar{
                max-width: 80%;
            }
            .searchbar input[type=radio]{
                height: 30px;
            }
            .searchbar .field-wrap{
                margin: auto;
                width: 80%;
            }
            .listOfLectures{
                color: #fff;
            }
            .listOfLectures table{
                width: 100%;
                text-align: center;
            }
            .listOfLectures td th{
                width: 20%;
            }
            .listOfLectures td:nth-child(5){
                cursor: pointer;
            }
            .listOfLectures td:nth-child(5) button{
                width: 100%;
                height: 100%;
                background-color: #1ab188;
                border: none;
                color: #fff;
            }
            .listOfLectures a{
                border: none;
                text-decoration: none;
                color: #1ab188;
            }




        </style>
    </head>
    <body>
    <div class="navbar" style="background-color: white">
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
        <a class="active" href="coaches.php">Our coaches</a>
        <a href="testCale.php#room_booking">Book a Room</a>
        <a href="testCale.php">Calendar</a>
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
                <li><a href="calendar.php">Our coaches</a></li>
                <li><a href="calendar.php">Book a Room</a></li>
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
        <?php
            $result = $mysqli->query("SELECT * FROM employees WHERE `role`='trainer'") or die($mysqli->error);

            if ($result->num_rows !== 0)
            {
                echo "<div class='form searchbar listOfLectures'><table>";
                echo "<th>Name</th><th>Surname</th><th>E-mail</th><th>Phone number</th><th>Online contact form</th>";
                
                while ($row = $result->fetch_array())
                {
                    $first_name = $row['first_name'];
                    $last_name = $row['last_name'];
                    $phone = $row['phone'];
                    $email = $row['email'];

                    echo "<tr>";
                    echo "<td>$first_name</td>";
                    echo "<td>$last_name</td>";
                    echo "<td><a href='mailto:$email'>$email</a></td>";
                    echo "<td><a href='tel:$phone'>$phone</a></td>";
                    echo "<td>Contact</td>";
                    echo "</tr>";
                }

                echo "</table></div>";
            }
            else
            {
                $_SESSION['message'] = "There are no coaches in the list yet.";
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
    