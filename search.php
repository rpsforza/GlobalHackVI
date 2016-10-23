<?php

	require("db_manager.php");

	if (isset($_POST)) {

		// searching for client or service?
		$type = $_POST["type"];
		$search_query = $_POST["search_query"];

		if ($type === "client") {

			

		} else {

		}

	}

?>

<form method="post">
	<input name="search_query" />
	<input type="submit">
</form>