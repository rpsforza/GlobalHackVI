<!doctype html>
<?php

require '../db_manager.php';
require '../search.php';

$x;
if (isset($_GET["query"])) {
	$x = search("client", $_GET["query"]);
}

$userType;
if (isset($_SESSION["user_id"])) {
	$userType = getUserType($_SESSION["user_id"]);
} else {
	$userType = "clientNoAuth";
	header('Location: ../services/');
}

?>
<html lang="en">
<head>
	<?php require "../header.php"; ?>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

	<style>
		#tabel {
			width: 90%;
			margin-left: 5%;
		}

		.mdl-grid {
			margin: auto .66%;
		}
	</style>
</head>
<body>
	<div class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
		<?php
		$PAGE_TITLE = "Dashboard";
		require "../nav.php";
		?>
		<main class="mdl-layout__content mdl-color--grey-100">
			<div class="mdl-grid">

				<?php
				if (isset($_SESSION["user_id"])) {
					if (getUserType($_SESSION["user_id"]) == "host" or getUserType($_SESSION["user_id"]) == "coc") {
						?>
						<div class="mdl-cell mdl-cell--12-col mdh-expandable-search">
							<i class="material-icons">search</i>
							<form action="./" method="GET">
								<input type="text" placeholder="Search" value="" name="query" size="1">
							</form>
						</div>

						<?php

						if (isset($_GET) && isset($x)) {
							if (count($x) > 0) {
								echo "<div class=\"mdl-cell mdl-cell--12-col\">";
								echo "<table style='width: 100%; margin-right: auto; margin-left: auto; margin-top: 25px' id=\"tabel\" class=\"mdl-data-table mdl-js-data-table mdl-data-table mdl-shadow--2dp\"><thead><tr><th class=\"mdl-data-table__cell--non-numeric\">First Name</th><th>Middle Name</th><th>Last Name</th><th>User Profile</th><th>Add User</th></tr></thead><tbody>";
								for ($ix = 0; $ix < count($x); $ix++) {

									$id = $x[$ix]["id"];
									$taken = "";
									$mysqli = getDB();
									if ($mysqli->query("SELECT * FROM provided_services WHERE client_id=$id")->fetch_assoc()) $taken = "style='color: rgb(255, 0, 0)'";

									echo "<tr $taken><td class=\"mdl-data-table__cell--non-numeric\">" . $x[$ix]["First_Name"] . "</td><td>" . $x[$ix]["Middle_Name"] . "</td><td>" . $x[$ix]["Last_Name"] . "</td><td><a href=" . ("../profile/?client=" . $x[$ix]["id"]) . "> <i class=\"mdl-color-text--blue-grey-400 material-icons\" role=\"presentation\">person</i></a></td><td><a href=" . ("add.php?client=" . $x[$ix]["id"]) . "> <i style=\"color:green\" class=\"mdl-color-text--blue-grey-400 material-icons\" role=\"presentation\">add</i></a></td></tr>";
								}
								echo "</tbody></table></div>";
							} else {
								echo "<h5 style=\"width: 100%; text-align: center; color: red;\"> No Results Found </h5>";
							}
						}
						?>

						<div class="mdl-cell mdl-cell--12-col demo-card mdl-card mdl-shadow--2dp">
							<div class="mdl-card__title">
								<h2 class="mdl-card__title-text">Occupancy</h2>
							</div>

							<div class="mdl-grid">
								<div class="mdl-cell mdl-cell--3-col mdl-color--white">
									<svg fill="#39B54A" viewBox="0 0 1 1"
										 class="demo-chart">
										<use xlink:href="#piechart" mask="url(#piemask)"></use>
										<text x="0.5" y="0.5" font-family="Roboto" font-size="0.3" fill="#888"
											  text-anchor="middle" dy="0.1">
											82
											<tspan dy="-0.07" font-size="0.2">%</tspan>
										</text>
									</svg>
								</div>

								<div class="mdl-cell mdl-cell--9-col">
									<table id="tabel" class="mdl-data-table mdl-js-data-table mdl-shadow--2dp">
										<thead>
										<tr>
											<th>Shelter Members</th>
										</tr>
										<tr>
											<th class="mdl-data-table__cell--non-numeric">Name</th>
											<th>Profile</th>
											<th>Remove</th>
										</tr>
										</thead>

										<tbody>
										<?php
										$mysqli = getDB();

										$user_type = $_SESSION["user_type"];
										$user_type_id = $_SESSION["user_type_id"];

										$services = $mysqli->query("SELECT * FROM provided_services WHERE host_or_coc='$user_type' AND provider_id=$user_type_id")->fetch_all(MYSQLI_ASSOC);
										$clients = [];
										for ($i = 0; $i < sizeof($services); $i++) {
											$id = $services[$i]["client_id"];
											$clients[] = $mysqli->query("SELECT * FROM client WHERE id=$id")->fetch_assoc();
										}

										foreach ($clients as $person) {
											$name = $person["First_Name"] . " " . $person["Last_Name"];
											echo "<tr><td class=\"mdl-data-table__cell--non-numeric\">" . $name . "</td><td><a href=" . ("../profile/?client=" . $person["id"]) . "> <i class=\"mdl-color-text--blue-grey-400 material-icons\" role=\"presentation\">person</i></a></td><td><a href=" . ("remove.php?client=" . $person["id"]) . "> <i style=\"color:red\" class=\"mdl-color-text--blue-grey-400 material-icons\" role=\"presentation\">close</i></a></td></tr>";
										}
										?>
										</tbody>
									</table>
								</div>
							</div>
						</div>

						<div class="mdl-cell mdl-cell--12-col mdl-card mdl-shadow--2dp">
							<div class="mdl-card__title">
								<h2 class="mdl-card__title-text">Reservation Requests</h2>
							</div>

							<?php
							if (isset($x) && count($x) > 0) {
								?>
								<table id="tabel" class="mdl-data-table mdl-js-data-table mdl-data-table">
									<thead>
									<tr>
										<th class="mdl-data-table__cell--non-numeric">Name</th>
										<th>Visit Profile</th>
										<th>Accept</th>
										<th>Deny</th>
									</tr>
									</thead>
									<tbody>
									<?php
									for ($ix = 0; $ix < count($x); $ix++) {
										echo "<tr><td class=\"mdl-data-table__cell--non-numeric\">" . $x[$ix]["First_Name"] . " " . $x[$ix]["Last_Name"] . "</td><td><a href=" . ("../profile/?client=" . $x[$ix]["id"]) . "> <i class=\"mdl-color-text--blue-grey-400 material-icons\" role=\"presentation\">person</i></a></td><td><a href=" . ("./add.php?client=" . $x[$ix]["id"]) . "> <i style=\"color:red\" class=\"mdl-color-text--blue-grey-400 material-icons\" role=\"presentation\">check_circle</i></a></td><td><a href=" . ("./remove.php?client=" . $x[$ix]["id"]) . "> <i style=\"color:green\" class=\"mdl-color-text--blue-grey-400 material-icons\" role=\"presentation\">close</i></a></td></tr>";
									}
									?>
									</tbody>
								</table>
								<?php
							} else {
								?>
								<div class="mdl-card__supporting-text mdl-color-text--grey-600">
									No current reservation requests.
								</div>
								<?php
							}
							?>
						</div>
						<?php
					}
				}
				?>
			</div>
		</main>
	</div>

	<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1"
		 style="position: fixed; left: -1000px; height: 1000px;">
		<defs>
			<mask id="piemask" maskContentUnits="objectBoundingBox">
				<circle cx=0.5 cy=0.5 r=0.49 fill="white"></circle>
				<circle cx=0.5 cy=0.5 r=0.40 fill="black"></circle>
			</mask>
			<g id="piechart">
				<circle cx=0.5 cy=0.5 r=0.5></circle>
				<path d="M 0.5 0.5 0.5 0 A 0.5 0.5 0 0 1 0.95 0.28 z" stroke="none"
					  fill="rgba(255, 255, 255, 0.75)"></path>
			</g>
		</defs>
	</svg>

	<script src="https://code.getmdl.io/1.2.1/material.min.js"></script>
</body>
</html>
