<?php

    session_start();

    require_once("../util/utils.php");
	require_once("load_obi.php");

    global $h, $u, $p, $dbname;

    //$data = array();
    $keyID = isset($_POST['ob_key']) ? $_POST['ob_key'] : null;


    $cake = find_ob_size($keyID);
    
    $retarray = array();
    $retarray[0] = $cake['ob_width'];
    $retarray[1] = $cake['ob_height'];


	echo  json_encode($retarray, JSON_FORCE_OBJECT) ;
	exit(1);

?>