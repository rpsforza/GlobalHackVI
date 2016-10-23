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

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.3.0/Chart.bundle.min.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
</head>
<body>
<script>
		$(document).ready(function() {

			var options = [];
			options["intake"] = false;
			options["vacancy"] = false;
			options["output"] = false;

			options["all"] = false;
			options["completed"] = false;
			options["initiated"] = false;

			var service_type = "all";

			var sliderRange = $("#slider-range");
			var amt = $("#amount");

			var min_date = 10;
			var max_date = 0;

			getOptions = function() {
				var result = [];
				for (var key in options) {
    				if (options[key]) result.push(key);
				}
				return result;
			}

			generateGraph = function() {
				var option_params = getOptions();
				$.ajax({
					url: "../graph/graph-helper.php",
					success: function(result) {
		    			var ctx = document.getElementById("myChart");
						var scatterChart = new Chart(ctx, {
							type: 'line',
							data: JSON.parse(result),
							borderColor: "rgba(75,192,192,1)",
							options: {
								responsive: false
							}
						});
		        	},
		        	error: function(result) {
		        		console.log("ajax returned an error");
		        	},
		        	type: 'POST',
	        		data: {
	        			coc_or_host: "coc",
	        			provider_id: "1",
	        			min_date: min_date,
	        			borderColor: "rgba(75,192,192,1)",
	        			max_date: max_date,
	        			increments: "10",
	        			options: option_params,
	        			service_type: service_type
	        		}
				});
			}

			sliderRange.slider({
				range: true,
				min: 0,
				max: 10,
				values: [0, 10],
				slide: function (event, ui) {
					amt.val("$" + ui.values[0] + " - $" + ui.values[1]);
				},
				stop: function(event, ui) {
					min_date = ui.values[1];
					max_date = ui.values[0];
					generateGraph();
				}
			});

			amt.val("$" + sliderRange.slider("values", 0) + " - $" + sliderRange.slider("values", 1));

			// FORM

			$('#intake').change(function() {
			    options["intake"] = this.checked;
			    generateGraph();
			});

			$('#vacancy').change(function() {
			    options["vacancy"] = this.checked;
			    generateGraph();
			});

			$('#output').change(function() {
			    options["output"] = this.checked;
			    generateGraph();
			});

			$('#all').change(function() {
			    options["all"] = this.checked;
			    generateGraph();
			});

			$('#completed').change(function() {
			    options["completed"] = this.checked;
			    generateGraph();
			});

			$('#initiated').change(function() {
			    options["initiated"] = this.checked;
			    generateGraph();
			});

			$("#services").change(function () {
				service_type = $("#services option:selected").val();
			    generateGraph();
			});

			options["output"] = true;
			generateGraph();
			options["output"] = false;
		});
	</script>
	<div class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
		<header class="demo-header mdl-layout__header mdl-color--grey-100 mdl-color-text--grey-600">
			<div class="mdl-layout__header-row">
				<span class="mdl-layout-title">Statistics</span>
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
					$a = [["dash", "dashboard", false], ["map", "map", false], ["shelter", "hotel", false], ["services", "domain", false], ["housing", "home", false]];
					break;
				case "client":
					$a = [["dash", "dashboard", false], ["profile", "account_box", false], ["map", "map", false], ["shelter", "hotel", false], ["services", "domain", false], ["housing", "home", false]];
					break;
				case "coc":
					$a = [["dash", "dashboard", false], ["profile", "account_box", false], ["map", "map", false], ["shelter", "hotel", false], ["services", "domain", false], ["housing", "home", false], ["availability", "people", false], ["statistics", "timeline", true]];
					break;
				case "host":
					$a = [["dash", "dashboard", false], ["profile", "account_box", false], ["map", "map", false], ["shelter", "hotel", false], ["availability", "people", false]];
					break;
				default:
					$a = [["dash", "dashboard", false], ["map", "map", false], ["shelter", "hotel", false], ["services", "domain", false], ["housing", "home", false]];
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
				<canvas id="myChart" style="width:100%; height: auto;"></canvas>

	<div style="width: 40%; margin-top: 30px; margin-left: 30px">
		<p>
			<label for="amount">Day range:</label>
			<input type="text" id="amount" readonly style="border:0; color:#f6931f; font-weight:bold;">
		</p>
		<div id="slider-range"></div>
		</div>

		<!-- <input type="checkbox" id="intake"> <label>Homeless Taken In</label>
		<input type="checkbox" id="vacancy"> <label>Vacancies</label>
		<input type="checkbox" id="output"> <label>Homeless Moved Out</label>

		<input type="checkbox" id="initiated"> <label>Services Initiated</label>
		<input type="checkbox" id="completed"> <label>Services Completed</label>
		<input type="checkbox" id="all"> <label>Services Completed or Initiated</label> -->


		<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="intake">
		  <input type="checkbox" id="intake" class="mdl-checkbox__input" checked>
		  <span class="mdl-checkbox__label">Homeless Taken In</span>
		</label>

		<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="vacancy">
		  <input type="checkbox" id="vacancy" class="mdl-checkbox__input" checked>
		  <span class="mdl-checkbox__label">Vacancies</span>
		</label>

		<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="output">
		  <input type="checkbox" id="output" class="mdl-checkbox__input" checked>
		  <span class="mdl-checkbox__label">Homeless Moved Out</span>
		</label>

		<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="initiated">
		  <input type="checkbox" id="initiated" class="mdl-checkbox__input" checked>
		  <span class="mdl-checkbox__label">Services Started, Not Completed</span>
		</label>

		<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="completed">
		  <input type="checkbox" id="completed" class="mdl-checkbox__input" checked>
		  <span class="mdl-checkbox__label">Services Completed</span>
		</label>

		<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="all">
		  <input type="checkbox" id="all" class="mdl-checkbox__input" checked>
		  <span class="mdl-checkbox__label">All Services</span>
		</label>

		<select id="services">
			<option value="all">all services</option>
			<?php
				$mysqli = getDB();

				$services = $mysqli->query("SELECT * FROM services")->fetch_all(MYSQLI_ASSOC);
				for ($i = 0; $i < count($services); $i++) {
					$service = $services[$i]["name"];
					$id = $services[$i]["id"];
					echo "<option value='$id'>$service</option>";
				}
			?>
		</select>
			</div>
		</main>
	</div>
	<script src="https://code.getmdl.io/1.1.3/material.min.js"></script>
</body>
</html>
