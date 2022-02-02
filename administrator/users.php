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
			<div class="my-4"><a class="btn btn-info" href="new_user.php">Create new user</a></div>
			<div class="table-responsive">
	    		<?php
					$query = "SELECT u.id, u.full_name, u.address, u.phone, u.email, r.name as role FROM users u JOIN roles r ON u.role_id = r.id ORDER BY u.id";
					$result = mysqli_query($connection, $query);
					if(!$result)
						die("ERROR: Couldn't fetch users");
					
					$rows = mysqli_num_rows($result);

					if($rows == 0)
						echo "<h2 align='center'>No users available</h2>";
					else {
						echo "<table class='table table-hover' width='100%'>
								<thead>
								<tr>
									<th>ID</th>
									<th>Full name</th>
									<th>Address</th>
									<th>Phone</th>
									<th>Email</th>
			                        <th>Role</th>
								</tr>
							</thead>
							<tbody>";
							while($row = mysqli_fetch_assoc($result)) {
								echo "<tr>
								<td>".$row['id']."</td>
			                    <td>".$row['full_name']."</td>
			                    <td>".$row['address']."</td>
			                    <td>".$row['phone']."</td>
			                    <td>".$row['email']."</td>
			                    <td>".$row['role']."</td>
								</tr>";
							 }
						echo "</tbody>
								</table>";		
					}
					$connection->close();
				?>
			</div>
			<?php echo "<a class='btn btn-dark mb-4' href=\"./home.php\">GO BACK</a>"; ?>
		</div>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>