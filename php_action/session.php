<?php
	session_start();
	require 'db_connect.php';
	if($_SESSION['login']==NULL){
		header("location: ../login.php");
	}
?>