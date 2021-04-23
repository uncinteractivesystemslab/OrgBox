<?php
session_start();
$_SESSION = array();
    $_SESSION['user_id'] = isset($_POST['user_id']) ? (int)$_POST['user_id'] : null;
    $_SESSION['hit_id'] = isset($_POST['hit_id']) ? (int)$_POST['hit_id'] : null;
    $_SESSION['megahit_id'] = isset($_POST['hit_id']) ? (int)$_POST['hit_id'] : null;

    echo print_r($_SESSION);
    echo "<br>";
?>