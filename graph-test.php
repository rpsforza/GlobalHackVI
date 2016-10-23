<html>
<head>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>

	<!-- slider -->
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
		  integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css"
		  integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"
			integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS"
			crossorigin="anonymous"></script>

	<!-- graphs -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.3.0/Chart.bundle.min.js"></script>
</head>
<body>
	<script>
		$(document).ready(function() {

			var options = [];
			options["intake"] = false;
			options["vacancy"] = false;
			options["output"] = false;

			options["all"] = false;
			options["completed"] = false;
			options["initiated"] = false;

			var service_type = "all";

			var sliderRange = $("#slider-range");
			var amt = $("#amount");

			getOptions = function() {
				var result = [];
				for (var key in options) {
    				if (options[key]) result.push(key);
				}
				return result;
			}

			sliderRange.slider({
				range: true,
				min: 0,
				max: 10,
				values: [1, 3],
				slide: function (event, ui) {
					amt.val("$" + ui.values[0] + " - $" + ui.values[1]);
				},
				stop: function(event, ui) {
					var option_params = getOptions();
					$.ajax({
						url: "graph/graph-helper.php",
						success: function(result) {
							console.log(result);
			    			var ctx = document.getElementById("myChart");
							var scatterChart = new Chart(ctx, {
								type: 'line',
								data: JSON.parse(result),
								options: {
									responsive: false
								}
							});
			        	},
			        	error: function(result) {
			        		console.log("ajax returned an error");
			        	},
			        	type: 'POST',
		        		data: {
		        			coc_or_host: "coc",
		        			provider_id: "1",
		        			min_date: ui.values[1],
		        			max_date: ui.values[0],
		        			increments: "10",
		        			options: option_params,
		        			service_type: service_type
		        		}
	  				});
				}
			});

			amt.val("$" + sliderRange.slider("values", 0) + " - $" + sliderRange.slider("values", 1));

			// FORM

			$('#intake').change(function() {
			    options["intake"] = this.checked;
			});

			$('#vacancy').change(function() {
			    options["vacancy"] = this.checked;
			});

			$('#output').change(function() {
			    options["output"] = this.checked;
			});

			$('#all').change(function() {
			    options["all"] = this.checked;
			});

			$('#completed').change(function() {
			    options["completed"] = this.checked;
			});

			$('#initiated').change(function() {
			    options["initiated"] = this.checked;
			});

			$("#services").change(function () {
				service_type = $("#services option:selected").val();
			});
		});
	</script>

	<canvas id="myChart" width="1000" height="600"></canvas>

	<div style="width: 40%; margin-top: 30px; margin-left: 30px">
		<p>
			<label for="amount">Day range:</label>
			<input type="text" id="amount" readonly style="border:0; color:#f6931f; font-weight:bold;">
		</p>
		<div id="slider-range"></div>
	</div>

	<input type="checkbox" id="intake"> <label>Homeless Taken In</label>
	<input type="checkbox" id="vacancy"> <label>Vacancies</label>
	<input type="checkbox" id="output"> <label>Homeless Moved Out</label>

	<input type="checkbox" id="initiated"> <label>Services Initiated</label>
	<input type="checkbox" id="completed"> <label>Services Completed</label>
	<input type="checkbox" id="all"> <label>Services Completed or Initiated</label>

	<select id="services">
		<option value="all">all services</option>
		<?php
			require("db_manager.php");
			$mysqli = getDB();

			$services = $mysqli->query("SELECT * FROM services")->fetch_all(MYSQLI_ASSOC);
			for ($i = 0; $i < count($services); $i++) {
				$service = $services[$i]["name"];
				$id = $services[$i]["id"];
				echo "<option value='$id'>$service</option>";
			}
		?>
	</select>
</body>
</html>
