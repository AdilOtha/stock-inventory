<?php 
	require '../session.php';

	//remove all session variables
	session_unset();

	//destroy the session
	session_destroy();

	header("../login.php");
 ?>