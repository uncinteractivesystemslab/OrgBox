<?php

    session_start();

    require_once("../util/utils.php");
	require_once("load_obi.php");

    global $h, $u, $p, $dbname;

    $keyID = isset($_POST['ob_key']) ? $_POST['ob_key'] : null;
    $ob_pos = find_ob_position($keyID);
    echo $ob_pos;
	exit(1);

?>