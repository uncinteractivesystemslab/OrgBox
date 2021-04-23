<?php
	session_start();

	// order_ob.php
	//
	// updates the orgboxes and saves the serialization
	//
	// expects:
	//    ob params passed via POST
	//

	require_once("../util/utils.php");
	//require_once("config.php");
	require_once("order_ob_func.php");
	require_once("save_state_func.php");


	$data = array();

	$data['ob_items'] = isset($_POST['ob_items']) ? $_POST['ob_items'] : null;

	$retval = order_ob($data);


	$data['save_reason'] = isset($_POST['save_reason']) ? $_POST['save_reason'] : null;
	$data['save_state'] = isset($_POST['save_state']) ? $_POST['save_state'] : null;
	$data['save_width'] = isset($_POST['save_width']) ? $_POST['save_width'] : null;
	$data['save_height'] = isset($_POST['save_height']) ? $_POST['save_height'] : null;

	$retval = save_state($data);

	echo "status=" . $retval;
	exit(1);




?>
