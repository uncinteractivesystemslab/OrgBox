<?php

// Define constants
define("STATUS_OKAY", 1);
define("STATUS_NOT_LOGGED_IN", 2);
define("STATUS_NO_CURRENT_PROJECT", 3);
define("STATUS_NO_QUERY", 4);
define("DB_ERROR", 5);
define("DEFAULT_ERRMSG",
			"Sorry, there was a problem with the system.  Please try again.");


// Define global vars

require_once("setvars.php");



function sanit_str ($a) {
	if (isset($a)) { return strip_tags($a); } else { return null; }
}


function sanit_int ($a) {
	if (isset($a)) { return intval($a); } else { return null; }
}


function isset_else_empty ($a) {
	if (isset($a)) { return $a; } else { return ''; }
}


function isset_else_null ($a) {
	if (isset($a)) { return $a; } else { return null; }
}

function isset_else_neg1 ($a) {
	if (isset($a)) { return $a; } else { return -1; }
}


function exit_error_pdo($e, $user_msg=DEFAULT_ERRMSG) {

	global $debug;

	// $user_msg will be output to the user
	echo $user_msg;
	echo "<p>";

	if ($debug==1) { echo $e->getMessage(); }

	exit();
}



function exit_error($err, $msg = "") {
	$retval = array();
	$retval['error'] = $err;
	if ($msg != "") {
		$retval['message'] = $msg;
	}
	echo json_encode($retval);
	exit();
}

function exit_dberror($mysqli) {
	$retval = array();
	$retval['error'] = DB_ERROR;
	if ($msg != "") {
		$retval['message'] = $mysqli->error;
	}
	echo json_encode($retval);
	exit();
}

function debug_echo($msg) {
	global $debug;
	if ($debug==1) { echo $msg; }
}


function get_query_string($qid) {

	global $h,$u,$p,$dbname;

	try {
		$dbh = new PDO("mysql:host=$h;dbname=$dbname",$u,$p);
		$dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		$dbh->setAttribute( PDO::ATTR_EMULATE_PREPARES, false);

		$sql = "select query_string from queries where query_id=:query_id";

		$sth = $dbh->prepare($sql);
		$data = array('query_id'=>$qid);
		$sth->execute($data);

		$row = $sth->fetch();
		$query_string = $row['query_string'];

		return($query_string);

	} catch (PDOException $e) {

		exit_error_pdo($e);

	} // end try..catch

} // end set_query_string()



function set_sess_hit_info() {

	global $h,$u,$p,$dbname; 

	try {
		$dbh = new PDO("mysql:host=$h;dbname=$dbname",$u,$p);
		$dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		$dbh->setAttribute( PDO::ATTR_EMULATE_PREPARES, false);

		$sql = "select t.task_id, t.task_desc, t.task_num, t.task_version, " .
					"h.interface, h.seq_num " .
					"from tasks t, hits h " .
					"where h.task_id = t.task_id and h.hit_id=:hit_id";

		$sth = $dbh->prepare($sql);
		$data = array('hit_id'=>$_SESSION['hit_id']);
		$sth->execute($data);
		$num_rows = $sth->rowCount();
		if ($num_rows > 0) {
			$row = $sth->fetch();
			$_SESSION['task_id'] = $row['task_id'];
			//settype($_SESSION['task_id'], "integer");
			$_SESSION['task_desc'] = $row['task_desc'];
			$_SESSION['task_num'] = $row['task_num'];
			$_SESSION['task_version'] = $row['task_version'];
			$_SESSION['interface'] = $row['interface'];
			$_SESSION['seq_num'] = $row['seq_num'];
		}

	} catch (PDOException $e) {

		exit_error_pdo($e);

	} // end try..catch


} // end set_task_desc()



?>

