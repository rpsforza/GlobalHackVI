<!doctype html>
<?php
require '../db_manager.php';

if (isset($_SESSION["user_id"])) {

} else {
	header('Location: ../login');
}

?>
<html lang="en">
<head>
	<?php require "../header.php"; ?>
</head>
<body>
	<div class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
		<?php
		$PAGE_TITLE = "Housing";
		require "../nav.php";
		?>
		<main class="mdl-layout__content mdl-color--grey-100">
			<div class="mdl-grid">
				<!-- CONTENT HERE -->
			</div>
		</main>
	</div>

	<script src="https://code.getmdl.io/1.1.3/material.min.js"></script>
</body>
</html>
