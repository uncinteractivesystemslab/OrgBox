<?php

	function log_event($data_in) {

		global $h, $u, $p, $dbname;

		$data = array();

		$data['sess_user_id'] = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
		$data['sess_hit_id'] = isset($_SESSION['hit_id']) ? $_SESSION['hit_id'] : null;
		$data['sess_megahit_id'] = isset($_SESSION['megahit_id']) ? $_SESSION['megahit_id'] : null;
		$data['sess_task_id'] = isset($_SESSION['task_id']) ? $_SESSION['task_id'] : null;

		$data['old_value'] = isset($data_in['old_value']) ? $data_in['old_value'] : null;
		$data['new_value'] = isset($data_in['new_value']) ? $data_in['new_value'] : null;
		$data['save_state_id'] = isset($data_in['save_state_id']) ? $data_in['save_state_id'] : null;
		$data['attribute'] = isset($data_in['attribute']) ? $data_in['attribute'] : null;
		$data['system_id'] = isset($_SESSION['system_id']) ? $_SESSION['system_id'] : null;


		try {
			$dbh = new PDO("mysql:host=$h;dbname=$dbname",$u,$p);
			$dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			$dbh->setAttribute( PDO::ATTR_EMULATE_PREPARES, false);

			$sql = "insert into tablename values ";
			$sql .= "(NULL, ";  // event_id will be set by auto_increment

			$sql .= ":sess_megahit_id, ";
			$sql .= ":sess_user_id, ";
			$sql .= ":sess_hit_id, ";
			$sql .= ":sess_task_id, ";

			$sql .= ":old_value, ";
			$sql .= ":new_value, ";
			$sql .= ":save_state_id, "; 
			$sql .= ":attribute,";  
			$sql .= ":system_id)"; 

			$sth = $dbh->prepare($sql);

			$sth->execute($data);

			$event_id = $dbh->lastInsertId();

			return("success, event_id = $event_id");

		} catch (PDOException $e) {
			return("error = " . $e->getMessage());

		} // end try..catch
		return(1);

	} // end function


?>
