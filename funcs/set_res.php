<?php
	session_start();

	$_SESSION['window_width'] = isset($_POST['window_width']) ? $_POST['window_width'] : "";
	$_SESSION['window_height'] = isset($_POST['window_height']) ? $_POST['window_height'] : "";
	$_SESSION['monitor_width'] = isset($_POST['monitor_width']) ? $_POST['monitor_width'] : "";
	$_SESSION['monitor_height'] = isset($_POST['monitor_height']) ? $_POST['monitor_height'] : "";
    

?>