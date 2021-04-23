<?php
    session_start();
    
	require_once("../util/utils.php");
	//require_once("config.php");
	require_once("resize_ob_func.php");
	require_once("save_state_func.php");


	$data = array();

	$data['obid'] = isset($_POST['obid']) ? $_POST['obid'] : null;
	$data['obwidth'] = isset($_POST['obwidth']) ? $_POST['obwidth'] : null;
	$data['obheight'] = isset($_POST['obheight']) ? $_POST['obheight'] : null;

	$retval = resize_ob($data);

	


	$data['save_reason'] = isset($_POST['save_reason']) ? $_POST['save_reason'] : null;
	$data['save_state'] = isset($_POST['save_state']) ? $_POST['save_state'] : null;
	$data['save_width'] = isset($_POST['save_width']) ? $_POST['save_width'] : null;
	$data['save_height'] = isset($_POST['save_height']) ? $_POST['save_height'] : null;

	if($data['save_reason'] != null){
		$retval = save_state($data);
	}

	echo $retval;
	exit(1);




?>
