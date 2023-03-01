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
if ($_SESSION['logged_in_e'] != 1)
{
    $_SESSION['message'] = "This page is only for employees.";
    header("location: error.php");
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>
            Find user
        </title>
        <?php include 'css/css.html'; ?>
        <style>
            body {
                background-image: url(try.png);
                background-repeat: no-repeat;
                background-attachment: fixed;
                background-size: cover;
            }

            .reduced {
                width: 30%;
                margin: auto;
            }
        </style>
    </head>
    <body>
    <?php
    require 'navbar.php';
    ?>
        <div class="form searchbar">
            <div class="tab-content">
                <a href="createMember.php" class="newCustomer"><img class="newCustomerImg" src="others/add.png" width="40px" height="40px"></a>
                <!--Icon by Freepik - Flaticon-->
                <h1>User Management</h1>
                <p>Note: Keep the following field empty to show a list of all customers.</p>
                <form name="search" action="searchUserProfileAdmin.php" method="post" autocomplete="off">
                    <div class="field-wrap">
                        <label>Search for users by (partial) Surname or literal E-mail</label>
                        <input placeholder="" class="searchMe" value="" autocomplete="on" name="text_to_search" />
                        <button class='button button-block' name='search_button'>Search</button>
                        <div class="errMsg">
                        <?php
                        if(isset($_SESSION['message'])){
                            echo($_SESSION['message']);
                        }
                        ?>
                        </div>
                    </div>

                </form>
            </div>
            <div>
            <?php
            if (isset($_POST['text_to_search']))
            {
                $searchText = $mysqli->escape_string($_POST["text_to_search"]);

                // E-mail check
                if (filter_var($searchText, FILTER_VALIDATE_EMAIL))
                {
                    // By e-mail
                    $result = $mysqli->query("SELECT * FROM users WHERE email='$searchText'") or die($mysqli->error);
                }
                else
                {
                    // By last name
                    $regex = "'^$searchText'";
                    $result = $mysqli->query("SELECT * FROM users WHERE last_name REGEXP $regex") or die($mysqli->error);
                }

                echo("
                    <table class='listOfUsersTable listOfUsersTableInit'>
                        <thead>
                            <th>
                            Surname
                            </th>
                            <th>
                            First name
                            </th>
                            <th>
                            E-mail
                            </th>
                            <th>
                            Edit User
                            </th>
                            <th>
                            Edit Lectures
                            </th>
                        </thead>
                    </table>
                    ");

                if ($result->num_rows !== 0)
                {
                    $_SESSION['message'] = "";
                    $i = 0; $j = 0;
                    while ($row = $result->fetch_array())
                    {
                        $j++;
                        $first_name = $row['first_name'];
                        $last_name = $row['last_name'];
                        $email = $row['email'];
                        $id = $row['id'];

                        echo "<div class='listOfUsers'>
                              <form name='edit_user' action='editUserProfileAdmin.php' method='post' autocomplete='off' id='form1$j'></form>";
                        echo "<form name='edit_user' action='editUserLecturesAdmin.php' method='post' autocomplete='off' id='form2$j'></form>";

                        echo("
                            <table class='listOfUsersTable'>
                                <thead>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        <input form='form1$j' type='hidden' value='$email' required autocomplete='on' name='email'/>
                                        $last_name  
                                    </td>
                                    <td>
                                    $first_name 
                                    </td>
                                    <td>
                                        $email
                                    </td>
                                    <td>
                                        <button form='form1$j' class='button button-block reduced'  name='edit_user_button_$i'>Edit</button>
                                    </td>
                                    <td>
                                        <input form='form2$j' type='hidden' value='$email' required autocomplete='on' name='email'/>
                                        <input form='form2$j' type='hidden' value='$id' required autocomplete='on' name='id'/>
                                        <button form='form2$j' class='button button-block reduced' name='edit_lectures_button_$i'>Manage</button>
                                    </td>

                                </tr>
                               </tbody> 
                            </table>
                        
                            ");

                        echo "</div>";

                        $i++;
                    }
                }
                else
                {
                    $_SESSION['message'] = "User not found!";
                }
            }
            ?>
            </div>
        </div>
        <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

        <script src="js/index.js"></script>
    </body>
</html>
