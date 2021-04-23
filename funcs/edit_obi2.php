<?php
	session_start();

	require_once("../util/utils.php");
	require_once("edit_obi_func.php");


	$data = array();


	$data['obi_url'] = isset($_POST['obi_url']) ? $_POST['obi_url'] : null;
	$data['obi_text'] = isset($_POST['obi_text']) ? $_POST['obi_text'] : null;
	$data['obi_note'] = isset($_POST['obi_note']) ? $_POST['obi_note'] : null;
	$data['obi_title'] = isset($_POST['obi_title']) ? $_POST['obi_title'] : null;
	$data['obi_id'] = isset($_POST['obi_id']) ? $_POST['obi_id'] : null;

    $obiurl = isset($_POST['obi_url']) ? $_POST['obi_url'] : null;
	$obitext = isset($_POST['obi_text']) ? $_POST['obi_text'] : null;
	$obinote = isset($_POST['obi_note']) ? $_POST['obi_note'] : null;
    $obititle = isset($_POST['obi_title']) ? $_POST['obi_title'] : null;
    $obiid = isset($_POST['obi_id']) ? $_POST['obi_id'] : null;
	edit_obi($data);


    $returnObi .= "<a id='obi_url_".$obiid."' href='".$obiurl."'  target='_blank'>".$obititle."</a>";
	$returnObi .=  "<span class='removeit' onclick='removeobi(".$obiid.")'>&nbsp;&nbsp;&nbsp;&nbsp;</span>";
	$returnObi .=  "<span class='editit' onclick='editobi(".$obiid.")'>&nbsp;&nbsp;&nbsp;&nbsp;</span>";
	$returnObi .=  "<br><span id='obi_text_".$obiid."'>".$obitext."</span>";
	if($obinote != null)
	{
        $returnObi .=  "<br><span class='obinote' id='obi_note_".$obiid."'>".$obinote."</span>";
	}else{
		$returnObi .=  "<br><br>";
	}
   echo $returnObi;

	//echo "obi_" . $retval;
	exit(1);


?>
