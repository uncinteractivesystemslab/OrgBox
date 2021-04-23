<?php
	session_start();

	// remove_ob.php
	//	
	// expects:
	//    ob params passed via POST
	//

	require_once("../util/utils.php");
	require_once("remove_obi_func.php");

	$data = array();

	$data['obi_id'] = isset($_POST['obi_id']) ? $_POST['obi_id'] : null;
	$obisubitems = isset($_POST['obi_subitems']) ? $_POST['obi_subitems'] : null;

	//Remove the Item
	$retval = remove_obi($data);
	//If subitems exist, remove them
	$pairs = explode(",",$obisubitems);
	foreach ($pairs as $value) {
		$obiid = explode("_",$value);
		$retval .= $obiid[1];
		$data['obi_id'] = $obiid[1];
		remove_obi($data);
	}    

	echo $retval;
	exit(1);




?>