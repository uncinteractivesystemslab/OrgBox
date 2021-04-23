<?php

	function create_obi($data_in) {

		global $h, $u, $p, $dbname;

		$data = array();

		// Use the current session data

		$data['sess_user_id'] = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
		$data['sess_hit_id'] = isset($_SESSION['hit_id']) ? $_SESSION['hit_id'] : null;
		//$data['sess_megahit_id'] = isset($_SESSION['megahit_id']) ? $_SESSION['megahit_id'] : null;
		$data['obi_url'] = isset($data_in['obi_url']) ? filter_var($data_in['obi_url'], FILTER_SANITIZE_URL) : null;
		$data['obi_text'] = isset($data_in['obi_text']) ? filter_var($data_in['obi_text'], FILTER_SANITIZE_STRING) : null;
		$data['obi_note'] = isset($data_in['obi_note']) ? filter_var($data_in['obi_note'], FILTER_SANITIZE_STRING) : null;
		$data['obi_title'] = isset($data_in['obi_title']) ? filter_var($data_in['obi_title'], FILTER_SANITIZE_STRING) : null;
		$data['obi_box_id'] = isset($data_in['obi_box_id']) ? $data_in['obi_box_id'] : null;
		$data['obi_order'] = 1000;
		$data['obi_level'] = 1;


		try {
			$dbh = new PDO("mysql:host=$h;dbname=$dbname",$u,$p);
			$dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			$dbh->setAttribute( PDO::ATTR_EMULATE_PREPARES, false);

			$sql = "insert into obitems values ";
			$sql .= "(NULL, ";  // event_id will be set by auto_increment

			$sql .= ":obi_order, ";
			$sql .= ":obi_level, ";
			$sql .= ":obi_text, ";
			$sql .= ":obi_url, ";
			$sql .= ":obi_title, ";
			$sql .= ":obi_box_id, ";
			$sql .= ":sess_user_id, ";
			$sql .= ":sess_hit_id, ";

					
			$sql .= "1, "; //set to active
			$sql .= ":obi_note)";  


			$sth = $dbh->prepare($sql);


			$sth->execute($data);

			$event_id = $dbh->lastInsertId();

			return("$event_id");

		} catch (PDOException $e) {

			//return(0);
			return("error = " . $e->getMessage());
			//return("Database error.");

		} // end try..catch
		return(1);

	} // end function


?>
