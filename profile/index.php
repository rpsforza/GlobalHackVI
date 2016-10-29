<!doctype html>
<?php

require '../coc-helper.php';

$me = false;

if (isset($_SESSION["user_id"])) {
	if (!$_GET) {
		$type = getUserType($_SESSION["user_id"]);
		$idd = $_SESSION["user_id"];
		header("Location: ../profile/?" . $type . "=" . $idd);
		$me = true;
	} else if (isset($_GET["client"])) {
		$me = ($_SESSION["user_id"] == $_GET["client"]);
	}
} else if (!isset($_GET["coc"])) {
	header('Location: ../login');
}

?>
<html lang="en">
<head>
	<?php require "../header.php"; ?>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
</head>
<body>
	<div class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
		<?php
		$PAGE_TITLE = "Profile";
		require "../nav.php";
		?>
		<main class="mdl-layout__content mdl-color--grey-100">
			<div class="mdl-grid">
				<?php
				function formatServices($servs)
				{
					global $servMap;
					$result = "";

					foreach ($servs as $servID) {
						$serv = $servMap[strval($servID)];
						$result .= formatService($serv[0], $serv[1]);
					}

					return $result;
				}

				function formatService($servName, $color)
				{
					return '<span class="mdl-chip mdl-chip--contact" style="display: -webkit-box;"><span class="mdl-chip__contact mdl-color--' . $color . ' mdl-color-text--white">' . substr($servName, 0, 1) . '</span><span class="mdl-chip__text">' . $servName . '</span></span>';
				}

				global $me;

				if (isset($_GET["coc"])) {
					$coc = getCOC(intval($_GET["coc"]));
					if ($coc) {
						$v = [
							"Name" => $coc['name'],
							"Location" => $coc['address'] . ', ' . $coc['city'] . ', ' . $coc['state'] . ' ' . $coc['zipcode'],
							"Phone" => $coc['phone'],
							"Services" => formatServices(explode(';', $coc['services'])), // TODO
							"Vacancy" => $coc['vacancy'],
							"Capacity" => $coc['capacity'],
							"Special Conditions" => false // TODO: Implement using db data
						];

						echo "<div></div><table class=\"mdl-data-table mdl-js-data-table mdl-shadow--2dp\"><tbody>";
						foreach ($v as $col => $value) {
							echo "<tr><td class=\"mdl-data-table__cell--non-numeric\"><b>" . $col . "</b></td><td>" . $value . "</td></tr>";
						}
						echo "</tbody></table></div>";

						echo '<div style="margin-left: 0.66%;">';
						// Reserve if you're an auth-ed client
						if (isset($_SESSION["user_id"]) and getUserType($_SESSION["user_id"]) == "client") {
							$isReserved = isReserved($_SESSION["user_id"], 0, $_GET["coc"]);

							echo '<button id="reserve-button" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--colored" onclick="reserve(\'coc\')"> Reserve </button>';
							echo '<button id="unreserve-button" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--colored" onclick="cancel_reservation(\'coc\')"> Cancel Reservation </button>';

							echo '<script>$(' . ($isReserved ? "\"#reserve-button\"" : "\"#unreserve-button\"") . ').hide();</script>';
						}
						echo '</div>';
					}
				} else if (isset($_GET["client"]) and ($me or (isset($_SESSION["user_id"]) and getUserType($_SESSION["user_id"]) == "coc" or getUserType($_SESSION["user_id"]) == "host"))) {
					$client = getClient(intval($_GET["client"]));

					function getAge($dob)
					{
						if ($dob != null && $dob != 'NULL') {
							$dobTime = date_create($dob);
							return ((new DateTime())->diff($dobTime)->y) . ' years';
						}

						return 'Unknown';
					}

					$v = [
						"Name" => $client['First_Name'] . ' ' . $client['Middle_Name'] . ' ' . $client['Last_Name'],
						"Age" => getAge($client['DOB']),
						"Gender" => $client['Gender'] ? 'Female' : 'Male',
						"Race" => 'Prefer not to answer', // TODO: Implement
						"Veteran" => ($client['VeteranStatus'] != 0) ? 'Yes' : 'No',
						"Situation" => 'Waiting user input...' // TODO: Implement
					];
					echo "<table class=\"mdl-data-table mdl-js-data-table mdl-shadow--2dp\"><tbody>";
					foreach ($v as $key => $value) {
						echo "<tr><td class=\"mdl-data-table__cell--non-numeric\"><b>" . $key . "</b></td><td>" . $value . "</td></tr>";
					}
					echo "</tbody></table>";

					// TODO: Refer if you are a host/coc
				} else if (isset($_GET["host"])) {
					$host = getHost(intval($_GET["host"]));
					$v = [
						"Name" => $host['name'],
						"Phone" => $host['phone'],
						"Latitude" => $host['latitude'],
						"Longitude" => $host['longitude'],
						"Vacancy" => $host['vacancy'],
						"Capacity" => $host['capacity']
					];
					echo "<table class=\"mdl-data-table mdl-js-data-table mdl-shadow--2dp\"><tbody>";
					foreach ($v as $key => $value) {
						echo "<tr><td class=\"mdl-data-table__cell--non-numeric\"><b>" . $key . "</b></td><td>" . $value . "</td></tr>";
					}
					echo "</tbody></table>";
				} else {

				}
				?>

				<div id="snackbar" class="mdl-js-snackbar mdl-snackbar">
					<div class="mdl-snackbar__text"></div>
					<button class="mdl-snackbar__action" type="button"></button>
				</div>

			</div>
		</main>
	</div>
	<script src="https://code.getmdl.io/1.1.3/material.min.js"></script>

	<script>
		function reserve(type) {
			var userID = <? echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '-1'; ?>;
			var cocID = <? echo isset($_GET["coc"]) ? $_GET["coc"] : '-1'; ?>;

			$.ajax({
				type: "POST",
				url: "../reserve.php",
				data: {
					userID: userID,
					cocID: cocID,
					type: type,
					cancel: false
				},
				success: function (result) {
					var snackbarContainer = document.querySelector('#snackbar');
					snackbarContainer.MaterialSnackbar.showSnackbar({message: 'Reserved!'});

					$("#reserve-button").hide("fast", function () {
						$("#unreserve-button").show("fast");
					});
				}
			});
		}

		function cancel_reservation(type) {
			var userID = <? echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '-1'; ?>;
			var cocID = <? echo isset($_GET["coc"]) ? $_GET["coc"] : '-1'; ?>;

			$.ajax({
				type: "POST",
				url: "../reserve.php",
				data: {
					userID: userID,
					cocID: cocID,
					type: type,
					cancel: true
				},
				success: function (result) {
					var snackbarContainer = document.querySelector('#snackbar');
					snackbarContainer.MaterialSnackbar.showSnackbar({message: 'Canceled Reservation.'});

					$("#unreserve-button").hide("fast", function () {
						$("#reserve-button").show("fast");
					});
				}
			});
		}
	</script>
</body>
</html>
