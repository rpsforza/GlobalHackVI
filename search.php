<?php

function search($type, $query)
{
	$mysqli = getDB();
	$search_terms = explode(" ", $query);
	$results = [];

	if ($type === "client") {

		for ($i = 0; $i < count($search_terms); $i++) {
			$term = $search_terms[$i];
			$new_results = $mysqli->query("SELECT * FROM client WHERE First_Name LIKE '%$term%' OR Middle_Name LIKE '%$term%' OR Last_Name LIKE '%$term%'")->fetch_all(MYSQLI_ASSOC);
			$results = array_unique(array_merge($results, $new_results), SORT_REGULAR);
		}

	} else {

		for ($i = 0; $i < count($search_terms); $i++) {
			$term = $search_terms[$i];
			$new_results = $mysqli->query("SELECT * FROM coc WHERE name LIKE '%$term%'")->fetch_all(MYSQLI_ASSOC);
			$results = array_unique(array_merge($results, $new_results), SORT_REGULAR);
			$new_results = $mysqli->query("SELECT * FROM host WHERE name LIKE '%$term%'")->fetch_all(MYSQLI_ASSOC);
			$results = array_unique(array_merge($results, $new_results), SORT_REGULAR);
		}
	}
	return $results;
}
