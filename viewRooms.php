<?php
include "db.php";
//include($_SERVER['DOCUMENT_ROOT'].'/img/');
ini_set('display_errors', 1);


$result = $mysqli->query("SELECT id, name, max, description, img FROM rooms");


?>
<!DOCTYPE html>
<html>
<head>
    <title>Sign-Up/Login Form</title>
    <?php include 'css/css.html'; ?>
    <style>
        body{
            background-image: url(try.png);
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
        }
    </style>
</head>

<body>
    <?php
        if(basename($_SERVER['PHP_SELF']) != 'index.php') {
            include "navbar.php";
        }?>
        <br>
        <h2 style="padding: 20px" id="rooms"> Naše místnosti</h2>
        <hr>
        <div class="container" style="display: flex; justify-content: stretch">
        <div class="row">

                <?php
                while ($row = $result->fetch_assoc())
                {
                    $room_id = $row['id'];
                    $room_name = $row['name'];
                    $room_description = $row['description'];
                    $room_image = "./img/" . $row['img'] . ".jpg";
                    $room_max = $row['max'];

                    $room_post = 'roomReservation.php?room_id=' . $room_id;
                    echo("
                        <div class='col-sm-6' style='padding: 20px'>
                            <div class='card' style='width: 30rem; height: auto;'>
                                <img src='$room_image' class='card-img-top' alt='obrázek'>
                                <div class='card-body'>
                                    <h5 class='card-title'>$room_name</h5>
                                    <p class='card-text dark'>$room_description</p>
                                    <ul class='list-group list-group-flush'>
                                        <li class='list-group-item'>Limit osob: $room_max </li>
                                        <li class='list-group-item'>
                                            <a href='$room_post'>
                                                <button style='padding-left: 10px; padding-right: 10px' class='button'>Rezervovat</button></li>
                                            </a>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    ");
                }
                ?>
        </div>
        </div>
</body>