<?php

require("db_manager.php");

function getAllShelters()
{
	$mysqli = getDB();
	$cocs = $mysqli->query("SELECT * FROM coc WHERE is_shelter=1")->fetch_all(MYSQLI_ASSOC);
	$hosts = $mysqli->query("SELECT * FROM host")->fetch_all(MYSQLI_ASSOC);
	return array_merge($cocs, $hosts);
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

		// doesn't add this shelter if the conditions aren't met
		if ($client_age < $minage || $client_age > $maxage) continue;
		if (($male && !$client_male) || ($female && !$client_female)) continue;
		if ($abuse && !$client_abuse) continue;
		if ($veteran && !$client_veteran) continue;

		$compatible_shelters[] = $shelter;
	}

	return $compatible_shelters;
}
