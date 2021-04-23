<?php
	session_start();

	require_once("../util/utils.php");
	require_once("drag_box_func.php");
	require_once("save_state_func.php");

	$data = array();
	$data2 = array();

	$orgboxes = isset($_POST['obs']) ? $_POST['obs'] : null;
	$data2['save_reason'] = isset($_POST['save_reason']) ? $_POST['save_reason'] : null;
	$data2['save_state'] = isset($_POST['save_state']) ? $_POST['save_state'] : null;
	$data2['save_width'] = isset($_POST['save_width']) ? $_POST['save_width'] : null;
	$data2['save_height'] = isset($_POST['save_height']) ? $_POST['save_height'] : null;

	//Reset the position to zero for each
	$pairs = explode(",",$orgboxes);
	foreach ($pairs as $value) {
		$data['ob_id'] = $value;
		drag_box_zero($data);
	}    
	
	$retval = "";
	if($data2['save_reason'] != null){
		$retval = save_state($data2);
	}


	exit($retval);


?>