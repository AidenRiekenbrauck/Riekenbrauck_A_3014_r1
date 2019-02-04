<?php session_start(); ?>
<?php require_once('scripts/connection.php'); ?>
<?php 
	// checking if a user is logged in
	if (!isset($_SESSION['user_id'])) {
		header('Location: index.php');
	}

	$user_list = '';

	// getting the list of users
	$query = "SELECT * FROM user WHERE is_deleted=0 ORDER BY first_name";
	$users = mysqli_query($connection, $query);

	if ($users) {
		while ($user = mysqli_fetch_assoc($users)) {
			$user_list .= "<tr>";
			$user_list .= "<td>{$user['first_name']}</td>";
			$user_list .= "<td>{$user['last_name']}</td>";
			$user_list .= "<td>{$user['last_login']}</td>";
			$user_list .= "</tr>";
		}
	} else {
		echo "Database query failed.";
	}


	//time of day

$hour = date('H');
$dayTerm = ($hour > 17) ? "Evening " : ($hour > 12) ? "Afternoon " : "Morning ";
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Users</title>
	<link rel="stylesheet" href="../css/main.css">
</head>
<body>
	<header>
		<div class="appname">User Login System</div>
		<div class="loggedin">Welcome, <?php echo "Good ".$dayTerm , $_SESSION['first_name']; ?>! <a href="logout.php">Log Out</a></div>
	</header>

	<main>

		<table class="masterlist">
			<tr>
				<th>First Name</th>
				<th>Last Name</th>
				<th>Last Succesful Login</th>
			</tr>

			<?php echo $user_list; ?>

		</table>
		
		
	</main>
</body>
</html>