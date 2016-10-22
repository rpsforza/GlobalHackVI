<?php

require("../db_manager.php");

if (isset($_POST)) {
	
	$first_name = $_POST["first_name"];
	$middle_name = $_POST["middle_name"];
	$last_name = $_POST["last_name"];
	$dob = $_POST["dob"];
	$gender = $_POST["gender"];

	// $date_created = current date;

	// TODO make UUID auto increment

	$mysqli = getDB();

	$statement = $mysqli->prepare("INSERT INTO client (First_Name, Middle_Name, Last_Name, DOB, Gender)
									VALUES (?, ?, ?, ?, ?)");
	$statement->bind_param("ssssi", $first_name, $middle_name, $last_name, $dob, $gender);
	$statement->execute();


} else {
	echo "POST data not set.";
}
