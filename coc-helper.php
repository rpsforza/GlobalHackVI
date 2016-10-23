<?php

require("db_manager.php");

$servMap = [
	"-1" => ["Other", "purple-A700"],
	"1" => ["Shelter", "indigo-A700"],
	"2" => ["Health", "green"],
	"3" => ["Legal", "lime-900"],
	"4" => ["Job", "blue-grey-800"],
	"5" => ["Food", "amber-900"],
	"6" => ["Hygiene", "pink-A400"],
	"7" => ["Transportation", "teal-A700"]
];

function getCOC($id)
{
	$mysqli = getDB();
	$statement = $mysqli->prepare("SELECT * FROM coc WHERE id=?");
	$statement->bind_param('i', $id);
	$statement->execute();
	$result = $statement->get_result();
	return $result ? $result->fetch_assoc() : false;
}

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

function newServiceReport($client_id, $service_id, $host_or_coc, $provider_id, $completed, $comments)
{
	$mysqli = getDB();

	$date = date("m/d/y");

	$statement = $mysqli->prepare("INSERT INTO provided_services (client_id, service_id, host_or_coc, provider_id, completed, comments, date) VALUES (?,?,?,?,?,?)");
	$statement->bind_param("iisiiss", $client_id, $service_id, $host_or_coc, $provider_id, $completed, $comments, $date);
	$statement->execute();
}

// report a client moving out of temporary shelter and into their own housing / a permanent housing service
// $rehousing_or_permanent_housing is either "rehousing" or "permanent_housing"
function clientMoveOut($client_id, $rehousing_or_permanent_housing, $new_address, $coc_or_host, $provider_id)
{
	$mysqli = getDB();

	$date = date("m/d/y");

	$statement = $mysqli->prepare("INSERT INTO output_records (date, client_id, rehousing_or_permanent_housing, home_address, coc_or_host, provider_id) 							VALUES (?,?,?,?,?,?)");
	$statement->bind_param("sisssi", $date, $client_id, $rehousing_or_permanent_housing, $new_address, $coc_or_host, $provider_id);
	$statement->execute();

	// increments the vacancies 
	$mysqli->query("UPDATE `$coc_or_host` SET vacancy = vacancy + 1 WHERE id=$provider_id");
	$vacancy = $mysqli->query("SELECT * FROM `$coc_or_host` WHERE id=$provider_id")->fetch_assoc()["vacancy"];

	// creates a new vacancy record
	$statement = $mysqli->prepare("INSERT INTO vacancy_records (date, vacancy, coc_or_host, provider_id) VALUES (?,?,?,?)");
	$statement->bind_param("sisi", $date, $vacancy, $coc_or_host, $provider_id);
	$statement->execute();

	// sets client "moved_on" to true
	$mysqli->query("UPDATE client SET moved_on=1 WHERE id=$client_id");
}

function clientMoveIn($client_id, $coc_or_host, $provider_id)
{
	$mysqli = getDB();

	$date = date("m/d/y");

	$statement = $mysqli->prepare("INSERT INTO intake_records (date, client_id, coc_or_host, provider_id) VALUES (?,?,?,?)");
	$statement->bind_param("sisi", $date, $client_id, $coc_or_host, $provider_id);
	$statement->execute();

	// decrements the vacancies 
	$mysqli->query("UPDATE `$coc_or_host` SET vacancy = vacancy - 1 WHERE id=$provider_id");
	$vacancy = $mysqli->query("SELECT * FROM `$coc_or_host` WHERE id=$provider_id")->fetch_assoc()["vacancy"];

	// creates a new vacancy record
	$statement = $mysqli->prepare("INSERT INTO vacancy_records (date, vacancy, coc_or_host, provider_id) VALUES (?,?,?,?)");
	$statement->bind_param("sisi", $date, $vacancy, $coc_or_host, $provider_id);
	$statement->execute();

	// sets client "moved_on" to false
	$mysqli->query("UPDATE client SET moved_on=0 WHERE id=$client_id");
}

function newReservation($client_id, $coc_or_host, $provider_id)
{
	$mysqli = getDB();
	$statement = $mysqli->prepare("SELECT * FROM reservation_records WHERE client_id=? AND coc_or_host=? AND provider_id=?");
	$statement->bind_param("isi", $client_id, $coc_or_host, $provider_id);
	$statement->execute();
	if ($statement->get_result()->num_rows > 0) {
		return;
	} else {
		$date = date("m/d/y");
		$statement = $mysqli->prepare("INSERT INTO reservation_records (client_id, coc_or_host, provider_id, date) VALUES (?,?,?,?)");
		$statement->bind_param("isis", $client_id, $coc_or_host, $provider_id, $date);
		$statement->execute();
	}
}

function completeReservation($client_id, $coc_or_host, $provider_id)
{
	$mysqli = getDB();
	$statement = $mysqli->prepare("UPDATE reservation_records SET showed_up=1 WHERE client_id=? AND coc_or_host=? AND provider_id=?");
	$statement->bind_param("isi", $client_id, $coc_or_host, $provider_id);
	$statement->execute();
}
