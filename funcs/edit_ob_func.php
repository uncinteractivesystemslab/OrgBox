<?php

	function edit_ob($data_in) {

		global $h, $u, $p, $dbname;

		$data = array();

		// Use the current session data

		$data['sess_user_id'] = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
		$data['sess_hit_id'] = isset($_SESSION['hit_id']) ? $_SESSION['hit_id'] : null;
		$data['ob_title'] = isset($data_in['ob_title']) ? filter_var($data_in['ob_title'], FILTER_SANITIZE_STRING) : null;
		$data['ob_id'] = isset($data_in['ob_id']) ? $data_in['ob_id'] : null;


		try {
			$dbh = new PDO("mysql:host=$h;dbname=$dbname",$u,$p);
			$dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			$dbh->setAttribute( PDO::ATTR_EMULATE_PREPARES, false);


			$sql = "update orgboxes set ";

			$sql .= "ob_title = :ob_title ";

            $sql .= "where ob_id = :obid";

            $sth = $dbh->prepare($sql);
            $data = array('obid'=>$data['ob_id'], 
            'ob_title'=>$data['ob_title']);
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
