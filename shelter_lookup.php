<?php

require("db_manager.php");

if (isset($_POST)) {
	if (isset($_POST['latitude']) && isset($_POST['longitude'])) {
		$lat = $_POST['latitude'];
		$lon = $_POST['longitude'];
		echo json_encode(getCloseLocations($lat, $lon, 250));
	}
	return;
}

function getAllShelters()
{
	$mysqli = getDB();
	$cocs = $mysqli->query("SELECT * FROM coc WHERE is_shelter=1")->fetch_all(MYSQLI_ASSOC);
	$hosts = $mysqli->query("SELECT * FROM host")->fetch_all(MYSQLI_ASSOC);
	return array_merge($cocs, $hosts);
}

function getCloseLocations($lat, $lon, $maxDist)
{
	$data = getAllShelters();
	$locs = [];

	foreach ($data as $loc) {
		if (distance($lat, $lon, $loc["latitude"], $loc["longitude"]) < $maxDist) {
			array_push($locs, $loc);
		}
	}

	usort($locs, function ($loc1, $loc2) {
		global $lat, $lon;
		return distance($lat, $lon, $loc2["latitude"], $loc2["longitude"]) - distance($lat, $lon, $loc1["latitude"], $loc1["longitude"]);
	});

	return $locs;
}

function distance($lat1, $lon1, $lat2, $lon2)
{
	$theta = $lon1 - $lon2;
	$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
	$dist = acos($dist);
	$dist = rad2deg($dist);
	$miles = $dist * 60 * 1.1515;
	return $miles;
}

function getCompatibleShelters($client_id)
{
	$mysqli = getDB();
	$client = $mysqli->query("SELECT * FROM client WHERE id=$client_id")->fetch_assoc();

	$client_birthday = $client["dob"];
	// date in mm/dd/yyyy format; or it can be in other formats as well
	$birthDate = "12/17/1983";
	// explode the date to get month, day and year
	$birthDate = explode("/", $birthDate);
	// get age from date or birthdate
	$client_age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md")
		? ((date("Y") - $birthDate[2]) - 1) : (date("Y") - $birthDate[2]));

	$client_abuse = $client["abuse"];
	$client_male = $client["Gender"] === 1 ? 1 : 0;
	$client_female = $client["Gender"] === 0 ? 1 : 0;
	$client_veteran = $client["VeteranStatus"] === 1 ? 1 : 0;

	$shelters = getAllShelters();
	$compatible_shelters = [];

	for ($i = 0; $i < count($shelters); $i++) {
		$shelter = $shelters[$i];
		$minage = $shelter["condition_minage"];
		$maxage = $shelter["condition_maxage"];
		$male = $shelter["condition_male"];
		$female = $shelter["condition_female"];
		$abuse = $shelter["condition_abuse"];
		$veteran = $shelter["condition_veteran"];

		$vacancy = $shelter["vacancy"];

		// doesn't add this shelter if the conditions aren't met
		if ($client_age < $minage || $client_age > $maxage) continue;
		if (($male && !$client_male) || ($female && !$client_female)) continue;
		if ($abuse && !$client_abuse) continue;
		if ($veteran && !$client_veteran) continue;
		if ($vacancy === 0) continue;

		$compatible_shelters[] = $shelter;
	}

	return $compatible_shelters;
}
