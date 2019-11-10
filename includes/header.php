<?php 
	require '../php_action/session.php';
 ?>
 <!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width initial-scale=1.0">
	<title>Welcome Admin</title>
	<link rel="stylesheet" type="text/css" href="../assets/bootstrap/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="../assets/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="../custom/css/custom.css">
	<link rel="stylesheet" type="text/css" href="../assets/jqueryui/jquery-ui.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<style type="text/css">
		body{
			background-color: rgba(30,144,255,0.8);
		}
		nav.navbar:hover{
			box-shadow: 0px 4px 8px 0 rgba(0,0,0,0.2);
		}
	</style>
</head>
<body>
	<nav class="navbar navbar-expand-lg navbar-light bg-light navbar-static-top">
	    <a class="navbar-brand" href="#">SIMS</a>
	    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
	  		<span class="navbar-toggler-icon"></span>
	    </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item" id="navDashboard">
        <a class="nav-link" href="../index2.php"><i class="fa fa-list-alt"></i> Home<span class="sr-only">(current)</span></a>
      </li>
      <li id="navStudent">
        <a class="nav-link" href="student.php"><i class="fa fa-user"></i> Student</a>
      </li>
      <li class="nav-item dropdown" id="navElectronics">
        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-cog"></i> Electronics</a>
        <div class="dropdown-menu">
            <a class="dropdown-item" href="electronics.php">Products</a>
            <a class="dropdown-item" href="orders_elec.php">Orders</a>            
        </div>
      </li>
      <li class="nav-item dropdown"  id="navBooks">
        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-book" aria-hidden="true"></i> Books
        </a>
        <div class="dropdown-menu">
          <a class="dropdown-item" href="books.php">Products</a>
          <a class="dropdown-item" href="orders_books.php">Orders</a>
        </div>
      </li>
      <li id="navNotes">
        <a class="nav-link" href="notes.php"><i class="fa fa-file" aria-hidden="true"></i> Notes</a>
      </li>
      <li id="navKits">
        <a class="nav-link" href="kits.php"><i class="fa fa-shopping-bag" aria-hidden="true"></i> Kits</a>
      </li>
      <li id="navRestock">
        <a class="nav-link" href="restock.php"><i class="fa fa-plus-circle" aria-hidden="true"></i></i> Restock</a>
      </li>
      <!--<li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Orders
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="#">Action</a>
          <a class="dropdown-item" href="#">Another action</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#">Something else here</a>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
      </li>-->
    </ul>
  </div>
</nav>
</body>
