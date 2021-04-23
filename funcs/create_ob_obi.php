
<?php
	session_start();

    require_once("create_ob_func.php");
    
	require_once("../util/utils.php");
	require_once("create_obi_func.php");


	$data = array();

	$data['ob_title'] = "NEW BOX";
	
	
	$obid = create_ob($data);

    $data = array();

	// c_obi_f will fill in the current session data

	$data['obi_url'] = isset($_POST['obi_url']) ? $_POST['obi_url'] : null;
	$data['obi_text'] = isset($_POST['obi_text']) ? $_POST['obi_text'] : null;
	$data['obi_note'] = isset($_POST['obi_note']) ? $_POST['obi_note'] : null;
	$data['obi_title'] = isset($_POST['obi_title']) ? $_POST['obi_title'] : null;
    $data['obi_box_id'] = $obid;
    
    $obiurl = isset($_POST['obi_url']) ? $_POST['obi_url'] : null;
	$obitext = isset($_POST['obi_text']) ? $_POST['obi_text'] : null;
	$obinote = isset($_POST['obi_note']) ? $_POST['obi_note'] : null;
	$obititle = isset($_POST['obi_title']) ? $_POST['obi_title'] : null;

	$obiid = create_obi($data);
    $data['obi_id'] = $obiid;

	$retval = "";
	
	$retval .= "<div class='orgboxdiv'  box='".$obid."' id='ob_".$obid."'>";
	$retval .= "<div id='ob_div_$obid'>";
	$retval .= "<span class='obtitlespan'  id='ob_title_".$obid."'>NEW BOX</span>";
	$retval .=  "<span class='removeit' onclick='removeob(".$obid.")'>&nbsp;&nbsp;&nbsp;&nbsp;</span>";
	$retval .=  "<span class='editit' onclick='editob(".$obid.")'>&nbsp;&nbsp;&nbsp;&nbsp;</span>";
	$retval .= "</div>";
	$retval .= "<hr/>";
	$retval .= "<ol class='orgboxitems' id='orgboxitems".$obid."' box='".$obid."'>";
	$retval .= "<li class='orgboxitemnew'><div> Drag text here to add a new item or <button id='new_obi_button' box='".$obid."' class='new_obi_button' onclick='createobi(".$obid.")'>Click Here</button> </div></li>"; 
    
    $retval .= "<li class='orgboxitem' id='obi_".$obiid."' sid='".$obiid."'><div id='obi_div_".$obiid."'>";
    $retval .= "<a id='obi_url_".$obiid."' href='".$obiurl."' target='_blank'>".$obititle."</a>";
	$retval .=  "<span class='removeit' onclick='removeobi(".$obiid.")'>&nbsp;&nbsp;&nbsp;&nbsp;</span>";
	$retval .=  "<span class='editit' onclick='editobi(".$obiid.")'>&nbsp;&nbsp;&nbsp;&nbsp;</span>";
    $retval .=  "<br><span id='obi_text_".$obiid."'>".$obitext."</span>";
	$retval .=  "<br><span class='obinote' id='obi_note_".$obiid."'>".$obinote."</span>";
    $retval .=  "</div></li>";
    
    $retval .= "</ol>";
	$retval .= "</div>"; 



    $retarray = array();
    $retarray[0] = $retval;
    $retarray[1] = $obiid;
    $retarray[2] = $obid;


	echo  json_encode($retarray, JSON_FORCE_OBJECT) ;
	exit(1);

?>