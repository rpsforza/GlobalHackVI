<?php

require("../db_manager.php");

if (isset($_GET["client"])) {
	$id = $_GET["client"];

	$mysqli = getDB();
	$coc_or_host = $_SESSION["user_type"];
	$provider_id = $_SESSION["user_type_id"];

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

	// creates a new service record
	$statement = $mysqli->prepare("INSERT INTO provided_services (date, client_id, host_or_coc, provider_id) VALUES (?,?,?,?)");
	$statement->bind_param("sisi", $date, $id, $coc_or_host, $provider_id);
	$statement->execute();

	// sets client "moved_on" to false
	$mysqli->query("UPDATE client SET moved_on=0 WHERE id=$client_id");
}

header("Location: ./");
