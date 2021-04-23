<?php
	session_start();

	require_once("../util/utils.php");
	require_once("edit_ob_func.php");

	$data = array();

	// edit_obi_func will fill in the current session data

	$data['ob_title'] = isset($_POST['ob_title']) ? $_POST['ob_title'] : null;
	$data['ob_id'] = isset($_POST['ob_id']) ? $_POST['ob_id'] : null;

	edit_ob($data);

    $retval = "<span class='obtitlespan'  id='ob_title_".$data['ob_id']."'>".$data['ob_title']."</span>";
	$retval .=  "<span class='removeit' onclick='removeob(".$data['ob_id'].")'>&nbsp;&nbsp;&nbsp;&nbsp;</span>";
	$retval .=  "<span class='editit' onclick='editob(".$data['ob_id'].")'>&nbsp;&nbsp;&nbsp;&nbsp;</span>";
    echo $retval;
	exit(1);


?>
