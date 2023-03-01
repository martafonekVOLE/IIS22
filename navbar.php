<!-- Navigation -->
<?php
if ( $_SESSION['logged_in']){
    $username = $_SESSION['email'];
}
?>
<nav class='navbar navbar-expand-lg navbar-dark bg-dark static-top'>
    <div class='container'>
        <a class='navbar-brand'>
            <?php
                echo "<a class='nav-link' style='text-decoration: none; color: #fff'>Logged in: $username</a>";
                if (basename($_SERVER['PHP_SELF']) == 'userReservations.php' || basename($_SERVER['PHP_SELF']) == 'userExpiredLectures.php' || basename($_SERVER['PHP_SELF']) == 'userExpiredRoomReservations.php') {
                        echo("<a class='nav-link' style='text-decoration: none; color: #fff; text-align: center' aria-current='page' href='profile.php'>Back to profile</a>");
                }
            ?>
        </a>
        <button class='navbar-toggler' type='button' data-bs-toggle='collapse' data-bs-target='#navbarSupportedContent' aria-controls='navbarSupportedContent' aria-expanded='false' aria-label='Toggle navigation'>
            <span class='navbar-toggler-icon'></span>
        </button>
        <div class='collapse navbar-collapse' id='navbarSupportedContent'>
            <ul class='navbar-nav ms-auto'>
                <?php
                if ( $_SESSION['logged_in_e'])
                {
                    if (basename($_SERVER['PHP_SELF']) == 'searchUserProfileAdmin.php') {
                        echo("
                        <li class='nav-item'>
                            <a class='nav-link active' aria-current='page' href='createMember.php'>Add User</a>
                        </li>
                        <li class='nav-item'>
                            <a class='nav-link active' aria-current='page' href='profile.php'>Home</a>
                        </li>
                        <li class='nav-item'>
                            <a class='nav-link active' aria-current='page' href='logout.php'>Odhlásit se</a>
                        </li>
                        ");
                    }
                    if (basename($_SERVER['PHP_SELF']) == 'createMember.php') {
                        echo("
                        <li class='nav-item'>
                            <a class='nav-link active' aria-current='page' href='searchUserProfileAdmin.php'>List of Users</a>
                        </li>
                        <li class='nav-item'>
                            <a class='nav-link active' aria-current='page' href='profile.php'>Home</a>
                        </li>
                        <li class='nav-item'>
                            <a class='nav-link active' aria-current='page' href='logout.php'>Log out</a>
                        </li>
                        ");
                    }
                    if (basename($_SERVER['PHP_SELF']) == 'createLectureType.php') {
                        echo("
                        <li class='nav-item'>
                            <a class='nav-link active' aria-current='page' href='searchLecturesAdmin.php'>Manage Lectures</a>
                        </li>
                        <li class='nav-item'>
                            <a class='nav-link active' aria-current='page' href='profile.php'>Home</a>
                        </li>
                        <li class='nav-item'>
                            <a class='nav-link active' aria-current='page' href='logout.php'>Log out</a>
                        </li>
                        ");
                    }
                    if (basename($_SERVER['PHP_SELF']) == 'editLecturesAdmin.php') {
                        echo("
                        <li class='nav-item'>
                            <a class='nav-link active' aria-current='page' href='createLectureType.php'>Manage Lecture Types</a>
                        </li>
                        <li class='nav-item'>
                            <a class='nav-link active' aria-current='page' href='profile.php'>Home</a>
                        </li>
                        <li class='nav-item'>
                            <a class='nav-link active' aria-current='page' href='logout.php'>Log out</a>
                        </li>
                        ");
                    }

                    if (basename($_SERVER['PHP_SELF']) == 'searchLecturesAdmin.php') {
                        echo("
                        <li class='nav-item'>
                            <a class='nav-link active' aria-current='page' href='editLecturesAdmin.php'>Create Lecture</a>
                        </li>
                        <li class='nav-item'>
                            <a class='nav-link active' aria-current='page' href='profile.php'>Home</a>
                        </li>
                        <li class='nav-item'>
                            <a class='nav-link active' aria-current='page' href='logout.php'>Log out</a>
                        </li>
                        ");
                    }

                    if (basename($_SERVER['PHP_SELF']) == 'adminRoomDashboard.php') {
                        echo("
                        <li class='nav-item'>
                            <a class='nav-link active' aria-current='page' href='adminAddRoom.php'>Add New Room</a>
                        </li>
                        <li class='nav-item'>
                            <a class='nav-link active' aria-current='page' href='profile.php'>Home</a>
                        </li>
                        <li class='nav-item'>
                            <a class='nav-link active' aria-current='page' href='logout.php'>Log out</a>
                        </li>
                        ");
                    }

                    if (basename($_SERVER['PHP_SELF']) == 'adminEditRoom.php') {
                        echo("
                        <li class='nav-item'>
                            <a class='nav-link active' aria-current='page' href='adminRoomDashboard.php'>Room dashboard</a>
                        </li>
                        <li class='nav-item'>
                            <a class='nav-link active' aria-current='page' href='profile.php'>Home</a>
                        </li>
                        <li class='nav-item'>
                            <a class='nav-link active' aria-current='page' href='logout.php'>Log out</a>
                        </li>
                        ");
                    }

                    if (basename($_SERVER['PHP_SELF']) == 'adminAddRoom.php') {
                        echo("
                        <li class='nav-item'>
                            <a class='nav-link active' aria-current='page' href='adminRoomDashboard.php'>Room dashboard</a>
                        </li>
                        <li class='nav-item'>
                            <a class='nav-link active' aria-current='page' href='profile.php'>Home</a>
                        </li>
                        <li class='nav-item'>
                            <a class='nav-link active' aria-current='page' href='logout.php'>Log out</a>
                        </li>
                        ");
                    }

                    if (basename($_SERVER['PHP_SELF']) == 'editUserLecturesAdmin.php') {
                        echo("
                        <li class='nav-item'>
                            <a class='nav-link active' aria-current='page' href='searchUserProfileAdmin.php'>List of users</a>
                        </li>
                        <li class='nav-item'>
                            <a class='nav-link active' aria-current='page' href='profile.php'>Home</a>
                        </li>
                        <li class='nav-item'>
                            <a class='nav-link active' aria-current='page' href='logout.php'>Log out</a>
                        </li>
                        ");
                    }

                    if (basename($_SERVER['PHP_SELF']) == 'editUserProfileAdmin.php') {
                        echo("
                        <li class='nav-item'>
                            <a class='nav-link active' aria-current='page' href='searchUserProfileAdmin.php'>List of users</a>
                        </li>
                        <li class='nav-item'>
                            <a class='nav-link active' aria-current='page' href='profile.php'>Home</a>
                        </li>
                        <li class='nav-item'>
                            <a class='nav-link active' aria-current='page' href='logout.php'>Log out</a>
                        </li>
                        ");
                    }
                }
                ?>

            </ul>
        </div>
    </div>
</nav>
