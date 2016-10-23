<html>
<head>
	<?php
	require '../db_manager.php';

	if (isset($_POST["username"])) {
		$username = $_POST["username"];
		$password = $_POST["password"];
		$first_name = $_POST["first_name"];
		$middle_name = $_POST["middle_name"];
		$last_name = $_POST["last_name"];
		$dob = $_POST["dob"];
		$gender = $_POST["gender"];

		// $date_created = current date;

		// TODO make UUID auto increment

		$mysqli = getDB();

		$statement = $mysqli->prepare("INSERT INTO client (First_Name, Middle_Name, Last_Name, DOB, Gender)
										VALUES (?, ?, ?, ?, ?)");
		$statement->bind_param("ssssi", $first_name, $middle_name, $last_name, $dob, $gender);
		$statement->execute();

		$usertype = "client";
		$table_id = $mysqli->insert_id;
		$statement = $mysqli->prepare("INSERT INTO login_accounts (username, password, user_type, table_id) VALUES (?,?,?,?)");
		$statement->bind_param("sssi", $username, $password, $usertype, $table_id);
		$statement->execute();
	}
	?>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
	<link rel="stylesheet" type="text/css" href="https://storage.googleapis.com/code.getmdl.io/1.0.1/material.blue_grey-orange.min.css">
	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
	<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="https://rawgit.com/FezVrasta/bootstrap-material-design/master/dist/js/material.min.js"></script>
	<script src="https://storage.googleapis.com/code.getmdl.io/1.0.1/material.min.js" type="text/javascript"></script>
	<script src="http://momentjs.com/downloads/moment-with-locales.min.js" type="text/javascript"></script>
	<style>
		.mdl-layout {
			align-items: center;
		  justify-content: center;
		}
		.mdl-layout__content {
			padding: 14px;
			flex: none;
		}

	</style>
</head>

<body>
	<div class="mdl-layout mdl-js-layout mdl-color--grey-100">
		<main class="mdl-layout__content">
			<div class="mdl-card mdl-shadow--6dp">
				<div class="mdl-card__title mdl-color--blue-grey-900 mdl-color-text--white">
					<img style="width: 100%;" id="logoname" src="../img/name2.png"/>
				</div>
		  	<div class="mdl-card__supporting-text">
					<form method="post">
						<div class="mdl-textfield mdl-js-textfield">
							<input class="mdl-textfield__input" name="username" value="" type="text" id="username" />
							<label class="mdl-textfield__label" for="username">Username</label>
						</div>
						<div class="mdl-textfield mdl-js-textfield">
							<input class="mdl-textfield__input" name="password" value="" type="password" id="userpass" />
							<label class="mdl-textfield__label" for="userpass">Password</label>
						</div>
						<div class="mdl-textfield mdl-js-textfield">
							<input class="mdl-textfield__input" name="first_name" value="" type="text" id="FirstName" />
							<label class="mdl-textfield__label" for="FirstName">First Name</label>
						</div>
						<div class="mdl-textfield mdl-js-textfield">
							<input class="mdl-textfield__input" name="middle_name" value="" type="text" id="MiddleName" />
							<label class="mdl-textfield__label" for="MiddleName">Middle Name</label>
						</div>
						<div class="mdl-textfield mdl-js-textfield">
							<input class="mdl-textfield__input" name="last_name" value="" type="text" id="LastName" />
							<label class="mdl-textfield__label" for="LastName">Last Name</label>
						</div>
						<div class="mdl-textfield mdl-js-textfield">
							<input class="mdl-textfield__input" name="dob" value="" type="text" id="DateOfBirth" />
							<label class="mdl-textfield__label" for="DateOfBirth">Date of Birth</label>
						</div>
						<div class="mdl-textfield mdl-js-textfield">
							<input class="mdl-textfield__input" name="gender" value="" type="text" id="DateOfBirth" />
							<label class="mdl-textfield__label" for="DateOfBirth">Gender</label>
						</div>


						<div style="border:none; " class="mdl-card__actions mdl-card--border">
							<input type="submit" style="float:right;" value="Sign Up" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect"/>
						</div>
					</form>
				</div>
			</div>
		</main>
	</div>
</body>
</html>
