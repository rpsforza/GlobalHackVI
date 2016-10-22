<?php

require("../db_manager.php");

date("m/d/y");

if (isset($_POST)) {
	
	// id of row in "clients" table
	$client_id = $_POST["client_id"];
	// stored in "services" table
	$service_id = $_POST["service_id"];
	// between 1 - 100. How much was done?
	$percent_completed = $_POST["percent_completed"];
	// ex: what kind of job training? If percent_completed != 100, what else needs to be done?
	$comments = date("m/d/y") . ":\n" . $_POST["comments"];

	$mysqli = getDB();

	$statement = $mysqli->prepare("SELECT * FROM provided_services WHERE client_id=? AND service_id=?");
	$statement->bind_param("ii", $client_id, $service_id);

	// if an entry with this client and service already exists, update the entry
	if ($statement->execute()) {

		$old_comments = $mysqli->query("SELECT * FROM provided_services WHERE client_id=$client_id AND service_id=$service_id")->fetch_assoc()["comments"];
		$updated_comments = $comments . "\n\n" . $old_comments;

		$statement = $mysqli->prepare("UPDATE provided_services SET percent_completed=?, comments=?");
		$statement->bind_param("is", $percent_completed, $updated_comments);
		$statement->execute();

	// if not, make a new entry
	} else {

		$statement = $mysqli->prepare("INSERT INTO provided_services (client_id, service_id, percent_completed, comments) VALUES (?,?,?,?)");
		$statement->bind_param("iiis", $client_id, $service_id, $percent_completed, $comments);
		$statement->execute();

	}

} else {
	echo "POST data not set.";
}

?>