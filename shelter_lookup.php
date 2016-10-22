<?php
	
	require("db_manager.php");

	function getCompatibleShelters($client_id) {
		$mysqli = getDB();
		$client = $myqli->query("SELECT * FROM clients WHERE id=$client_id")->fetch_assoc();

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
	}

?>