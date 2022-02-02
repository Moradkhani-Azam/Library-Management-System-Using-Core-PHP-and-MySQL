<?php
	require "db_connect.php";
	require "message_display.php";
	session_start();
	
	if(empty($_SESSION['role']));
	else if($_SESSION['role'] == "employee")
		header("Location: employee/home.php");
	else if($_SESSION['role'] == "member")
		header("Location: member/home.php");
	else if($_SESSION['role'] == "administrator")
		header("Location: administrator/home.php");
?>

<html lang="en">
	<head>
		<meta charset="utf-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>The Port-Cartier municipal library website</title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="css/custom_style.css">
	</head>
	<body>
		<header class="px-5 py-3 bg-dark bg-gradient">
			<a href="./">
				<div id="logo">
					<img src="img/Library.svg" alt="Logo" width="55" height="55" />
					<p class="px-2 fs-5">Port-Cartier municipal library</p>
				</div>
			</a>
		</header>
		<div class="container">
	    	<legend class="text-center m-3 fw-bold">User Login</legend>

	    	<div class="row justify-content-center">
				<div id="alert-message">
					<p class="mb-0" id="message"></p>
				</div>
			</div>

			<form method="POST">
				<div class="row m-5 justify-content-center">
					<div class="col-12 col-sm-6">
						<div class="form-floating icon">
							<input type="email" class="form-control" id="email" name="email" placeholder="example@gmail.com" required />
				    		<label for="email" class="form-label">Email address</label>
						</div>
					</div>
				</div>

				<div class="row m-5 justify-content-center">
					<div class="col-12 col-sm-6">
						<div class="form-floating icon">
							<input type="password" class="form-control" name="password" placeholder="Password" required />
					    	<label for="password" class="form-label">Password</label>
						</div>
					</div>
				</div>
					
				<div class="row justify-content-center">
					<div class="col-1">
						<button type="submit" class="btn btn-primary" name="login">Login</button>
					</div>
				</div>
			</form>
		</div>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
	</body>

	<?php
		if(isset($_POST['login']))
		{
			$query = "SELECT u.id as user_id, u.full_name, u.password, r.name as role FROM users u JOIN roles r ON u.role_id = r.id  WHERE email= '" . $_POST['email'] ."'";

			$result = mysqli_query($connection, $query);

			if (mysqli_num_rows($result) > 0) {
				$row = mysqli_fetch_assoc($result);
				if(password_verify($_POST['password'], $row['password'])) {
					if($row['role'] === 'member') {
						$_SESSION['role'] = "member";
						$_SESSION['user_id'] = $row['user_id'];
						$_SESSION['full_name'] = $row['full_name'];
						header("Location: member/home.php");
					}
					if($row['role'] === 'employee') {
						$_SESSION['role'] = "employee";
						$_SESSION['user_id'] = $row['user_id'];
						$_SESSION['full_name'] = $row['full_name'];
						header("Location: employee/home.php");
					}
					if($row['role'] === 'administrator') {
						$_SESSION['role'] = "administrator";
						$_SESSION['user_id'] = $row['user_id'];
						$_SESSION['full_name'] = $row['full_name'];
						header("Location: administrator/home.php");
					}
					else echo error("User type is not defined!");
				} else echo error("Invalid password!");

			} else echo error("Invalid emai!");
		}
	?>
</html>