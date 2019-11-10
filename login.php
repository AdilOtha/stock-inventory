<!DOCTYPE html>
<html>
<head>
	<title>Log In</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="styles/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="font-awsome/css/font-awesome.min.css">
</head>
<style type="text/css">
	body{
		background-color: rgba(30,144,255,0.8);
	}
	.form-container{
	position: absolute;
	top:15vh;
	background: #ffff;
	padding: 30px;
	border-radius: 10px;
	box-shadow: 0px 0px 10px 0px #000;
}
</style>
<body>
	<?php
	session_start();
	require('php_action/db_connect.php');
		if(isset($_POST['submit'])){
				$uname=$_POST['uname'];
				$pswd=$_POST['pswd'];$sql="SELECT * FROM users WHERE email='$uname' && password='$pswd' ";
				$result=$connect->query($sql);
				$count=$result->num_rows;
				if($count>0){
					$_SESSION['login']=$uname;
					header('location: index2.php');
				}
				else{
					echo "incorrect email or password";
				}
				
		}
	?>
	<div id="form1">
		<section class="container-fluid bg">
			<section class="row justify-content-center">
				<section class="col-12 col-sm-6 col-md-3">
					<form action="login.php" method="post" class="form-container">
					<div class="form-group">
						<label for="email">Email address:</label><br>
						<input type="email" name="uname" class="form-control" id="email" validate><br>
					</div>
					<div class="form-group">
						<label for="pswd">Password:</label><br>
						<input type="Password" class="form-control" name="pswd" id="pswd"><br>
					</div>
					<div>
						<input class="btn btn-primary btn-block" class="form-control" type="submit" name="submit" value="Login"><br>
					</div>
					</form>	
				</section>
			</section>
		</section>
	</div>
</body>
	<script type="text/javascript" src="js/jquery-331.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
</html>