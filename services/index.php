<!doctype html>
<?php

require '../db_manager.php';

?>
<html lang="en">
<head>
	<?php require "../header.php"; ?>
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
		<?php
		$PAGE_TITLE = "Services";
		require "../nav.php";
		?>
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
		var lat, lon, locs, map, markers, info;

		function geolocate(highAccuracy) {
			highAccuracy = (typeof highAccuracy === 'boolean') && (highAccuracy);

			if (navigator.geolocation) {
				navigator.geolocation.getCurrentPosition(
					onGeoSuccess,
					highAccuracy
						? geolocate
						: onGeoFail,
					{
						enableHighAccuracy: highAccuracy,
						maximumAge: 600000,
						timeout: 10000
					}
				);
			} else {
				onGeoFail();
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
			lat = pos.coords.latitude;
			lon = pos.coords.longitude;

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

			info = new google.maps.InfoWindow();

			map.addListener('click', function () {
				info.close();
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
						info.setContent("Your location");
						info.open(map, markMe);
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

				var marker = new google.maps.Marker({
					position: new google.maps.LatLng(loc.latitude, loc.longitude),
					map: map,
					title: loc.name,
					icon: shelterSymbol
				});

				marker.addListener('click', function () {
					info.setContent('<a href=' + "../profile/?coc=" + loc.id + '>' + loc.name + '</a>');
					info.open(map, marker);
				});

				return marker;
			});

			document.getElementsByClassName("mdl-layout-title").innerHTML = "Services (" + markers.length + ")";
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

		geolocate(true);
	</script>
</body>
</html>
