<?php
	session_start();

	require_once("../util/utils.php");
	require_once("create_ob_func.php");


	$data = array();

	$data['ob_title'] = isset($_POST['ob_title']) ? $_POST['ob_title'] : null;
	
	
	$obid = create_ob($data);
	$obtitle = $data['ob_title'];


	$retval = "";
	
	$retval .= "<div class='orgboxdiv'  box='".$obid."' id='ob_".$obid."'>";
	$retval .= "<div id='ob_div_$obid'>";
	$retval .= "<span class='obtitlespan' id='ob_title_".$obid."' >".$obtitle."</span>";
	$retval .=  "<span class='removeit' onclick='removeob(".$obid.", \"".$obtitle."\")'>&nbsp;&nbsp;&nbsp;&nbsp;</span>";
	$retval .=  "<span class='editit' onclick='editob(".$obid.", \"".$obtitle."\")'>&nbsp;&nbsp;&nbsp;&nbsp;</span>";
	$retval .= "</div>";
	$retval .= "<hr/>";
	$retval .= "<ol class='orgboxitems' id='orgboxitems".$obid."' box='".$obid."'>";
	$retval .= "<li class='orgboxitemnew'><div> Drag text here to add a new item or <button id='new_obi_button' box='".$obid."' class='new_obi_button' onclick='createobi(".$obid.")'>Click Here</button> </div></li>"; 
	$retval .= "</ol>";
	$retval .= "</div>"; 



	$retarray = array();
	$retarray[0] = $retval;
	$retarray[1] = $obid;
 
 
	echo  json_encode($retarray, JSON_FORCE_OBJECT) ;
	exit(1);

?>