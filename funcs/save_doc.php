<?php
	session_start();

	require_once("../util/utils.php");
	require_once("save_doc_func.php");


	$data = array();

	$data['save_reason'] = isset($_POST['save_reason']) ? $_POST['save_reason'] : null;
	$data['save_state'] = isset($_POST['save_state']) ? $_POST['save_state'] : null;
	$data['save_html'] = isset($_POST['save_html']) ? $_POST['save_html'] : null;

	if($data['save_reason'] != null && $data['save_reason'] != ''){
		$retval = save_doc($data);
	}

	exit($retval);


?>
