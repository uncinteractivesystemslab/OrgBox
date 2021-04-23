<?php




	function save_state($data_in) {

		global $h, $u, $p, $dbname;

		$data = array();

		// Use the current session data

		$data['sess_user_id'] = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
		$data['sess_hit_id'] = isset($_SESSION['hit_id']) ? $_SESSION['hit_id'] : null;
		$data['sess_task_id'] = isset($_SESSION['task_id']) ? $_SESSION['task_id'] : null;
		$data['save_reason'] = isset($data_in['save_reason']) ? filter_var($data_in['save_reason'], FILTER_SANITIZE_STRING) : null;
		$data['save_state'] = isset($data_in['save_state']) ? htmlentities($data_in['save_state']) : null;
		$data['save_width'] = isset($data_in['save_width']) ? $data_in['save_width'] : null; //isset($_SESSION['window_width']) ? $_SESSION['window_width'] : null;//
		$data['save_height'] = isset($data_in['save_height']) ? $data_in['save_height'] : null;//isset($_SESSION['window_height']) ? $_SESSION['window_height'] : null;//
		$data['save_res_width'] = isset($_SESSION['monitor_width']) ? $_SESSION['monitor_width'] : null;//isset($data_in['save_res_width']) ? $data_in['save_res_width'] : null;//
		$data['save_res_height'] = isset($_SESSION['monitor_height']) ? $_SESSION['monitor_height'] : null;//isset($data_in['save_res_height']) ? $data_in['save_res_height'] : null;//
		$data['ob_order'] = 1000;

		$data['client_time'] = isset($data_in['client_time']) ? $data_in['client_time'] : null;
		$data['php_time'] = round(microtime(true)*1000);

		if($data['save_reason'] == null){
			return(0);
		}

		try {
			$dbh = new PDO("mysql:host=$h;dbname=$dbname",$u,$p);
			$dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			$dbh->setAttribute( PDO::ATTR_EMULATE_PREPARES, false);

			$sql = "insert into savestates values ";
			$sql .= "(NULL, ";  // state_id will be set by auto_increment

			$sql .= ":ss_reason, ";
			$sql .= ":ss_state, ";
			$sql .= ":sess_user_id, ";
			$sql .= ":sess_hit_id, ";
			$sql .= ":sess_task_id, ";
			$sql .= ":client_time, ";
			$sql .= ":php_time, ";
			$sql .= "now(), ";
			$sql .= ":ss_width, ";
			$sql .= ":ss_height, ";
			$sql .= ":ss_res_width, ";
			$sql .= ":ss_res_height)";

			$sth = $dbh->prepare($sql);
            $data = array('ss_reason'=>$data['save_reason'], 
            'ss_state'=>$data['save_state'], 
            'sess_user_id'=>$data['sess_user_id'], 
            'sess_hit_id'=>$data['sess_hit_id'], 
            'sess_task_id'=>$data['sess_task_id'], 
            'client_time'=>$data['client_time'], 
			'php_time'=>$data['php_time'], 
            'ss_width'=>$data['save_width'], 
			'ss_height'=>$data['save_height'], 
            'ss_res_width'=>$data['save_res_width'], 
			'ss_res_height'=>$data['save_res_height']);
		 
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
