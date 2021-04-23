<?php
	function edit_obi($data_in) {

		global $h, $u, $p, $dbname;

		$data = array();

		// Use the current session data

		$data['sess_user_id'] = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
		$data['sess_hit_id'] = isset($_SESSION['hit_id']) ? $_SESSION['hit_id'] : null;
		$data['obi_url'] = isset($data_in['obi_url']) ? filter_var($data_in['obi_url'], FILTER_SANITIZE_URL) : null;
		$data['obi_text'] = isset($data_in['obi_text']) ? filter_var($data_in['obi_text'], FILTER_SANITIZE_STRING) : null;
		$data['obi_note'] = isset($data_in['obi_note']) ? filter_var($data_in['obi_note'], FILTER_SANITIZE_STRING) : null;
		$data['obi_title'] = isset($data_in['obi_title']) ? filter_var($data_in['obi_title'], FILTER_SANITIZE_STRING) : null;
		$data['obi_id'] = isset($data_in['obi_id']) ? $data_in['obi_id'] : null;


		try {
			$dbh = new PDO("mysql:host=$h;dbname=$dbname",$u,$p);
			$dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			$dbh->setAttribute( PDO::ATTR_EMULATE_PREPARES, false);

			$sql = "update obitems set ";

			$sql .= "obi_title = :obi_title, ";
			$sql .= "obi_text = :obi_text, ";
			$sql .= "obi_note = :obi_note, ";
			$sql .= "obi_url = :obi_url ";

            $sql .= "where obitem_id = :obiid";

            $sth = $dbh->prepare($sql);
            $data = array('obiid'=>$data['obi_id'], 
            'obi_title'=>$data['obi_title'], 
            'obi_text'=>$data['obi_text'],  
            'obi_note'=>$data['obi_note'], 
            'obi_url'=>$data['obi_url']);
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
