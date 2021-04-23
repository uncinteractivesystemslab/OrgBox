<?php
	session_start();

	require_once("../util/utils.php");
	require_once("order_ob_func_packery.php");


	$data = array();

	$data['obs'] = isset($_POST['obs']) ? $_POST['obs'] : null;

	$retval = order_ob_packery($data);

	echo "status=" . $retval;
	exit(1);




?>
