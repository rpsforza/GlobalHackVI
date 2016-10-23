<?php

	if (isset($_POST)) {

		require("../db_manager.php");
		$mysqli = getDB();

		/*** SET VALUES ***/

		// identifiers
		$coc_or_host = $_POST["coc_or_host"];
		$provider_id = $_POST["provider_id"];
		// min_date / max_date define the date range
		// they are expressed in terms of - days (so 40 means 40 days before today)
		// so min date is actually larger than max date
		$min_date = $_POST["min_date"];
		$max_date = $_POST["max_date"];
		// number of increments on the x-axis
		$increments = $_POST["increments"];
		// $tables is an array containing the values "completed", "initiated", "all"
		// it stores the options for which graph(s) you want to see - one, two, or all three?
		$options = $_POST["options"];

		$service_type = $_POST["service_type"];
		$service_clause = "";
		if ($service_type != "all") {
			$service_clause = " AND service_id=$service_type";
		}

		/*** MAIN FUNCTION ***/

		// calculates interval based on min date and max date
		$interval = ceil(($min_date - $max_date) / $increments);

		// converts these to the php date format (unix?) anyways, they are comparable and not backwards
		$converted_min_date = strtotime("-$min_date day");
		$converted_max_date = strtotime("-$max_date day");

		// the resulting object, according to chart.js specs (this is object "data" in the Chart object)
		$result = array(
			'labels' => [],
			'datasets' => []
		);

		// SETS X-AXIS (LABEL)
		for ($i = $min_date; $i >= $max_date; $i-=$interval) {
			// sets the value for the x-axis tick
			// so if i is 14 (as in 14 days before today) and today is 10/20/16, this should make result["labels"][] = 10/06/16
			$time_stamp = strtotime("-$i day");
			$date = date("m/d/y", $time_stamp);
			$result["labels"][] = $date;
		}

		// iterates through the tables, applying the same procedure to table "vacancy" / "output" / "intake"
		foreach ($options as $option) {

			// the y-axis data corresponding to this table
			$data = [];

			// backwards because min_date > max_date it's weird I know
			// every increment corresponds to another tick on the x-axis
			for ($i = $min_date; $i >= $max_date; $i-=$interval) {
				// records that fit within this date span (between i and i + interval days)
				$matching_records = 0;

				// this basically iterates through all the values of i that are skipped when i += interval
				// so if interval is 3, and i is 14, then this would look at i = 14, 15, 16
				// the next outer loop would start i at 17
				for ($j = $i; $j < $i + $interval; $j++) {

					// searches for matching records
					$time_stamp = strtotime("-$j day");
					$date = date('m/d/y', $time_stamp);

					$count_data = false;
					if ($option === "intake" || $option === "output" || $option === "vacancy") {
						$table = $option . "_records";
						$count_data = $mysqli->query("SELECT * FROM $table WHERE date='$date' AND coc_or_host='$coc_or_host' AND provider_id=$provider_id")->fetch_all();
					} else if ($option === "reservations") {
						$count_data = $mysqli->query("SELECT * FROM reservation_records WHERE date='$date' AND coc_or_host='$coc_or_host' AND provider_id=$provider_id AND showed_up=1")->fetch_all();
					} else if ($option === "missed_reservations") {
						$count_data = $mysqli->query("SELECT * FROM reservation_records WHERE date='$date' AND coc_or_host='$coc_or_host' AND provider_id=$provider_id AND showed_up=0")->fetch_all();
					} else {
						$clause = "";
						if ($option === "completed") {
							$clause = " AND completed=1";
						} else if ($option === "initiated") {
							$clause = " AND completed=0";
						}
						$query = "SELECT * FROM provided_services WHERE date='$date' AND coc_or_host='$coc_or_host' AND provider_id=$provider_id" . $clause . $service_clause;

						$count_data = $mysqli->query($query)->fetch_all();
					}

					// if records are found, it adds them to matching_records
					if ($count_data) {
						$matching_records += count($count_data);
					}
				}

				// matching_records now equals the total number of intakes that happened between i and i + interval
				$data[] = $matching_records;
			}

			$rgb = "";
			switch ($option) {
				case "intake": $rgb = "rgba(100, 0, 0, 0.4)"; break;
				case "output": $rgb = "rgba(175, 15, 15, 0.4)"; break;
				case "vacancy": $rgb = "rgba(250, 30, 30, 0.4)"; break;
				case "all": $rgb = "rgba(0, 100, 0, 0.4)"; break;
				case "completed": $rgb = "rgba(15, 175, 15, 0.4)"; break;
				case "initiated": $rgb = "rgba(30, 250, 30, 0.4)"; break;
				case "reservations": $rgb = "rgba(0, 0, 100, 0.4)"; break;
				case "missed_reservations": $rgb = "rgba(15, 15, 175, 0.4)"; break; 
				default: $rgb = "rgba(30, 30, 250, .4";
			}

			$result["datasets"][] = (object) array(
				'label' => $option,
				'backgroundColor' => $rgb,
				'data' => $data
			);
		}

		echo json_encode($result);
	}
?>