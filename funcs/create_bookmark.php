<?php
	session_start();

	require_once("../util/utils.php");
	require_once("create_obi_func.php");


	$data = array();

	$data['obi_url'] = isset($_POST['obi_url']) ? $_POST['obi_url'] : null;
	$data['obi_text'] = isset($_POST['obi_text']) ? $_POST['obi_text'] : null;
	$data['obi_title'] = isset($_POST['obi_title']) ? $_POST['obi_title'] : null;
    $data['obi_box_id'] = isset($_POST['obi_box_id']) ? $_POST['obi_box_id'] : null;
    
    $obiurl = isset($_POST['obi_url']) ? $_POST['obi_url'] : null;
	$obitext = isset($_POST['obi_text']) ? $_POST['obi_text'] : null;
	$obititle = isset($_POST['obi_title']) ? $_POST['obi_title'] : null;

	$obiid = create_obi($data);
    $data['obi_id'] = $obiid;

    

    $returnObi = "<li class='orgboxitem' id='obi_".$obiid."' sid='".$obiid."'><div id='obi_div_".$obiid."'>";
    $returnObi .= "<a id='obi_url_".$obiid."' href='".$obiurl."' target='_blank'>".$obititle."</a>";
	$returnObi .=  "<span class='removeit' onclick='removeobi(".$obiid.")'>&nbsp;&nbsp;&nbsp;&nbsp;</span>";
    $returnObi .=  "<br><span id='obi_text_".$obiid."'>".$obitext."</span>";
   $returnObi .=  "</div></li>";
   
   $retarray = array();
   $retarray[0] = $returnObi;
   $retarray[1] = $obiid;


   echo  json_encode($retarray, JSON_FORCE_OBJECT) ;
exit(1);

?>
