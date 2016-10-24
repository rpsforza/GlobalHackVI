<?php

require("../db_manager.php");

if (isset($_GET["client"])) {
	$id = $_GET["client"];
	$coc_or_host = $_SESSION["user_type"];
	$provider_id = $_SESSION["user_type_id"];

	$mysqli = getDB();

	$date = date("m/d/y");

	$statement = $mysqli->prepare("INSERT INTO output_records (date, client_id, coc_or_host, provider_id) VALUES (?,?,?,?)");
	$statement->bind_param("sisi", $date, $client_id, $coc_or_host, $provider_id);
	$statement->execute();

	// increments the vacancies 
	$mysqli->query("UPDATE `$coc_or_host` SET vacancy = vacancy + 1 WHERE id=$provider_id");
	$vacancy = $mysqli->query("SELECT * FROM `$coc_or_host` WHERE id=$provider_id")->fetch_assoc()["vacancy"];

	// creates a new vacancy record
	$statement = $mysqli->prepare("INSERT INTO vacancy_records (date, vacancy, coc_or_host, provider_id) VALUES (?,?,?,?)");
	$statement->bind_param("sisi", $date, $vacancy, $coc_or_host, $provider_id);
	$statement->execute();

	// deletes service record
	$statement = $mysqli->prepare("DELETE FROM provided_services WHERE client_id=? AND host_or_coc=? AND provider_id=?");
	$statement->bind_param("isi", $id, $coc_or_host, $provider_id);
	$statement->execute();

	// sets client "moved_on" to true
	$mysqli->query("UPDATE client SET moved_on=1 WHERE id=$client_id");

}

header("Location: index.php");
?>