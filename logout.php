<?php
session_start();
if (isset($_SESSION['username']) || isset($_SESSION['password']) || isset($_SESSION['staff_logged_in'])) {
    session_unset();
    session_destroy();
    echo '<script language = "javascript">';
    echo '</script>';
    echo "<script>window.location.assign('login.php')</script>";
    die();
} else {
    echo "<script>window.location.assign('login.php')</script>";
    die();
}

?>
