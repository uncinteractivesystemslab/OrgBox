<?php

    function load_obi($keyID) {

        $data_in = find_obi($keyID);        
        structure_obi($data_in);
    }
  
    function new_obi() 
    {
        $obiid = $data_in["obi_id"];
        $returnObi = "<li class='orgboxitem' id='obi_".$obiid."' sid='".$obiid."'><div id='obi_div_".$obiid."'>neeew";
        $returnObi .= structure_obi($data_in);
        $returnObi .=  "</div></li>";
        echo $returnObi;
    }

    function structure_obi($data_in) 
    {

        $obiid = isset($data_in['obi_id']) ? $data_in['obi_id'] : "1";
        $obititle = isset($data_in['obi_title']) ? $data_in['obi_title'] : "Blank Title";
        $obiurl = isset($data_in['obi_url']) ? $data_in['obi_url'] : "www.google.com";
        $obitext = isset($data_in['obi_text']) ? $data_in['obi_text'] : "Blank Text";
        $obinote = isset($data_in['obi_note']) ? $data_in['obi_note'] : "";

        $echoText .= "<a id='obi_url_".$obiid."' href='".$obiurl."' target='_blank'>".$obititle."</a>";
        $echoText .=  "<span class='removeit' onclick='removeobi(".$obiid.")'>&nbsp;&nbsp;&nbsp;&nbsp;</span>";
        $echoText .=  "<span class='editit' onclick='editobi(".$obiid.")'>&nbsp;&nbsp;&nbsp;&nbsp;</span>";
        //$echoText .=  "<img src='./img/remove.png' alt='Remove' height='16' width='16'> </span>";
        //$echoText .=  "<img src='./img/edit.png' alt='Edit' height='16' width='16'>&nbsp;&nbsp;</span>";
        $echoText .=  "<br><span id='obi_text_".$obiid."'>".$obitext."</span>";
        if($obinote != null)
        {
        $echoText .=  "<br><span class='obinote' id='obi_note_".$obiid."'>".$obinote."</span>";
        }else{
            $echoText .=  "<br><br>";
        }

        echo $echoText;
    }

    function find_obi($keyID){

        global $h, $u, $p, $dbname;

		/* if (!(isset($_SESSION['user_id']) 
				&& isset($_SESSION['megahit_id']) 
				//&& is_int($_SESSION['user_id']) 
				//&& is_int($_SESSION['megahit_id'])
			))
		{
			return(0);
			exit(1);
		} */ 

		$data = array();

        try {
            $db = mysqli_connect($h,$u,$p,$dbname);
			$sql = "SELECT * FROM obitems WHERE obitem_id='$keyID'";
            $result = mysqli_query($db, $sql);
            foreach($result as $item){ 
                $data['obi_id']  = $item['obitem_id']; 
                $data['obi_text']  = $item['obi_text']; 
                $data['obi_url']  = $item['obi_url']; 
                $data['obi_title']  = $item['obi_title']; 
                $data['obi_note']  = $item['obi_note'];
                return $data;
            }

		} catch (PDOException $e) {

			return("error = " . $e->getMessage());

		} // end try..catch
        
        return("no results");
    }

    

    function load_bookmark($keyID) {

        $data_in = find_obi($keyID);
        
        structure_bookmark($data_in);
    }

    function structure_bookmark($data_in) {
        $obiid = isset($data_in['obi_id']) ? $data_in['obi_id'] : "1";
        $obititle = isset($data_in['obi_title']) ? $data_in['obi_title'] : "Blank Title";
        $obiurl = isset($data_in['obi_url']) ? $data_in['obi_url'] : "www.google.com";
        $obitext = isset($data_in['obi_text']) ? $data_in['obi_text'] : "Blank Text";

        $echoText .= "<a id='obi_url_".$obiid."' href='".$obiurl."' target='_blank'>".$obititle."</a>";
        $echoText .=  "<span class='removeit' onclick='removeobi(".$obiid.")'>&nbsp;&nbsp;&nbsp;&nbsp;</span>";
        $echoText .=  "<br><span id='obi_text_".$obiid."'>".$obitext."</span>";

        echo $echoText;
    }

    function find_ob_position($keyID){

        global $h, $u, $p, $dbname;

		$data = array();

        try {
            $db = mysqli_connect($h,$u,$p,$dbname);
			$sql = "SELECT * FROM orgboxes WHERE ob_id='$keyID'";
            $result = mysqli_query($db, $sql);
            foreach($result as $item){ 
                $data['ob_position']  = $item['ob_position'];
                
            }

            return $data['ob_position'];

		} catch (PDOException $e) {

			return("error = " . $e->getMessage());

		} // end try..catch
        
        return("no results");
    }

    function find_ob_size($keyID){

        global $h, $u, $p, $dbname;

		$data = array();

        try {
            $db = mysqli_connect($h,$u,$p,$dbname);
			$sql = "SELECT * FROM orgboxes WHERE ob_id='$keyID'";
            $result = mysqli_query($db, $sql);
            foreach($result as $item){ 
                $data['ob_width']  = $item['ob_width'];
                $data['ob_height']  = $item['ob_height'];
                
            }

            return $data;

		} catch (PDOException $e) {

			return("error = " . $e->getMessage());

		} // end try..catch
        
        return("no results");
    }


    function load_od_text($uid, $hid){

        global $h, $u, $p, $dbname;

		$data = array();

        try {
            $db = mysqli_connect($h,$u,$p,$dbname);
			$sql = "SELECT * FROM orgdoclog WHERE odl_user_id='$uid' and odl_hit_id='$hid' order by ptime desc limit 1";
            $result = mysqli_query($db, $sql);
            foreach($result as $item){ 
                $data['odl_state']  = $item['odl_state'];
                
            }

            return $data['odl_state'];

		} catch (PDOException $e) {

			return("error = " . $e->getMessage());

		} // end try..catch
        
        return("no results");
    }

?>