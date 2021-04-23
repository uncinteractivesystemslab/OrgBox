<?php
	function save_doc($data_in) {

		global $h, $u, $p, $dbname;

		$data = array();

		// Use the current session data

		$data['sess_user_id'] = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
		$data['sess_hit_id'] = isset($_SESSION['hit_id']) ? $_SESSION['hit_id'] : null;
		$data['sess_task_id'] = isset($_SESSION['task_id']) ? $_SESSION['task_id'] : null;
		$data['save_reason'] = isset($data_in['save_reason']) ? filter_var($data_in['save_reason'], FILTER_SANITIZE_STRING) : null;
		$data['save_state'] = isset($data_in['save_state']) ? htmlentities($data_in['save_state']) : null;
		$data['save_html'] = isset($data_in['save_html']) ? htmlentities($data_in['save_html']) : null;

		$data['client_time'] = isset($data_in['client_time']) ? $data_in['client_time'] : null;
		$data['php_time'] = round(microtime(true)*1000);

		

		try {
			$dbh = new PDO("mysql:host=$h;dbname=$dbname",$u,$p);
			$dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			$dbh->setAttribute( PDO::ATTR_EMULATE_PREPARES, false);

			$sql = "insert into orgdoclog values ";
			$sql .= "(NULL, ";  // state_id will be set by auto_increment

			$sql .= ":odl_reason, ";
			$sql .= ":odl_state, ";
			$sql .= ":odl_html, ";
			$sql .= ":sess_user_id, ";
			$sql .= ":sess_hit_id, ";
			$sql .= ":sess_task_id, ";
			$sql .= ":client_time, ";
			$sql .= ":php_time, ";
			$sql .= "now())";

			$sth = $dbh->prepare($sql);
            $data = array('odl_reason'=>$data['save_reason'], 
            'odl_state'=>$data['save_state'], 
            'odl_html'=>$data['save_html'], 
            'sess_user_id'=>$data['sess_user_id'], 
            'sess_hit_id'=>$data['sess_hit_id'], 
            'sess_task_id'=>$data['sess_task_id'], 
            'client_time'=>$data['client_time'], 
			'php_time'=>$data['php_time']);
		 
			$sth->execute($data);

			$event_id = $dbh->lastInsertId();

			return($event_id);

		} catch (PDOException $e) {

			//return(0);
			return("error = " . $e->getMessage());
			//return("Database error.");

		} // end try..catch
		return(1);

	} // end function


?>
