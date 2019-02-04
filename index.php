<?php session_start(); ?>
<?php require_once('admin/scripts/connection.php'); ?>
<?php 
$atmp=0;


	// check for form submission
	if (isset($_POST['submit'])) {
		$atmp=$_POST['hidden'];
		$errors = array();
		
		if ($atmp<4){

		// check if the username and password has been entered
		if (!isset($_POST['email']) || strlen(trim($_POST['email'])) < 1 ) {
			$errors[] = 'Email is Missing / Invalid';
		}

		if (!isset($_POST['password']) || strlen(trim($_POST['password'])) < 1 ) {
			$errors[] = 'Password is Missing / Invalid';
		}

		// check if there are any errors in the form
		if (empty($errors)) {
			// save username and password into variables
			$email 		= mysqli_real_escape_string($connection, $_POST['email']);
			$password 	= mysqli_real_escape_string($connection, $_POST['password']);

			// prepare database query
			$query = "SELECT * FROM user 
						WHERE email = '{$email}' 
						AND password = '{$password}' 
						LIMIT 1";

			$result_set = mysqli_query($connection, $query);

			if ($result_set) {
				// query succesfful

				if (mysqli_num_rows($result_set) == 1) {
					// valid user found
					$user = mysqli_fetch_assoc($result_set);
					$_SESSION['user_id'] = $user['id'];
					$_SESSION['first_name'] = $user['first_name'];

					// updating last login

					$query = "UPDATE user SET last_login = NOW()";
					$query .="WHERE id = {$_SESSION['user_id']} LIMIT 1";

					$result_set = mysqli_query($connection, $query);

					if (!$result_set) {
						die("Database query fail.");
					}


					// redirect to users.php 
					header('Location: admin/users.php');
				} else {
					// user name and password invalid
					$errors[] = 'Invalid Email / Password';
					$atmp++;
				}
			} else {
				$errors[] = 'Database query failed';
				echo "<input type='hidden' name='hidden' value='".$atmp."'>";
			}

			if($atmp==4){
				echo '<p class="error">login limit exeeded</p>';

			}
		}
	}
}






?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Log In - User Management System</title>
	<link rel="stylesheet" href="css/main.css">
</head>
<body>
	<div class="login">

		<form action="index.php" method="post">
			<?php
			echo "<input type='hidden' name='hidden' value='".$atmp."'>";
			?>

			<fieldset>
				<legend><h1>Log In</h1></legend>

				<?php 
					if (isset($errors) && !empty($errors)) {
						echo '<p class="error">Invalid Email / Password. Attempt number is '.$atmp.'</p>';
					}
				?>

				<?php 
					if (isset($_GET['logout'])) {
						echo '<p class="info">You have successfully logged out from the system</p>';
					}
				?>

				<p>
					<label for="">Email:</label>
				<input type="text" name="email" id="" <?php if ($atmp==4){?> disabled="disabled"<?php }?> placeholder="Email Address">
				</p>

				<p>
					<label for="">Password:</label>
					<input type="password" name="password" id="" placeholder="Password">
				</p>

				<p>
					<button type="submit" name="submit">Log In</button>
				</p>

			</fieldset>

		</form>		

	</div> <!-- .login -->
</body>
</html>
<?php mysqli_close($connection); ?>