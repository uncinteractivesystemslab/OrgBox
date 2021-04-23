<?php
	session_start();

	require_once("utils.php");

	require_once("log_event_func.php");

	$data = array();

	$data['old_value'] = isset($_POST['old_value']) ? $_POST['old_value'] : null;
	$data['new_value'] = isset($_POST['new_value']) ? $_POST['new_value'] : null;
	$data['save_state_id'] = isset($_POST['save_state_id']) ? $_POST['save_state_id'] : null;
	$data['attribute'] = isset($_POST['attribute']) ? $_POST['attribute'] : null;

	$retval = log_event($data);

	echo "status=" . $retval;
	exit(1);


?>
