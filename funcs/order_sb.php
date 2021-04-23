<?php
	session_start();

	require_once("../util/utils.php");
	require_once("order_sb_func.php");
	require_once("save_state_func.php");


	$data = array();

	$data['ob_structure'] = isset($_POST['ob_structure']) ? $_POST['ob_structure'] : null;
	$data['ob_id'] = isset($_POST['ob_id']) ? $_POST['ob_id'] : null;
	$data['save_reason'] = isset($_POST['save_reason']) ? $_POST['save_reason'] : null;
	$data['save_state'] = isset($_POST['save_state']) ? $_POST['save_state'] : null;
	$data['save_width'] = isset($_POST['save_width']) ? $_POST['save_width'] : null;
	$data['save_height'] = isset($_POST['save_height']) ? $_POST['save_height'] : null;

	$retval = order_sb($data);

	if($data['save_reason'] != null){
		$retval = save_state($data);
	}

	//echo $retval;
	exit($retval);
?>
