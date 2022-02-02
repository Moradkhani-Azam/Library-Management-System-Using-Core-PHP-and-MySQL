<?php
	require "../db_connect.php";
	require "../message_display.php";

	session_start();
	
	if(empty($_SESSION['role']) || $_SESSION['role'] != "administrator")
		header("Location: ../index.php")
?>

<html>
	<head>
		<meta charset="utf-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Port-Cartier municipal library website</title>
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
			<legend class="text-center m-3 fw-bold">Add New User</legend>

	    	<div class="row justify-content-center">
				<div id="alert-message">
					<p class="mb-0" id="message"></p>
				</div>
			</div>

			<div class="row justify-content-center">
				<div class="col-12 col-sm-6">
					<form method="POST">

						<div class="mb-3">
							<input type="text" class="form-control" id="full-name" name="full_name" placeholder="Full name" required>
						</div>

						<div class="mb-3">
							<label for="address" class="form-label">Address</label>
							<div class="row" id="address">
								<div class="col-6 mb-2">
									<input type="text" class="form-control" name="address[number]" placeholder="number" required>
								</div>
								<div class="col-6 mb-2">
									<input type="text" class="form-control" name="address[street]" placeholder="street" required>
								</div>
								<div class="col-6">
									<input type="text" class="form-control" name="address[city]" placeholder="city" required>
								</div>
								<div class="col-6">
									<input type="text" class="form-control" name="address[province]" placeholder="province" required>
								</div>
							</div>
						</div>

						<div class="mb-3">
		  					<input type="text" class="form-control" id="phone" name="phone" placeholder="(930) 483-6295" required>
						</div>
						<div class="mb-3">
		  					<input type="email" class="form-control" id="email" name="email" placeholder="example@email.com" required>
						</div>
						<div class="mb-3">
						  	<input type="password" class="form-control" id="password" name="password" placeholder="password" required>
						</div>

						<h4>Role</h4>
						<select class="form-select" aria-label="Default select example" name="role_id">
							<option value="1">Administrator</option>
							<option value="2">Employee</option>
							<option value="3">Memeber</option>
						</select>
						<br />
						<div class="row justify-content-between mb-5">
							<div class="col-3">
								<?php echo "<a class='btn btn-dark' href=\"./users.php\">GO BACK</a>"; ?>
							</div>
							<div class="col-3">
								<button type="submit" class="btn btn-primary" name="add_user">Add User</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</body>
	<?php
		if(isset($_POST['add_user'])) {
			$query = "SELECT id FROM users WHERE email='" . $_POST['email'] ."' OR phone='" . $_POST['phone'] . "'";
			$result = mysqli_query($connection, $query);
			
			if(!$result)
				echo error("ERROR: Couldn't fetch users");
			
			if(mysqli_num_rows($result) != 0)
				echo error("A user with that email or phone number already exists!");
			else {
				if(!isset($_POST['role_id']) || !in_array($_POST['role_id'], array(1, 2,3)))
					echo error("Role is not defined");
				else {
					$address = implode(' ', $_POST['address']);
					$query = "INSERT INTO users (full_name, address, email, phone, password, role_id) VALUES('" . $_POST['full_name'] . "', '". $address . "', '" . $_POST['email'] . "', '" . $_POST['phone'] . "', '" . password_hash($_POST['password'], PASSWORD_DEFAULT) . "', '" . $_POST['role_id']."')";

					if ($connection->query($query) === TRUE){
						echo success("New user record has been added successfully!");
					} else {
						echo error("ERROR: Couldn't add user!");
					}
				}
			}
			$connection->close();
		}
	?>
</html>