<?php

	function resize_ob($data_in) {

		global $h, $u, $p, $dbname;
		$data = array();

		// Use the current session data
		$data['sess_user_id'] = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
		$data['sess_hit_id'] = isset($_SESSION['hit_id']) ? $_SESSION['hit_id'] : null;
        $data['obid'] = isset($data_in['obid']) ? $data_in['obid'] : null;
        $data['obwidth'] = isset($data_in['obwidth']) ? $data_in['obwidth'] : null;
        $data['obheight'] = isset($data_in['obheight']) ? $data_in['obheight'] : null;


		try {
            $dbh = new PDO("mysql:host=$h;dbname=$dbname",$u,$p);
            $dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $dbh->setAttribute( PDO::ATTR_EMULATE_PREPARES, false);

			$i = 0;
                
                
                $sql = "update orgboxes set ob_width = :ob_width, ob_height = :ob_height ";
                $sql .= "where ob_id = :obid";

                $sth = $dbh->prepare($sql);
                $data = array('obid'=>$data['obid'], 'ob_width'=>$data['obwidth'], 'ob_height'=>$data['obheight']);
                $sth->execute($data);




			return("success");

		} catch (PDOException $e) {

			//return(0);
			return("error = " . $e->getMessage());
			//return("Database error.");

		} // end try..catch
		return(1);

	} // end function


?>
