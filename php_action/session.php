<?php
	session_start();
	require 'db_connect.php';
	if(!isset($_SESSION['login'])){
		header("location: ./login.php");
	}
?>
