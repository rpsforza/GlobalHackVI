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
			$user_data = validateUser($_POST["Username"], $_POST["Password"]);
			if ($user_data) {
				// this id corresponds to the "login_accounts" table
				$_SESSION["user_id"] = $user_data["id"];
				// coc, client, or host
				$_SESSION["user_type"] = $user_data["user_type"];
				// this id corresponds to the table of the user type (coc, client, host)
				$_SESSION["user_type_id"] = $user_data["table_id"];
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
	<link rel="stylesheet" type="text/css" href="https://storage.googleapis.com/code.getmdl.io/1.0.1/material.blue_grey-orange.min.css">
	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
	<script src="https://storage.googleapis.com/code.getmdl.io/1.0.1/material.min.js" type="text/javascript"></script>
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
						<div style="border:none; " class="mdl-card__actions mdl-card--border">
							<button style="float:left;" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">Sign Up</button>
							<input type="submit" style="float:right;" value="Login" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect"/>
						</div>
					</form>
				</div>
			</div>
		</main>
	</div>
</body>
</html>
