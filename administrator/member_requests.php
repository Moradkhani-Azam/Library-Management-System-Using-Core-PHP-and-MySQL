<?php
    require "../db_connect.php";
    require "../message_display.php";

	session_start();

	if(empty($_SESSION['role']) || $_SESSION['role'] != "administrator" || empty($_GET['member_id'])) {
		header("Location: ../index.php");
	}

	$member_id = $_GET['member_id'];

	$query = "SELECT full_name FROM users WHERE id ='".$member_id."'";
	$result = mysqli_query($connection, $query);

	if(!$result)
		die("ERROR: Couldn't fetch users");
				
	$row = mysqli_num_rows($result);

	if($row == 0) die("The member id:".$member_id." does not exist!");
	else $member_name = mysqli_fetch_assoc($result)['full_name'];
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
			<legend align="center" class="m-3">
				<h3><i><?php echo $member_name; ?></i> Active Requests</h3>
			</legend>
			<hr/>
			<div class="row justify-content-center">
				<div id="alert-message">
					<p class="mb-0" id="message"></p>
				</div>
			</div>

			<div class="my-4">
				<button type='button' id='newRequest' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#newRequestModal' data-title='<?php echo $member_name; ?>'>
					Create new request
				</button>
			</div>
    		<?php

				$query = "SELECT r.created_at, d.id, d.title FROM requests r JOIN documents d ON r.document_id = d.id WHERE r.user_id ='".$member_id."' AND r.status='active'";
				$result = mysqli_query($connection, $query);

				if(!$result)
					echo error("ERROR: Couldn't fetch documents");
				
				$rows = mysqli_num_rows($result);

				if($rows == 0)
					echo "<h4 class='text-muted' align='center'>No request available.</h4>";
				else {
					echo "<table class='table table-hover' width='100%'>
						<thead>
							<tr>
								<th>Document ID</th>
								<th>Document title</th>
								<th>Created at</th>
		                        <th></th>
							</tr>
						</thead>
						<tbody>";
						while($row = mysqli_fetch_assoc($result)) {
							echo "<tr>
			                    <td>".$row['id']."</td>
			                    <td>".$row['title']."</td>
			                    <td>".$row['created_at']."</td>
			                    <td><form method='POST' onSubmit=\"return confirm('Do you want to cancel?')\">
									<input id='document_id' type='hidden' name='document_id' value='".$row['id']."' />
									<button class='btn btn-warning' id='btnCancel' type='submit' name='cancel_request'>Cancel</button>
									</form>
								</td>
							</tr>";
						 }
					echo "</tbody>
							</table>";

				}
				echo '<div class="modal" id="newRequestModal">
						<div class="modal-dialog">
						    <div class="modal-content">
						    	<div class="modal-header">
						        	<h4 class="modal-title">Modal Heading</h4>
						    	</div>

							    <div class="modal-body">
									<form method="POST">
										<div class="mb-3">
											<label for="document_id">Document ID</label>
											<input type="number" class="form-control" id="document_id" name="document_id" placeholder="Enter document id" required>
										</div>
										<div class="">
											<button type="submit" class="btn btn-primary" name="add_request">Add</button>
										</div>
									</form>
							    </div>

						      	<div class="modal-footer">
						        	<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
						      	</div>

						    </div>
						</div>
					</div>';

				echo "<a class='btn btn-dark' href=\"./members.php?member_id=" .$member_id."\">GO BACK</a>";

				if(isset($_POST['cancel_request'])) {
					$query = "UPDATE requests SET status='canceled' WHERE document_id='" . $_POST['document_id'] ."' AND user_id = '" . $member_id . "'";

					$result = mysqli_query($connection, $query);

					if(!$result)
						echo error("ERROR: Couldn't fetch documents");
					else echo success("Request is canceled successfully");

					// echo "<meta http-equiv='refresh' content='0'>";
					$connection->close();
				}

				if(isset($_POST['add_request'])) {

					$sql = "SELECT id FROM documents WHERE id='" . $_POST['document_id'] ."'";
					$result0 = mysqli_query($connection, $sql);
					
					if(!$result0) {
						echo error("ERROR: Couldn't fetch documents");
						die();
					} 

					if(mysqli_num_rows($result0) == 0) {
						echo error("This document id is not exist!");
					} else {
						$query = "SELECT user_id FROM requests WHERE document_id='" . $_POST['document_id'] ."' AND status='active'";
						$result = mysqli_query($connection, $query);
						
						if(!$result){
							echo error("ERROR: Couldn't fetch requests");
							die();
						}
						
						if(mysqli_num_rows($result) != 0){
							echo error("This document is already requested!");
						} else {
							$query = "INSERT INTO requests (user_id, document_id, created_at, status) VALUES('" . $member_id . "', '". $_POST['document_id'] . "', NOW(), 'active')";

							if ($connection->query($query) === TRUE) {
								// header("Location: member_requests.php");
								echo success("New request record has been added successfully!");

							} else {
								echo error("ERROR: Couldn't add request!");
							}
						}
						$connection->close();
					}
				}
			?>
		</div>
		<script>
			var button = document.getElementById('newRequest');
			var modal = document.getElementById('newRequestModal');
			button.addEventListener('click', (event) => {
				var title = event.target.getAttribute("data-title");
				modal.querySelector(".modal-title").innerHTML ="Member name: " + title;
			});
		</script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>