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
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

	<style>
		#map {
			height: 66vh;
			width: 100%;
			margin-bottom: 5px;
		}

		#grid {
			min-height: 10px;
			min-width: 10px;
		}

		#tabel {
			width: 100%;
		}

		#tableWrap {
			width: 100%;
			height: auto;
			max-width: 100vw;
			overflow: scroll;
		}
		th {
			text-align: center !important;
		}
		.mdl-chip--contact {
			display: -webkit-box;
		}
	</style>
</head>
<body>
	<div class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
		<header class="demo-header mdl-layout__header mdl-color--grey-100 mdl-color-text--grey-600">
			<div class="mdl-layout__header-row">
				<span class="mdl-layout-title" id="layout-title">Map</span>
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
					$a = [["dash", "dashboard", false], ["map", "map", true], ["shelter", "hotel", false], ["services", "domain", false], ["housing", "home", false]];
					break;
				case "client":
					$a = [["dash", "dashboard", false], ["profile", "account_box", false], ["map", "map", true], ["shelter", "hotel", false], ["services", "domain", false], ["housing", "home", false]];
					break;
				case "coc":
					$a = [["dash", "dashboard", false], ["profile", "account_box", false], ["map", "map", true], ["shelter", "hotel", false], ["services", "domain", false], ["housing", "home", false], ["availability", "people", false], ["statistics", "timeline", false]];
					break;
				case "host":
					$a = [["dash", "dashboard", false], ["profile", "account_box", false], ["map", "map", true], ["shelter", "hotel", false], ["availability", "people", false]];
					break;
				default:
					$a = [["dash", "dashboard", false], ["map", "map", true], ["shelter", "hotel", false], ["services", "domain", false], ["housing", "home", false]];
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
			<div id="grid" class="mdl-grid">
				<div id="map"></div>
				<div id="tableWrap">
					<table id="tabel" class="mdl-data-table mdl-js-data-table mdl-data-table mdl-shadow--2dp">
						<thead>
						<tr>
							<th class="mdl-data-table__cell--non-numeric">Name</th>
							<th>Services</th>
							<th>Vacancy</th>
						</tr>
						</thead>
						<tbody id="table-body">
						</tbody>
					</table>
				</div>
			</div>
		</main>
	</div>
	<script src="https://code.getmdl.io/1.1.3/material.min.js"></script>
	<script>
		var lat, lon, locs, map, markers;

		function getGeolocation() {
			if (navigator.geolocation) {
				navigator.geolocation.getCurrentPosition(onGeoSuccess, onGeoFail);
			} else {
				return onGeoFail();
			}
		}

		function onGeoFail() {
			// Resort to location by IP
			$.getJSON('http://ipinfo.io', function (data) {
				var pos = data.loc.split(',').map(Number);
				onGeoSuccess({coords: {latitude: pos[0], longitude: pos[1]}});
			});
		}

		function onGeoSuccess(pos) {
			var override = true;
			lat = override ? 38.6227953 : pos.coords.latitude;
			lon = override ? -90.2530406 : pos.coords.longitude;

			$.ajax({
				type: "POST",
				url: "../shelter_lookup.php",
				data: {latitude: lat, longitude: lon},
				success: function (result) {
					locs = JSON.parse(result);

					// Start Google Maps
					var mapScript = document.createElement("script");
					mapScript.src = "https://maps.googleapis.com/maps/api/js?key=AIzaSyAsloKYX3PEw3qk0tmp8B5SVAEuBqg38zQ&callback=initMap";
					document.head.appendChild(mapScript);
				}
			});
		}

		function pinSymbol(color) {
			return {
				path: 'M 0,0 C -2,-20 -10,-22 -10,-30 A 10,10 0 1,1 10,-30 C 10,-22 2,-20 0,0 z M -2,-30 a 2,2 0 1,1 4,0 2,2 0 1,1 -4,0',
				fillColor: color,
				fillOpacity: 1,
				strokeColor: '#000',
				strokeWeight: 2,
				scale: 1
			};
		}

		function initMap() {
			map = new google.maps.Map(document.getElementById('map'), {
				center: {lat: lat, lng: lon},
				styles: [{
					"elementType": "labels",
					"stylers": [{"color": "#37474f"}, {"visibility": "on"}]
				}, {
					"elementType": "labels.icon",
					"stylers": [{"color": "#ac3737"}, {"visibility": "off"}]
				}, {
					"elementType": "labels.text",
					"stylers": [{"color": "#c61515"}, {"visibility": "on"}]
				}, {
					"elementType": "labels.text.fill",
					"stylers": [{"color": "#37474f"}, {"saturation": 36}, {"lightness": 40}]
				}, {
					"elementType": "labels.text.stroke",
					"stylers": [{"color": "#ffffff"}, {"lightness": 16}, {"visibility": "on"}]
				}, {
					"featureType": "administrative",
					"elementType": "geometry.fill",
					"stylers": [{"color": "#fefefe"}, {"lightness": 20}]
				}, {
					"featureType": "administrative",
					"elementType": "geometry.stroke",
					"stylers": [{"color": "#fefefe"}, {"lightness": 17}, {"weight": 1.2}]
				}, {
					"featureType": "landscape",
					"elementType": "geometry",
					"stylers": [{"color": "#f5f5f5"}, {"lightness": 20}]
				}, {
					"featureType": "poi",
					"elementType": "geometry",
					"stylers": [{"color": "#f5f5f5"}, {"lightness": 21}]
				}, {
					"featureType": "poi.park",
					"elementType": "geometry",
					"stylers": [{"color": "#dedede"}, {"lightness": 21}]
				}, {
					"featureType": "road.arterial",
					"elementType": "geometry",
					"stylers": [{"color": "#ffffff"}, {"lightness": 18}]
				}, {
					"featureType": "road.highway",
					"elementType": "geometry.fill",
					"stylers": [{"color": "#ffffff"}, {"lightness": 17}]
				}, {
					"featureType": "road.highway",
					"elementType": "geometry.stroke",
					"stylers": [{"color": "#ffffff"}, {"lightness": 29}, {"weight": 0.2}]
				}, {
					"featureType": "road.local",
					"elementType": "geometry",
					"stylers": [{"color": "#ffffff"}, {"lightness": 16}]
				}, {
					"featureType": "transit",
					"elementType": "geometry",
					"stylers": [{"color": "#f2f2f2"}, {"lightness": 19}]
				}, {
					"featureType": "water",
					"elementType": "geometry",
					"stylers": [{"color": "#e9e9e9"}, {"lightness": 17}]
				}, {
					"featureType": "water",
					"elementType": "geometry.fill",
					"stylers": [{"color": "#37474f"}, {"visibility": "on"}]
				}],
				zoom: 10,
				zoomControl: true,
				mapTypeControl: false,
				fullscreenControl: false,
				streetViewControl: false
			});

			var markMe = new google.maps.Marker({
				position: new google.maps.LatLng(lat, lon),
				map: null,
				animation: google.maps.Animation.DROP,
				title: "Your Location",
				draggable: true,
				icon: pinSymbol("#39B54A")
			});

			addMarkersFromLocs();

			setTimeout(function () {
				markMe.setMap(map);

				setTimeout(function () {
					markMe.setAnimation(google.maps.Animation.BOUNCE);
					markMe.addListener('click', function () {
						markMe.setAnimation(null);
					});

					markMe.addListener('dragend', function (event) {
						$.ajax({
							type: "POST",
							url: "../shelter_lookup.php",
							data: {latitude: event.latLng.lat(), longitude: event.latLng.lng()},
							success: function (result) {
								markers.forEach(function (marker) {
									marker.setMap(null);
								});
								markers = [];
								locs = JSON.parse(result);
								addMarkersFromLocs();
							}
						});
					});
				}, 500);
			}, 2750);
		}

		function addMarkersFromLocs() {
			var shelterSymbol = pinSymbol("#F79622");

			document.getElementById("table-body").innerHTML = "";

			markers = locs.map(function (loc) {
				addLocToTable(loc);

				return new google.maps.Marker({
					position: new google.maps.LatLng(loc.latitude, loc.longitude),
					map: map,
					title: loc.name,
					icon: shelterSymbol
				});
			});

			document.getElementById("layout-title").innerHTML = "Map (" + markers.length + ")";
		}

		var servMap = {
			"-1": {
				name: "Other",
				bg: "purple-A700"
			},
			"1": {
				name: "Shelter",
				bg: "indigo-A700"
			},
			"2": {
				name: "Health",
				bg: "green"
			},
			"3": {
				name: "Legal",
				bg: "lime-900"
			},
			"4": {
				name: "Job",
				bg: "blue-grey-800"
			},
			"5": {
				name: "Food",
				bg: "amber-900"
			},
			"6": {
				name: "Hygiene",
				bg: "pink-A400"
			},
			"7": {
				name: "Transportation",
				bg: "teal-A700"
			}
		};

		function addLocToTable(loc) {
			var tableBody = document.getElementById("table-body");

			var row = document.createElement("tr");

			var name = document.createElement("td");
			name.className += "mdl-data-table__cell--non-numeric";
			var link = document.createElement("a");
			link.setAttribute("href", ("../profile/?coc=" + loc.id));
			link.innerHTML = loc.name;
			name.appendChild(link);
			row.appendChild(name);

			var services = document.createElement("td");
			loc.services.split(";").forEach(function (servNum) {
				var service = servMap[servNum];

				if (service == null) {
					service = {
						name: "Other",
						bg: "purple-A700"
					};
				}

				var servName = service.name;

				var badge = document.createElement("span");
				badge.className += "mdl-chip mdl-chip--contact";

				var icon = document.createElement("span");
				icon.classList.add("mdl-chip__contact");
				icon.classList.add("mdl-color--" + service.bg);
				icon.classList.add("mdl-color-text--white");
				icon.innerHTML = servName.charAt(0);
				badge.appendChild(icon);

				var text = document.createElement("span");
				text.className += "mdl-chip__text";
				text.innerHTML += servName;
				badge.appendChild(text);

				services.appendChild(badge);
			});
			row.appendChild(services);

			var vacancy = document.createElement("td");
			vacancy.innerHTML = loc.vacancy;
			row.appendChild(vacancy);

			tableBody.appendChild(row);
		}

		getGeolocation();
	</script>
</body>
</html>
