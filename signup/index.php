<html>
<head>
	<?php
	require '../db_manager.php';

	if (isset($_POST["Username"]) && isset($_POST["Password"]) && isset($_POST["FirstName"]) && isset($_POST["MiddleName"]) && isset($_POST["LastName"]) && isset($_POST["DateOfBirth"]) && isset($_POST["Gender"])  ) {
			if (newClient($_POST["FirstName"], $_POST["MiddleName"], $_POST["LastName"], $_POST["DateOfBirth"], $_POST["Gender"])) {
				header('Location: ../login/');
			} else {
				header('Location: ./?error=1');
				exit();
			}
			exit();
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
					<form method="post" action="./">
						<div class="mdl-textfield mdl-js-textfield">
							<input class="mdl-textfield__input" name="Username" value="" type="text" id="username" />
							<label class="mdl-textfield__label" for="username">Username</label>
						</div>
						<div class="mdl-textfield mdl-js-textfield">
							<input class="mdl-textfield__input" name="Password" value="" type="password" id="userpass" />
							<label class="mdl-textfield__label" for="userpass">Password</label>
						</div>
						<div class="mdl-textfield mdl-js-textfield">
							<input class="mdl-textfield__input" name="FirstName" value="" type="text" id="FirstName" />
							<label class="mdl-textfield__label" for="FirstName">First Name</label>
						</div>
						<div class="mdl-textfield mdl-js-textfield">
							<input class="mdl-textfield__input" name="MiddleName" value="" type="text" id="MiddleName" />
							<label class="mdl-textfield__label" for="MiddleName">Middle Name</label>
						</div>
						<div class="mdl-textfield mdl-js-textfield">
							<input class="mdl-textfield__input" name="LastName" value="" type="text" id="LastName" />
							<label class="mdl-textfield__label" for="LastName">Last Name</label>
						</div>
						<div class="mdl-textfield mdl-js-textfield">
							<input class="mdl-textfield__input" name="DateOfBirth" value="" type="text" id="DateOfBirth" />
							<label class="mdl-textfield__label" for="DateOfBirth">Date of Birth</label>
						</div>
						<div class="mdl-textfield mdl-js-textfield">
							<input class="mdl-textfield__input" name="DateOfBirth" value="" type="text" id="DateOfBirth" />
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
