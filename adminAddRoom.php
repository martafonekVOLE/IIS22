<?php
session_start();
include "navbar.php";
include "db.php";
include "utils.php";


if ($_SESSION['logged_in_e'] != 1)
{
    $_SESSION['message'] = "You need to admin to view this site";
    redirect("error.php");
}


//parse form (with image file) - from https://www.w3schools.com/php/php_file_upload.asp
if(isset($_POST['name']) && isset($_POST['description']) && isset($_POST['max']) && isset($_FILES['img'])){
    $target_dir = __DIR__. "/img/";
    $target_file = $target_dir . basename($_FILES["img"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES["img"]["tmp_name"]);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
        }
    }
    if (file_exists($target_file)) {
        $uploadOk = 0;
    }
    if ($_FILES["img"]["size"] > 5000000) {
        $uploadOk = 0;
    }
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        $_SESSION['message'] = "Invalid picture entered";
        redirect("adminRoomDashboard.php");
    }
    else{
        if (!move_uploaded_file($_FILES["img"]["tmp_name"], $target_file)) {
            $_SESSION['message'] = "Could not store image, try different one.";
            redirect("adminRoomDashboard.php");

        }
        //img upload successful, create DB record
        else{
            $room_name = $_POST['name'];
            $room_desc = $_POST['description'];
            $room_max = $_POST['max'];
            $img_name = $_FILES["img"]["name"];
            if($room_max == 0){
                $_SESSION['message'] = "Cant create room with 0 capacity";
                redirect("adminRoomDashboard.php");
            }
            else{
                try{
                    $mysqli->query(" INSERT INTO `rooms` (`name`, `description`, `max`, `img`) VALUES ('$room_name', '$room_desc', $room_max, '$img_name')");
                    $_SESSION['message'] = "Room $room_name created!";
                    redirect("adminRoomDashboard.php");
                }
                catch(Exception $e){
                    $_SESSION['message'] = "Room was not added - please try different image (Name too long).";
                    redirect("adminRoomDashboard.php");
                }
            }
        }
    }


}

?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <title>Add room</title>
    <?php include 'css/css.html'; ?>
</head>

<body>
<br>
<div class="form">
    <h1 style="padding: 20px" id="rooms">Add room</h1>

    <div class="tab-content">
        <form action="adminAddRoom.php" method="post" name="add_room_form" enctype="multipart/form-data">

            <div class='field-wrap'>
                <input type="text" placeholder="Name" required name='name'/>
            </div>

            <div class='field-wrap'>
                <input type="text" placeholder='Description' required name='description'/>
            </div>

            <div class='field-wrap'>
                <input type="number" placeholder='Capacity' required name='max'/>
            </div>

            <div class='field-wrap'>

                <label for="img">Picture</label>
                <input style="padding-top: 30px" type="file" required  name="img" id="img" />
            </div>

            <div class='field-wrap'>
                <input class="button button-block" type="submit" value="Create" name="submit"/>
            </div>
        </form>
        <button class="button button-block"><a href="adminRoomDashboard.php">Back</a></button>
    </div>
</div>
</body>