<?php
	session_start();

	$data = array();

	$data['ob_title'] = isset($_GET['ob_title']) ? $_GET['ob_title'] : null;
	$data['ob_id'] = isset($_GET['ob_id']) ? $_GET['ob_id'] : null;

    $retval = "<span class='obtitlespan'  id='ob_title_".$data['ob_id']."'>".$data['ob_title']."</span>";
	$retval .=  "<span class='removeit' onclick='removeob(".$data['ob_id'].")'>&nbsp;&nbsp;&nbsp;&nbsp;</span>";
	$retval .=  "<span class='editit' onclick='editob(".$data['ob_id'].")'>&nbsp;&nbsp;&nbsp;&nbsp;</span>";
    echo $retval;
	exit(1);


?>
