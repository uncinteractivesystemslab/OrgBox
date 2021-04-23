<?php

	function create_ut($data_in) {

		global $h, $u, $p, $dbname;
		

		if (!(isset($_SESSION['user_id'])  
				//&& is_int($_SESSION['user_id']) 
				//&& is_int($_SESSION['megahit_id'])
			))
		{
			return(0);
			exit(1);
		}

		$data = array();

		$data['user_id'] = isset($data_in['user_id']) ? $data_in['user_id'] : null;
		$data['task_id'] = isset($data_in['task_id']) ? $data_in['task_id'] : null;
		$data['system_id'] = isset($data_in['system_id']) ? $data_in['system_id'] : null;

		try {
			$dbh = new PDO("mysql:host=$h;dbname=$dbname",$u,$p);
			$dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			$dbh->setAttribute( PDO::ATTR_EMULATE_PREPARES, false);

			$sql = "insert into usertasks values ";
			$sql .= "(NULL, ";  // event_id will be set by auto_increment
			$sql .= ":user_id, ";
			$sql .= ":task_id, ";
			$sql .= ":system_id)";

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
