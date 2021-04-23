<?php
	function remove_ob($data_in) {

		global $h, $u, $p, $dbname;

		$data = array();

		// Use the current session data
		$data['sess_user_id'] = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
		$data['sess_hit_id'] = isset($_SESSION['hit_id']) ? $_SESSION['hit_id'] : null;
        $data['ob_box_id'] = isset($data_in['ob_box_id']) ? $data_in['ob_box_id'] : null;
		$data['ob_items'] = isset($data_in['ob_items']) ? $data_in['ob_items'] : null;


		try {
            $dbh = new PDO("mysql:host=$h;dbname=$dbname",$u,$p);
            $dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			$dbh->setAttribute( PDO::ATTR_EMULATE_PREPARES, false);
			

            $sql = "update orgboxes set ob_active = :new_state ";
            $sql .= "where ob_id = :obid";

            $sth = $dbh->prepare($sql);
            $data = array('obid'=>$data['ob_box_id'], 'new_state'=>FALSE);
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
