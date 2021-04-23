<?php
	session_start();
   
   $_SESSION['user_id'] = isset($_POST['user_id']) ? filter_var($_POST['user_id'], FILTER_SANITIZE_NUMBER_INT) : 1;
   $_SESSION['hit_id'] = isset($_POST['hit_id']) ? filter_var($_POST['hit_id'], FILTER_SANITIZE_NUMBER_INT) : 1;
   $_SESSION['megahit_id'] = isset($_POST['hit_id']) ? filter_var($_POST['hit_id'], FILTER_SANITIZE_NUMBER_INT) : 1;
   $systemid = isset($_POST['system_id']) ? filter_var($_POST['system_id'], FILTER_SANITIZE_NUMBER_INT) : 1;
   $_SESSION['system_id'] = $systemid;

   
   header("Location: /orgbox/orgbox.php");
   exit();
    
?>