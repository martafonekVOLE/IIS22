<?php
session_start();
include "navbar.php";
include "db.php";
include "utils.php";


if ($_SESSION['logged_in_e'] != 1)
{
    $_SESSION['message'] = "Login as admin to view this site.";
    redirect("error.php");
}

if(isset($_GET['room_id'])){
    $rid = $_GET['room_id'];
    $room = $mysqli->query("SELECT name, max, description, img FROM rooms WHERE rooms.id=$rid");

    if($room->num_rows == 1){
        $row = $room->fetch_assoc();
        $room_id = $rid;
        $room_name = $row['name'];
        $room_description = $row['description'];
        $room_image = $row['img'];
        $room_max = $row['max'];
    }
    else
    {
        $_SESSION['message'] = "Error in editing, please contact the devs at matej@fitness.mpech.net.\n";
        redirect("error.php");
    }
}
else if(isset($_POST['id']) && isset($_POST['name']) && isset($_POST['description']) && isset($_POST['max'])) {
    $updated_room_id = $_POST['id'];
    $updated_room_name = $_POST['name'];
    $updated_room_description = $_POST['description'];
    $updated_room_max = $_POST['max'];
    //update img
    if (file_exists($_FILES['img']['tmp_name'][0])){
        $_SESSION['message'] =  $_FILES['img']['name'];
        $target_dir = __DIR__ . "/img/";
        $target_file = $target_dir . basename($_FILES["img"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        if (isset($_POST["submit"])) {
            $check = getimagesize($_FILES["img"]["tmp_name"]);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                $uploadOk = 0;
            }
        }
        if (file_exists($target_file)) {
            $uploadOk = 0;
        }
        if ($_FILES["img"]["size"] > 500000) {
            $uploadOk = 0;
        }
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            $_SESSION['message'] = "Invalid image, please select different one.";
            redirect("adminRoomDashboard.php");
        } else {
            if (!move_uploaded_file($_FILES["img"]["tmp_name"], $target_file)) {
                $_SESSION['message'] = "Could not store the image on server, try different one.";
                redirect("adminRoomDashboard.php");
            } //img upload successful, create DB record
            else {
                $img_name = $_FILES["img"]["name"];
                try {
                    $mysqli->query("UPDATE `rooms` SET `img` = '$img_name' WHERE `rooms`.`id` = $updated_room_id") or die();
                }
                catch(Exception $e){
                    $_SESSION['message'] = "Image name too long, please shorten it\n";
                    redirect("adminRoomDashboard.php");
                }
            }
        }
    }
    $mysqli->query("UPDATE `rooms` SET `name` = '$updated_room_name', `description` = '$updated_room_description',`max` = '$updated_room_max' WHERE `rooms`.`id` = $updated_room_id");
    $_SESSION['message'] .= "Room $updated_room_name was edited.";
    redirect("adminRoomDashboard.php");
}
else
{
    $_SESSION['message'] = "Invalid access.";
    redirect("error.php");
}
?>


<!DOCTYPE html>
<html lang="cs">
<head>
    <title>Edit room</title>
    <?php include 'css/css.html'; ?>
</head>

<body>
<br>
<div class="form">
    <h1 style="padding: 20px" id="rooms">Edit room</h1>
    <div class="tab-content">
        <form action="adminEditRoom.php" method="post" name="edit_room_form" enctype="multipart/form-data">

            <input type="hidden" name="id" value="<?php echo $rid ?>">
            <div class='field-wrap'>
                <input type="text" placeholder="Name"  name='name' value='<?php echo $room_name ?>' />
            </div>

            <div class='field-wrap'>
                <input type="text" placeholder='Description'  name='description' value='<?php echo $room_description ?>' />
            </div>

            <div class='field-wrap'>
                <input type="number" placeholder='Capacity'  name='max' value='<?php echo $room_max ?>'/>
            </div>
            <?php echo("/img/" . $room_image); ?>
            <img class="room_image" style="max-width: 400px; margin: auto" alt="Image" src="<?php echo("./img/" . $room_image); ?>">
            <div class='field-wrap'>
                <label for="img">Image - <?php echo $room_image ?></label>
                <input style="padding-top: 30px" type="file"   name="img" id="img" />
            </div>

            <div class='field-wrap'>
                <input class="button button-block" type="submit" value="Edit" name="submit"/>
            </div>
        </form>
        <button  class="button button-block"><a style='text-decoration: none; color: white;' href="adminRoomDashboard.php">Back</a></button>
    </div>
</div>
</body>