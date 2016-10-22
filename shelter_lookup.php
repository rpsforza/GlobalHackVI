<?php
	
	require("db_manager.php");

	function getCompatibleShelters($client_id) {
		$mysqli = getDB();
		$client = $myqli->query("SELECT * FROM clients WHERE id=$client_id")->fetch_assoc();

		$client_age = 
	}

?>