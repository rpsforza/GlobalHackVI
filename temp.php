<?php

	require("db_manager.php");
	$mysqli = getDB();

	$info = $mysqli->query("SELECT * FROM login_accounts")->fetch_assoc();
	echo var_dump($info);

?>