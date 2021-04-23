<?php
	session_start();

	require_once("../util/utils.php");
	require_once("edit_obi_func.php");


	$data = array();

	// edit_obi_func will fill in the current session data

	$data['obi_url'] = isset($_POST['obi_url']) ? $_POST['obi_url'] : null;
	$data['obi_title'] = isset($_POST['obi_title']) ? $_POST['obi_title'] : null;
	$data['obi_id'] = isset($_POST['obi_id']) ? $_POST['obi_id'] : null;

    $obiurl = isset($_POST['obi_url']) ? $_POST['obi_url'] : null;
    $obititle = isset($_POST['obi_title']) ? $_POST['obi_title'] : null;
    $obiid = isset($_POST['obi_id']) ? $_POST['obi_id'] : null;
	edit_obi($data);


    $returnObi .= "<a id='obi_url_".$obiid."' href='".$obiurl."'  target='_blank'>".$obititle."</a>";
    $returnObi .=  "<span class='removeitem' onclick='removeobi(".$obiid.")'>";
    $returnObi .=  "<img src='./img/remove.png' alt='Remove' height='16' width='16'> </span>";
    $returnObi .=  "<span class='edititem' onclick='editobi(".$obiid.")'>";
    $returnObi .=  "<img src='./img/edit.png' alt='Edit' height='16' width='16'>&nbsp;&nbsp;</span>";
   echo $returnObi;

	//echo "obi_" . $retval;
	exit(1);


?>
