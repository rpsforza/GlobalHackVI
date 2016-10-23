<!doctype html>
<?php

require '../coc-helper.php';

if (isset($_SESSION["user_id"])) {

} else {
	header('Location: ../login');
}

?>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="description" content="A front-end template that helps you build fast, modern mobile web apps.">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
	<title>Clementine</title>
	<link rel="apple-touch-icon" sizes="57x57" href="../favicon/apple-touch-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="../favicon/apple-touch-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="../favicon/apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="../favicon/apple-touch-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="../favicon/apple-touch-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="../favicon/apple-touch-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="../favicon/apple-touch-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="../favicon/apple-touch-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="../favicon/apple-touch-icon-180x180.png">
	<link rel="icon" type="image/png" href="../favicon/favicon-32x32.png" sizes="32x32">
	<link rel="icon" type="image/png" href="../favicon/favicon-194x194.png" sizes="194x194">
	<link rel="icon" type="image/png" href="../favicon/favicon-96x96.png" sizes="96x96">
	<link rel="icon" type="image/png" href="../favicon/android-chrome-192x192.png" sizes="192x192">
	<link rel="icon" type="image/png" href="../favicon/favicon-16x16.png" sizes="16x16">
	<link rel="manifest" href="../favicon/manifest.json">
	<link rel="mask-icon" href="../favicon/safari-pinned-tab.svg" color="#5bbad5">
	<link rel="shortcut icon" href="../favicon/favicon.ico">
	<meta name="msapplication-TileColor" content="#da532c">
	<meta name="msapplication-TileImage" content="../favicon/mstile-144x144.png">
	<meta name="msapplication-config" content="../favicon/browserconfig.xml">
	<meta name="theme-color" content="#ffffff">
	<meta name="mobile-web-app-capable" content="yes">
	<link rel="icon" sizes="192x192" href="/favicon/android-chrome-192x192.png">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="apple-mobile-web-app-title" content="Material Design Lite">
	<link rel="apple-touch-icon-precomposed" href="/favicon/apple-touch-icon-precomposed.png">
	<meta name="msapplication-TileImage" content="images/touch/ms-touch-icon-144x144-precomposed.png">
	<meta name="msapplication-TileColor" content="#3372DF">
	<link rel="shortcut icon" href="/favicon/favicon.ico">

	<link rel="stylesheet"
		  href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<link rel="stylesheet" href="../css/colors.css">
	<link rel="stylesheet" href="../css/styles.css">

</head>
<body>
	<div class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
		<header class="demo-header mdl-layout__header mdl-color--grey-100 mdl-color-text--grey-600">
			<div class="mdl-layout__header-row">
				<span class="mdl-layout-title">Profile</span>
				<div class="mdl-layout-spacer"></div>

				<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" id="hdrbtn">
					<i class="material-icons">more_vert</i>
				</button>
				<ul class="mdl-menu mdl-js-menu mdl-js-ripple-effect mdl-menu--bottom-right" for="hdrbtn">
					<li class="mdl-menu__item">About</li>
					<li class="mdl-menu__item">Contact</li>
					<li class="mdl-menu__item">Legal information</li>
				</ul>
			</div>
		</header>
		<div class="demo-drawer mdl-layout__drawer mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
			<header class="demo-drawer-header">
				<img id="logoname" src="../img/name2.png"/>
				<div class="demo-avatar-dropdown">
					<span><?php if (isset($_SESSION["user_id"])) {
							$name = getUsersName($_SESSION["user_id"]);
						} else {
							$name = "<a href=\"../login/\">Login</a>";
						}
						echo $name; ?></span>
					<div class="mdl-layout-spacer"></div>
					<?php if (isset($_SESSION["user_id"])) {
						echo "<button id=\"accbtn\" class=\"mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon\"><i class=\"material-icons\" role=\"presentation\">arrow_drop_down</i><span class=\"visuallyhidden\">Logout</span></button><ul class=\"mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect\" for=\"accbtn\"><li class=\"mdl-menu__item\"><a id=\"logoutbuttonnav\" href=\"../logout/\">Logout</a></li></ul>";
					}

					?>
				</div>
			</header>
			<?php
			if (isset($_SESSION["user_id"])) {
				$userType = getUserType($_SESSION["user_id"]);
			} else {
				$userType = "clientNoAuth";
			}
			switch ($userType) { // [alt text, mdl font icon, current page]
				case "clientNoAuth":
					$a = [["services", "domain", false], ["housing", "home", false]];
					break;
				case "client":
					$a = [["dash", "dashboard", false], ["profile", "account_box", true], ["services", "domain", false], ["housing", "home", false]];
					break;
				case "coc":
					$a = [["dash", "dashboard", false], ["profile", "account_box", true], ["services", "domain", false], ["housing", "home", false], ["availability", "people", false], ["statistics", "timeline", false]];
					break;
				case "host":
					$a = [["dash", "dashboard", false], ["profile", "account_box", true], ["services", "domain", false], ["availability", "people", false], ["statistics", "timeline", false]];
					break;
				default:
					$a = [["dash", "dashboard", false], ["services", "domain", false], ["housing", "home", false]];
					break;
			}

			?>
			<nav class="demo-navigation mdl-navigation mdl-color--blue-grey-800">
				<?php
				foreach ($a as $arr) {
					$active = "";
					if ($arr[2]) $active = "active-nav ";
					echo "<a href=../" . $arr[0] . " class=\"" . $active . "mdl-navigation__link\"><i class=\"mdl-color-text--blue-grey-400 material-icons\" role=\"presentation\">" . $arr[1] . "</i>" . ucwords($arr[0]) . "</a>";
				}
				?>
				<div class="mdl-layout-spacer"></div>
				<a class="mdl-navigation__link" href="../faq/"><i class="mdl-color-text--blue-grey-400 material-icons"
																  role="presentation">help_outline</i><span>FAQ</span></a>
			</nav>
		</div>
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

						echo "<table class=\"mdl-data-table mdl-js-data-table mdl-shadow--2dp\"><tbody>";
						foreach ($v as $col => $value) {
							echo "<tr><td class=\"mdl-data-table__cell--non-numeric\"><b>" . $col . "</b></td><td>" . $value . "</td></tr>";
						}
						echo "</tbody></table>";

						// TODO: Reserve if you're an auth-ed client
					}
				} else if (isset($_GET["client"]) && (getUserType($_SESSION["user_id"]) == "coc" or getUserType($_SESSION["user_id"]) == "host")) {
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
			</div>
		</main>
	</div>
	<script src="https://code.getmdl.io/1.1.3/material.min.js"></script>
</body>
</html>
