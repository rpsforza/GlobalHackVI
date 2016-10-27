<?php

require("db_manager.php");

if (isset($_POST)) {
	if (isset($_POST['latitude']) && isset($_POST['longitude'])) {
		$lat = $_POST['latitude'];
		$lon = $_POST['longitude'];

		$data = isset($_SESSION["user_id"])
			? getCompatibleCloseShelters($_SESSION["user_id"], $lat, $lon, 150)
			: getCloseLocations(getAllShelters(), $lat, $lon, 150);

		echo json_encode($data);
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

function getCloseLocations($data, $lat, $lon, $maxDist)
{
	function distance($lat1, $lon1, $lat2, $lon2)
	{
		$theta = $lon1 - $lon2;
		$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
		$dist = acos($dist);
		$dist = rad2deg($dist);
		$miles = $dist * 60 * 1.1515;
		return $miles;
	}

	$locs = array_filter($data, function ($loc) use ($lat, $lon, $maxDist) {
		return distance($lat, $lon, $loc["latitude"], $loc["longitude"]) < $maxDist;
	});

	usort($locs, function ($loc1, $loc2) use ($lat, $lon) {
		return distance($lat, $lon, $loc1["latitude"], $loc1["longitude"]) - distance($lat, $lon, $loc2["latitude"], $loc2["longitude"]);
	});

	return $locs;
}

function getCompatibleCloseShelters($client_id, $lat, $lon, $maxDist)
{
	if (is_string($client_id))
		$client_id = intval($client_id);

	$mysqli = getDB();
	$client = $mysqli->query("SELECT * FROM client WHERE id=$client_id")->fetch_assoc();

	return array_values(array_filter(getCloseLocations(getAllShelters(), $lat, $lon, $maxDist), function ($shelter) use ($client) {
		return isCompatible($client, $shelter);
	}));
}

function isCompatible($client, $shelter)
{
	/* Client info */
	$client_age = floor((time() - strtotime($client["DOB"])) / 31556926);
	$client_abuse = intval($client["abuse"]);
	$client_male = intval($client["Gender"]);
	$client_female = $client["Gender"] === '0' ? 1 : 0;
	$client_veteran = intval($client["VeteranStatus"]);

	/* Check shelter */
	$minage = intval($shelter["condition_minage"]);
	$maxage = intval($shelter["condition_maxage"]);
	$male = intval($shelter["condition_male"]);
	$female = intval($shelter["condition_female"]);
	$abuse = intval($shelter["condition_abuse"]);
	$veteran = intval($shelter["condition_veteran"]);

	return !((($client_age < $minage) || ($client_age > $maxage))
		|| ((!$male && $client_male) || (!$female && $client_female))
		|| (!$abuse && $client_abuse)
		|| (!$veteran && $client_veteran));
}
