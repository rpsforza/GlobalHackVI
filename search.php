	<?php

	require("db_manager.php");
	$mysqli = getDB();

	if (isset($_POST["type"])) {

		// searching for client or service?
		$type = $_POST["type"];
		$search_query = $_POST["search_query"];
		$search_terms = explode(" ", $search_query);

		$results = [];

		if ($type === "client") {

			for ($i = 0; $i < count($search_terms); $i++) {
				$term = $search_terms[$i];
				$new_results = $mysqli->query("SELECT * FROM client WHERE First_Name='$term' OR Middle_Name='$term' OR Last_Name='$term'") or die($mysqli->error);
				$new_results = $new_results->fetch_all(MYSQLI_ASSOC);
				$results = array_unique(array_merge($results, $new_results), SORT_REGULAR);
			}

		} else {

			for ($i = 0; $i < count($search_terms); $i++) {
				$term = $search_terms[$i];
				$new_results = $mysqli->query("SELECT * FROM coc WHERE name=$'term'")->fetch_all(MYSQLI_ASSOC);
				$results = array_unique(array_merge($results, $new_results), SORT_REGULAR);
				$new_results = $mysqli->query("SELECT * FROM host WHERE name=$'term'")->fetch_all(MYSQLI_ASSOC);
				$results = array_unique(array_merge($results, $new_results), SORT_REGULAR);
			}
		}

		echo var_dump($results);
		echo "HI";
	}
?>

<html>
<head>
</head>

<body>
<form method="post">
	<input name="search_query" />
	<input type="hidden" name="type" value="coc" />
	<input type="submit" />
</form>
</body>