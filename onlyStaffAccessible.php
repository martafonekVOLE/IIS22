<?php
session_start();
if ( $_SESSION['logged_in_e'] != 1 ) {
    $_SESSION['message'] = "Only staff members can see this";
    header("location: error.php");    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TEST</title>
</head>
<body>
    
</body>
</html>