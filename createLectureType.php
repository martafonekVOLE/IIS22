<?php
require 'db.php';
session_start();
ini_set('display_errors', 1);

if ($_SESSION['logged_in'] != 1)
{
    $_SESSION['message'] = "Please log in to view this page!";
    header("location: error.php");
}
if ($_SESSION['logged_in_e'] != 1)
{
    $_SESSION['message'] = "This page is only for employees.";
    header("location: error.php");
}
if(isset($_POST['removeElement'])){
    eraseColor($mysqli->escape_string($_POST['removeColor']));
}
if(isset($_POST['insertName'])){
    if(!isset($_POST['insertColor'])){
        insertColor($mysqli->escape_string($_POST['insertName']), "");
    }
    else{
        insertColor($mysqli->escape_string($_POST['insertName']), $mysqli->escape_string($_POST['insertColor']));
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Lecture type</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css"> -->

    <!--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    -->
    <?php
    include 'css/css.html';
    ?>
</head>
<body>
    <?php
        require 'navbar.php';
    ?>
    <div class="listOfLectureTypes">
        <form id="insert" method="post" action="createLectureType.php"></form>
        <div class="form">
            <h2>Repetitive Lessons</h2>
            <table class="listOfUsersTable listOfColors">
            <?php
                $result = $mysqli->query("SELECT * FROM lecture_types") or die($mysqli->error);

                $j = 0;
                while(($row = $result->fetch_array())){
                    echo "<form id='remove$j' method='post' autocomplete='off' action='createLectureType.php'></form>";

                    $name = $row['name'];
                    $color = $row['color'];
                    $id = $row['id'];

                    echo "<tr><td style='background-color: $color'></td><td>$name</td><td><input form='remove$j' type='hidden' value='$id' required autocomplete='on' name='removeColor'/><button name='removeElement' class='smallTransparentButton' form='remove$j'>Remove</button></td></tr>";
                    $j++;
                }
            ?>
            </table>

            <?php
            if(isset($_SESSION['message'])){
                echo $_SESSION['message'];
            }
            $_SESSION['message'] = "";
            ?>
            <div class="field-wrap">
                <h2 style="margin-top: 20px">Create New</h2>
                <input form="insert" name="insertName" type="text" placeholder="Lesson name/shortcut" class="colorInput">
                <select form="insert" name="insertColor" class="dropdownColor">
                    <option value="" class="">Select color</option>
                    <option value="purple" name class="purple">Purple</option>
                    <option value="green" class="green">Green</option>
                    <option value="blue" class="blue">Blue</option>
                    <option value="lime" class="lime">Lime</option>
                    <option value="orange" class="orange">Orange</option>
                </select>
                <button form="insert">Create lecture</button>
            </div>

        </div>
    </div>

</body>
</html>

<?php
function eraseColor($colID){
    require 'db.php';
    $mysqli->query("DELETE FROM lecture_types WHERE id=$colID") or error();
    $_SESSION['message'] = "Lecture successfully created.";
}
function insertColor($colName, $colColor){
    require 'db.php';
    $mysqli->query("INSERT INTO lecture_types (name, color) values ('$colName', '$colColor')") or die($mysqli->error());
    header("location: createLectureType.php");
}
function error(){
    $_SESSION['message'] = "Sorry, this lecture cannot be deleted, because it has assigned events in calendar. Please contact system administrator";
}
?>