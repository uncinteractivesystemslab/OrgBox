<?php


	function create_ob($data_in) {

		global $h, $u, $p, $dbname;
		

		if (!(isset($_SESSION['user_id']) 
				&& isset($_SESSION['hit_id']) 
			))
		{
			return(0);
			exit(1);
		}

		$data = array();

		// Use the current session data

		$data['sess_user_id'] = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
		$data['sess_hit_id'] = isset($_SESSION['hit_id']) ? $_SESSION['hit_id'] : null;
		$data['ob_title'] = isset($data_in['ob_title']) ? filter_var($data_in['ob_title'], FILTER_SANITIZE_STRING) : null;
		$data['ob_order'] = 1000;


		try {
			$dbh = new PDO("mysql:host=$h;dbname=$dbname",$u,$p);
			$dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			$dbh->setAttribute( PDO::ATTR_EMULATE_PREPARES, false);

			$sql = "insert into orgboxes values ";
			$sql .= "(NULL, ";  // event_id will be set by auto_increment

			$sql .= ":ob_title, ";
			$sql .= ":ob_order, ";
			$sql .= "1, ";
			$sql .= ":sess_user_id, ";
			$sql .= ":sess_hit_id, ";
			$sql .= "NULL, "; //Structure
			$sql .= "NULL, "; //Position
			$sql .= "NULL, "; //Height
			$sql .= "NULL)"; //Width

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
