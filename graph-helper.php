<?php

	// min_date / max_date define the date range
	// they are expressed in terms of - days (so 40 means 40 days before today)
	// so min date is actually larger than max date

	// increments is the number of increments on the x-axis

	// $tables is an array containing the values "intake", "vacancy", and / or "output"
	// it stores the options for which graph(s) you want to see - one, two, or all three?

	function getCapacityMetrics($coc_or_host, $provider_id, $min_date, $max_date, $increments, $tables) {

		// calculates interval based on min date and max date
		$interval = ceil(($min_date - $max_date) / $increments);

		// converts these to the php date format (unix?) anyways, they are comparable and not backwards
		$converted_min_date = strtotime("-$min_date day");
		$converted_max_date = strtotime("-$max_date day");

		// iterates through the tables, applying the same procedure to table "vacancy" / "output" / "intake"
		foreach ($tables as $table) {

			// matches the chart.js specifications
			// each label corresponds to an x-value, each data corresponds to a y-value
			// so if label[2] = 3 and data[2] = 10/12/16, then 3 intakes happened on 10/12/16
			$labels = [];
			$data = [];

			// backwards because min_date > max_date it's weird I know
			// every increment corresponds to another tick on the x-axis
			for ($i = $min_date; $i >= $max_date; $i+=$interval) {

				// sets the value for the x-axis tick
				// so if i is 14 (as in 14 days before today) and today is 10/20/16, this should add labels[] = 10/06/16
				$time_stamp = strtotime("-$i day");
				$date = data("m/d/y", $time_stamp);
				$labels[] = $date;

				// records that fit within this date span (between i and i + interval days)
				$matching_records = 0;

				// this basically iterates through all the values of i that are skipped when i += interval
				// so if interval is 3, and i is 14, then this would look at i = 14, 15, 16
				// the next outer loop would start i at 17
				for ($j = $i; $j < $i + $interval; $j++) {

					// searches for matching records
					$time_stamp = strtotime("-$j day");
					$date = date('m/d/y', $time_stamp);
					$intake_data = $mysqli->query("SELECT * FROM intakes WHERE date='$date'");

					// if records are found, it adds them to matching_records
					if ($intake_data) $matching_records += count($intake_data);
				}

				// matching_records now equals the total number of intakes that happened between i and i + interval
				$data[] = $matching_records;
			}

			
		}

		// returns an object according to chart.js specifications
		$return_object = (object) array(
			'labels' => $labels,
			'datasets' => array(

			)
		);

	}

?>