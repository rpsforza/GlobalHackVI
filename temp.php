<?php

	require("db_manager.php");
	$mysqli = getDB();

	$info = $mysqli->query("SELECT * FROM provided_services")->fetch_assoc();
	echo var_dump($info);

?>