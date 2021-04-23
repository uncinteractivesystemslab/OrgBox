<?php

    session_start();

    require_once("../util/utils.php");
	require_once("load_obi.php");

    global $h, $u, $p, $dbname;
    $uid = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $hid = isset($_SESSION['hit_id']) ? $_SESSION['hit_id'] : null;

    $cake = html_entity_decode(load_od_text($uid, $hid));
    echo $cake;
    
	exit(1);

?>