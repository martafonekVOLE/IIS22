<?php
session_start();
include "db.php";
include "navbar.php";
include "utils.php";

function view_alert(string $str){
    echo("<h5 style='background: gray'>$str</h5>");
}

if ($_SESSION['logged_in_e'] != 1)
{
    $_SESSION['message'] = "You have to be an employee to view this site";
    redirect("error.php");
}

if ( isset($_SESSION['message']) )
{
    view_alert($_SESSION['message']);
    // Don't annoy the user with more messages upon page refresh
    unset( $_SESSION['message'] );
}

if(isset($_POST['delete_id'])){
    $delete_id = $_POST['delete_id'];
    $result = $mysqli->query("SELECT name, time_start FROM lecture WHERE room_id=$delete_id and DATE(time_start) > DATE(NOW())");
    if($result->num_rows != 0){
        view_alert("Cannot delete room because there are upcoming lectures. Please delete these first:");
        while($row = $result->fetch_assoc()){
            view_alert(" - ". $row['name'] . " - " . $row['time_start'] . "\n");
        }
    }
    else if($result->num_rows != 0){
        $result = $mysqli->query("SELECT name, date_start FROM reservation LEFT JOIN rooms r on reservation.room_id = r.id WHERE room_id=$delete_id and DATE(date_start) > DATE(NOW())");
        view_alert("Cannot delete room because there are upcoming room reservations. Please delete these first:");
        while($row = $result->fetch_assoc()){
            view_alert(" - " . $row['name'] . " - " . $row['date_start'] . "\n");
        }
    }
    else{
        $img_del_res = $mysqli->query("SELECT img FROM rooms WHERE rooms.id = $delete_id");
        if($img_del_res->num_rows == 1){
            $img_del_row = $img_del_res->fetch_assoc();
            $img_del = $img_del_row['img'];
            if($img_del != "default.jpg"){
                unlink(__DIR__ . "/img/" . $img_del);
            }
        }
        $mysqli->query("DELETE FROM rooms WHERE rooms.id = $delete_id");

        view_alert("Room deleted.");
    }


}

$all_rooms = $mysqli->query("SELECT id, name, max, description, img FROM rooms");

?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <title>Room management</title>
    <?php include 'css/css.html'; ?>
</head>

<body>
<br>

    <div class="form searchbar">
        <button style="padding: 15px; margin-bottom: 35px;" class='button room-btns'><a style="text-decoration: none; color: white;" href='adminAddRoom.php'> + Add New Room</a></button>

        <h2 style="padding: 20px" id="rooms">Existing rooms</h2>


        <?php
        while ($row = $all_rooms->fetch_assoc())
        {
            $room_id = $row['id'];
            $room_name = $row['name'];
            $room_description = $row['description'];
            $room_image = "./img/" . $row['img'];
            $room_max = $row['max'];
            echo("
                <table class='roomTable'>
                <tr>
                <td>
                <h4 style='color: white'>$room_name</h4>
                </td>
                <td>
                <button class='button room-btns'><a style='text-decoration: none; color: white;' href='adminEditRoom.php?room_id=$room_id'>Edit</a></button>
                </td>
                <td>
                <form class='room-btns' method='POST' action='adminRoomDashboard.php' onsubmit=\"return confirm('Are you sure you wish to delete $room_name? Doing so removes the room record from reservation and lectrure history.');\">
                    <input type='hidden' name='delete_id' value=$room_id>
                    <button class='button' type='submit'>Delete</button>
                </form>
                </td>
                </tr>
                </table>
            ");
        }
        ?>

    </div>
</body>