<?php
    require "../db_connect.php";
	session_start();

	if(empty($_SESSION['role']) || $_SESSION['role'] != "member")
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
							<p class="px-2 fs-5">The Port-Cartier municipal library</p>
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
			<div class="row">
			</div>
			<div class="row">
			<div class="table-responsive">
	    		<?php
		    		if(isset($_GET['member_id']))
		    			$member_id = $_GET['member_id'];
		    		else
		    			$member_id = $_SESSION['user_id'];

					$query = "SELECT * FROM loans l JOIN documents d ON l.document_id = d.id WHERE l.user_id='".$member_id."' ORDER BY l.created_at DESC";
					$result = mysqli_query($connection, $query);
					if(!$result)
						die("ERROR: Couldn't fetch documents");
					
					$rows = mysqli_num_rows($result);

					if($rows == 0)
						echo "<h2 align='center'>No borrowed document available</h2>";
					else {
						echo "<h2 align='center' class='my-4'>List of requested document</h2>";
						echo "<table class='table table-hover' width='100%'>
									<thead>
										<tr>
											<th>Document title</th>
											<th>Created at</th>
					                        <th>Status</th>
										</tr>
									</thead>
									<tbody>";
								while($row = mysqli_fetch_assoc($result)) {
									echo "<tr>
				                    <td>".$row['title']."</td>
				                    <td>".$row['created_at']."</td>
				                    <td>". $row['status'] ."</td>
									</tr>";
								}

						echo "</tbody></table>";
					}
					
					$connection->close();
				?>
			</div>
			</div>
			<?php echo "<a class='btn btn-dark' href=\"javascript:history.go(-1)\">GO BACK</a>"; ?>
		</div>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>

</html>