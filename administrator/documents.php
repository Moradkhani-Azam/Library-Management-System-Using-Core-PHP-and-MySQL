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
			<form method="GET">
				<div class="row m-5 justify-content-center">
					<div class="col-12 col-sm-6">
						<div class="input-group mb-3">
							<input type="text" class="form-control" name="keywords" placeholder="Search in title, author or description"
							value='<?php if(isset($_GET['keywords'])) echo $_GET['keywords'];?>' aria-label="Title" aria-describedby="button-addon">
  							<button class="btn btn-outline-secondary" type="submit" id="button-addon">Search</button>
						</div>
					</div>
					<div class="col-12 col-sm-6">
						<div class="input-group mb-3">
  							<select class="form-select" id="filter" name="filter">
						    	<option selected></option>
						    	<option value="requested" <?php if(isset($_GET['filter']) && $_GET['filter'] == 'requested') echo 'selected';?>>Requested</option>
						    	<option value="borrowed" <?php if(isset($_GET['filter']) && $_GET['filter'] == 'borrowed') echo 'selected';?>>Borrowed</option>
						  	</select>
						  	<button class="btn btn-outline-secondary" type="submit">Filter</button>	
						</div>
					</div>
				</div>
			</form>
			<div class="table-responsive">
	    		<?php
		    		if(isset($_GET['keywords']))
		    			$keywords = $_GET['keywords'];
		    		else $keywords = '';

					$query = "SELECT * FROM documents d LEFT JOIN requests r ON d.id=r.document_id LEFT JOIN loans l ON d.id=l.document_id WHERE (d.title like '%".$keywords."%' OR d.author like '%".$keywords."%' OR d.description like '%".$keywords."%')";

					if(isset($_GET['filter']))
						if($_GET['filter'] == 'requested')
							$query .= " AND r.status = 'active'";
						else if($_GET['filter'] == 'borrowed')
							$query .= " AND l.status = 'active'";

					$query .= " GROUP BY d.id";

					$result = mysqli_query($connection, $query);
					if(!$result)
						die("ERROR: Couldn't fetch documents");
					
					$rows = mysqli_num_rows($result);

					if($rows == 0)
						echo "<h2 align='center'>No document available</h2>";
					else {
						echo "<table class='table table-hover' width='100%'>
								<thead>
									<tr>
										<th>ID</th>
										<th>Title</th>
										<th>Author</th>
										<th>Published Year</th>
										<th>Category</th>
				                        <th>Type</th>
				                        <th>Genre</th>
				                        <th>ISBN</th>
				                        <th>Description</th>
				                        <th>Status</th>
									</tr>
								</thead>
							<tbody>";
							while($row = mysqli_fetch_assoc($result)) {

								$query2 = "SELECT user_id FROM requests WHERE document_id='" . $row['id'] . "' AND status='active'";
								$result2 = mysqli_query($connection, $query2);
								if(!$result2)
									die("ERROR: Couldn't fetch documents");
								
								$rows2 = mysqli_num_rows($result2);

								if($rows2 == 0) $action = '';
								else $action = '<div class="btn btn-secondary btn-sm">Requested</div>';

								$query2 = "SELECT user_id FROM loans WHERE document_id='" . $row['id'] . "' AND status='active'";
								$result2 = mysqli_query($connection, $query2);
								if(!$result2)
									die("ERROR: Couldn't fetch documents");
								
								$rows2 = mysqli_num_rows($result2);
								
								if($rows2 == 0) $action .= '';
								else $action .= '<div class="btn btn-secondary btn-sm">Borrowed</div>';

								if($row['isbn']) $isbn = $row['isbn'];
								else $isbn = '-';

								$viewDescription ="<button type='button' id='description' class='btn btn-primary btn-sm' data-bs-toggle='modal' data-bs-target='#descriptionModal' data-content='".$row['description']."' data-title='".$row['title']."'>
								  view
								</button>";

								echo "<tr>
								<td>".$row['id']."</td>
			                    <td>".$row['title']."</td>
			                    <td>".$row['author']."</td>
			                    <td>".$row['published_year']."</td>
			                    <td>".$row['category']."</td>
			                    <td>".$row['type']."</td>
			                    <td>".$row['genre']."</td>
			                    <td>".$isbn."</td>
			                    <td>" . $viewDescription . "</td>
			                    <td>" . $action . "</td>
								</tr>";
							}
						echo "</tbody></table>";

						echo '<div class="modal" id="descriptionModal">
							  <div class="modal-dialog">
							    <div class="modal-content">
							      <div class="modal-header">
							        <h4 class="modal-title">Modal Heading</h4>
							      </div>

							      <div class="modal-body">
							        Modal body..
							      </div>

							      <div class="modal-footer">
							        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
							      </div>

							    </div>
							  </div>
							</div>';
					}

					if(isset($_POST['document_request']))
					{
						$sql = "SELECT user_id FROM requests WHERE document_id='" . $_POST['document_id']. "' AND status='active'";
						$result2 = mysqli_query($connection, $sql);
						if(!$result2)
							die("ERROR: Couldn't fetch documents");
						
						$rows2 = mysqli_num_rows($result2);
						if($rows2 == 0) {
							$query = "INSERT INTO requests (user_id, document_id, created_at, status) VALUES ('" . $_SESSION['user_id'] . "', '". $_POST['document_id'] . "', NOW(), 'active')";

							$result = mysqli_query($connection, $query);

							if(!$result)
								echo error("ERROR: Couldn't fetch documents!");
							else echo success("Document is requested successfully!");
						} else echo error("Document is Unavailable!");
					}
					
					$connection->close();
				?>
			</div>
			<?php echo "<a class='btn btn-dark mb-4' href=\"./home.php\">GO BACK</a>"; ?>
		</div>
		<script>
			var buttons = document.querySelectorAll('[id=description]');
			var modal = document.getElementById('descriptionModal');

			for (var i = 0; i < buttons.length; i++) {
				buttons[i].addEventListener('click', (event) => {
				var description = event.target.getAttribute("data-content");
				var title = event.target.getAttribute("data-title");
				modal.querySelector(".modal-title").innerHTML = title;
				modal.querySelector(".modal-body").innerHTML = description;
				})
	        }
		</script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>