
<?php
    session_start();
    
	$_SESSION['get_uid'] = isset($_POST['get_uid']) ? $_POST['get_uid'] : null;
	$_SESSION['get_hid'] = isset($_POST['get_hid']) ? $_POST['get_hid'] : null;
	$_SESSION['page_type'] = isset($_POST['page_type']) ? $_POST['page_type'] : null;
	$_SESSION['page_user_id'] = isset($_POST['page_user_id']) ? $_POST['page_user_id'] : null;

	$_SESSION['page_hit_id'] = isset($_POST['page_hit_id']) ? $_POST['page_hit_id'] : null;
	$_SESSION['page_task_id'] = isset($_POST['page_task_id']) ? $_POST['page_task_id'] : null;
	$_SESSION['page_qid'] = isset($_POST['page_qid']) ? $_POST['page_qid'] : null;
	$_SESSION['page_pg'] = isset($_POST['page_pg']) ? $_POST['page_pg'] : null;

	$_SESSION['page_seq_num'] = isset($_POST['page_seq_num']) ? $_POST['page_seq_num'] : null;
	$_SESSION['page_creation_time'] = isset($_POST['page_creation_time']) ? $_POST['page_creation_time'] : null;

	exit(1);

?>