<?php
	require "../db_connect.php";
	require "../message_display.php";

	session_start();
	
	if(empty($_SESSION['role']) || $_SESSION['role'] != "administrator")
		header("Location: ../index.php");

?>

<html>
	<head>
		<meta charset="utf-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>The Port-Cartier municipal library website</title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="../css/custom_style.css">
	</head>

	<body>
		<header class="px-5 py-3 bg-dark bg-gradient">
			<ul class="nav justify-content-between">
				<li class="nav-item">
					<a href="./">
						<div id="logo">
							<img src="../img/Library.svg" alt="Logo" width="55" height="55" />
							<p class="px-2 fs-5">Port-Cartier municipal library</p>
						</div>
					</a>
				</li>
				<li class="nav-item dropdown">
					<button class="btn btn-danger dropdown-toggle" type="button" id="dropdownMenu" data-bs-toggle="dropdown" aria-expanded="false"><?php echo $_SESSION['full_name'] ?></button>
					<ul class="dropdown-menu" aria-labelledby="dropdownMenu">
						<li><a class="dropdown-item" href="../logout.php">Logout</a></li>
					</ul>
				</li>
			</ul>
		</header>
		<div class="container">
			<div class="row justify-content-center m-3">
				<div class="col-3">
					<a class="btn btn-info" href="users.php">List of all users</a>
				</div>
			</div>
			<div class="row justify-content-center m-3">
				<div class="col-3">
					<a class="btn btn-info" href="members.php">List of all members</a>
				</div>
			</div>
			<div class="row justify-content-center m-3">
				<div class="col-3">
					<a class="btn btn-info" href="late_loans.php">List of loans that are late</a>
				</div>
			</div>
			<div class="row justify-content-center m-3">
				<div class="col-3">
					<a class="btn btn-info" href="documents.php">List documents</a>
				</div>
			</div>
		</div>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
	</body>
</html>