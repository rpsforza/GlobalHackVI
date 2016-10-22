<html>
<head>
	<?php
	require '../db_manager.php';

	if (isset($_SESSION["user_id"])) {
		header('Location: ../');
	}

	if (isset($_GET["error"])) {
		if ($_GET["error"] == 1) {

		} else if ($_GET["error"] == 2) {

		} else {

		}
		?>
		<script type="text/javascript">
			// ERROR
		</script>

		<?php
	} else {
		if (isset($_POST["Username"]) && isset($_POST["Password"])) {
			$user_id = validateUser($_POST["Username"], $_POST["Password"]);
			if ($user_id) {
				$_SESSION["user_id"] = $user_id;
				header('Location: ../');
			} else {
				header('Location: ./?error=1');
				exit();
			}
			exit();
		}
	}

	?>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
		  integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css"
		  integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"
			integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS"
			crossorigin="anonymous"></script>
</head>
<body>
	<form method="post" action="./">
		<input type="text" name="Username" value="" placeholder="Email">
		<input type="password" name="Password" value="" placeholder="Password">
		<input type="submit" name="commit" value="Login">
	</form>
</body>
</html>
