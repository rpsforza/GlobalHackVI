<?php

require("../db_manager.php");

if (isset($_POST)) {
	// id of row in "clients" table
	$client_id = $_POST["client_id"];
	// stored in "services" table
	$service_id = $_POST["service_id"];
	// host or coc provided service?
	$host_or_coc = $_POST["host_or_coc"];
	// id of row in provider table
	$provider_id = $_POST["provider_id"];
	// 0 or 1
	$completed = $_POST["completed"];
	// ex: what kind of job training? If percent_completed != 100, what else needs to be done?
	$comments = $_POST["comments"];
	// current date
	$date = date("m/d/y");

	$mysqli = getDB();

	$statement = $mysqli->prepare("INSERT INTO provided_services (client_id, service_id, host_or_coc, provider_id, completed, comments, date) VALUES (?,?,?,?,?,?)");
	$statement->bind_param("iisiiss", $client_id, $service_id, $host_or_coc, $provider_id, $completed, $comments, $date);
	$statement->execute();

} else {
	echo "POST data not set.";
}
