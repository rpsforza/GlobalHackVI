<?php

require("db_manager.php");

// $identifier is either "id" or "name"
function getServicesListByIdentifier($identifier)
{
	$mysqli = getDB();
	$services_raw = $mysqli->query("SELECT * FROM services")->fetch_all(MYSQLI_ASSOC);
	return array_column($services_raw, $identifier);
}

function getServicesProvided($client_id)
{
	$mysqli = getDB();
	$service_ids = getServicesListByIdentifier("id");
	$services_provided = [];

	foreach ($service_ids as $service_id) {
		$service_record = $mysqli->query("SELECT * FROM provided_services WHERE client_id=$client_id AND service_id=$service_id");
		if ($service_record) {
			$services_provided[] = $service_record;
		}
	}

	return $services_provided;
}
