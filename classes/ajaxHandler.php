<?php
	session_start();
	require_once('main.php');
	if(isset($_REQUEST['out'])) {
		$toDownload = array();
		$toDownload = $_REQUEST['out'];
		$obj = unserialize($_SESSION['Object']);
		$obj->downloadData($toDownload);
	}
?>