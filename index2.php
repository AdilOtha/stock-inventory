<?php
	require("php_action/session.php");
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width initial-scale=1.0">
	<title>Welcome Admin</title>
	<link rel="stylesheet" type="text/css" href="assets/bootstrap/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="assets/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="custom/css/custom.css">
	<link rel="stylesheet" type="text/css" href="assets/jqueryui/jquery-ui.min.css">

	<style type="text/css">
		body{
			background-color: rgba(30,144,255,0.8);
		}
		.nav-item{
			background: #ffff;
			font-size: 25px;
			padding: 30px;
			border-radius: 10px;
			box-shadow: 0px 0px 10px 0px #000;
			text-align: center;
		}
		.dropdown-menu{
			font-size: 25px;
		}
		.jumbotron{
			text-align: center;
		}
	</style>
</head>
<body>
	<div class="jumbotron jumbotron-fluid">
	    <div class="container">
	    	<h1>Welcome to Stock Inventory Management System</h1>
	  	</div>
	</div>
	<section class="container-fluid bg">
		<section class="row justify-content-center">
  		<ul class="nav flex-column">
  			<li class="nav-item dropdown">
  				<a href="php_action/student.php" class="nav-link">Student DB</a>
  			</li>
  			<br>
    		<li class="nav-item dropdown">
		      <a class="nav-link dropdown-toggle" href="php_action/electronics.php" id="navbardrop" data-toggle="dropdown">
		        Electronics
		      </a>
		      <div class="dropdown-menu">
		        <a class="dropdown-item" href="php_action/electronics.php">Products</a>
		        <a class="dropdown-item" href="php_action/kits.php">Kits</a>
		        <a class="dropdown-item" href="php_action/orders_elec.php">Orders</a>			      
		      </div>
		    </li>
		    <br>
	    	<li class="nav-item dropdown">
		      <a class="nav-link dropdown-toggle" href="php_action/books.php" id="navbardrop" data-toggle="dropdown">
		        Books
		      </a>
		      <div class="dropdown-menu">
		        <a class="dropdown-item" href="php_action/books.php">Products</a>
		        <a class="dropdown-item" href="#">Orders</a>			      
		      </div>
		    </li>
		    <br>
	    	<li class="nav-item dropdown">
		      <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
		        Notes
		      </a>
		      <div class="dropdown-menu">
		        <a class="dropdown-item" href="php_action/notes.php">All Notes</a>
		        <a class="dropdown-item" href="#">Notes sorted by Book</a>			      
		      </div>
		    </li>
	  	</ul>
	 	</section>
	</section>
</body>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
	<script type="text/javascript" src="assets/jquery/jquery.min.js"></script>
	<script type="text/javascript" src="assets/jqueryui/jquery-ui.min.js"></script>
	<script type="text/javascript" src="assets/bootstrap/bootstrap.min.js"></script>
</html>