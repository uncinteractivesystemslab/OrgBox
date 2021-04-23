<?php
	session_start();

	// remove_ob.php
	//	
	// expects:
	//    ob params passed via POST
	//


	require_once("../util/utils.php");
	require_once("remove_ob_func.php");
	require_once("remove_obi_func.php");

	$data = array();

	$data['ob_box_id'] = isset($_POST['ob_id']) ? $_POST['ob_id'] : null;
	$data['ob_items'] = isset($_POST['ob_items']) ? $_POST['ob_items'] : null;

	$retval = remove_ob($data);
    $pairs = explode("&",$data['ob_items']);
    foreach ($pairs as $pair)
    {
        $exploded_pair = explode("=", $pair);
        preg_match('#\[(.*?)\]#', $exploded_pair[0], $match);
        $data['obi_box_id'] = $match[1];
		$retval2 .= remove_obi($data);
    }

	echo "status=" . $retval;
	exit(1);




?>
