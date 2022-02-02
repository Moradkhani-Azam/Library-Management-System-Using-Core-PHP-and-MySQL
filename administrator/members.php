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
			<div class="row justify-content-center">
				<div id="alert-message">
					<p class="mb-0" id="message"></p>
				</div>
			</div>
			<form method="GET">
				<div class="row m-5 justify-content-center">
					<div class="col-12 col-sm-6">
						<div class="input-group mb-3">
							<input type="text" class="form-control" id="keywords" name="keywords" placeholder="Name"
							value='<?php if(isset($_GET['keywords']))echo $_GET['keywords'];?>' aria-label="Name" aria-describedby="button-addon">
  							<button class="btn btn-outline-secondary" type="submit" id="button-addon">Search</button>
						</div>
					</div>
				</div>
			</form>
			<div class="table-responsive">
	    		<?php
		    		if(isset($_GET['keywords']))
		    			$keywords = $_GET['keywords'];
		    		else $keywords = '';

		    		$query = "SELECT u.id, u.full_name, u.address, u.phone, u.email FROM users u JOIN roles r ON u.role_id = r.id WHERE r.name='member' AND u.full_name like '%".$keywords."%' ORDER BY u.id";
					$result = mysqli_query($connection, $query);
					if(!$result)
						die("ERROR: Couldn't fetch member");
					
					$rows = mysqli_num_rows($result);

					if($rows == 0)
						echo "<h2 align='center'>No members available</h2>";
					else {
						echo "<table class='table table-hover' width='100%'>
							<thead>
								<tr>
									<th>ID</th>
									<th>Full name</th>
									<th>Address</th>
									<th>Phone</th>
									<th>Email</th>
			                        <td></td>
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
			                    <td><a class='btn btn-primary mb-2' href='member_requests.php?member_id=".$row['id']."'>Requsts</a>
			                    </br><a class='btn btn-primary' href='member_loans.php?member_id=".$row['id']."'>Loans</a><td>
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