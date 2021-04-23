<?php
	function order_ob_packery($data_in) {

		global $h, $u, $p, $dbname;

		$data = array();

		// Use the current session data
		$data['sess_user_id'] = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
		$data['sess_hit_id'] = isset($_SESSION['hit_id']) ? $_SESSION['hit_id'] : null;
        $data['obs'] = isset($data_in['obs']) ? $data_in['obs'] : null;


		try {
            $dbh = new PDO("mysql:host=$h;dbname=$dbname",$u,$p);
            $dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $dbh->setAttribute( PDO::ATTR_EMULATE_PREPARES, false);

			$i = 0;
            foreach ($data['obs'] as $value) {
                
                $obid = explode("_",$value);
                
                $sql = "update orgboxes set ob_order = :new_order ";
                $sql .= "where ob_id = :obid";

                $sth = $dbh->prepare($sql);
                $data = array('obid'=>$obid[0], 'new_order'=>$obid[1]);
                $sth->execute($data);
                $i++;
            }




			return("success");

		} catch (PDOException $e) {

			//return(0);
			return("error = " . $e->getMessage());
			//return("Database error.");

		} // end try..catch
		return(1);

	} // end function


?>
