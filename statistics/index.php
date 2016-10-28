<!doctype html>
<?php

require '../db_manager.php';

if (isset($_SESSION["user_id"])) {

	if (isset($_GET["query"])) {

		require("../search.php");
		$search_results = search("coc", urldecode($_GET["query"]));

	}

	$mysqli = getDB();

	$coc_or_host = $_SESSION["user_type"];
	$provider_id = $_SESSION["user_type_id"];
	if (isset($_GET["coc_or_host"])) {
		$coc_or_host = $_GET["coc_or_host"];
		$provider_id = $_GET["provider_id"];
	}

} else {
	header('Location: ../login');
}

?>
<html lang="en">
<head>
	<?php require "../header.php"; ?>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.3.0/Chart.bundle.min.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.min.js"></script>
</head>
<body>
	<script>
		$(document).ready(function () {

			var options = [];
			options["intake"] = true;
			options["vacancy"] = false;
			options["output"] = false;

			options["all"] = false;
			options["completed"] = false;
			options["initiated"] = false;

			options["reservations"] = false;
			options["missed_reservations"] = false;

			var service_type = "all";

			var sliderRange = $("#slider-range");
			var amt = $("#amount");

			var min_date = -60;
			var max_date = 0;

			getOptions = function () {
				var result = [];
				for (var key in options) {
					if (options[key]) result.push(key);
				}
				return result;
			};

			generateGraph = function () {
				var option_params = getOptions();
				$.ajax({
					url: "../graph/graph-helper.php",
					success: function (result) {
						// console.log(result);
						var ctx = document.getElementById("myChart");
						var scatterChart = new Chart(ctx, {
							type: 'line',
							data: JSON.parse(result),
							borderColor: "rgba(75,192,192,1)",
							options: {
								responsive: true,
								maintainAspectRatio: true
							}
						});
					},
					error: function (result) {
						// console.log("ajax returned an error");
					},
					type: 'POST',
					data: {
						coc_or_host: <?php echo "'$coc_or_host'"; ?>,
						provider_id: <?php echo $provider_id; ?>,
						min_date: min_date,
						borderColor: "rgba(75,192,192,1)",
						max_date: max_date,
						increments: "10",
						options: option_params,
						service_type: service_type
					}
				});
			};

			sliderRange.slider({
				range: true,
				min: -60,
				max: 0,
				values: [-50, 0],
				slide: function (event, ui) {
					var min = moment().add(ui.values[0], 'days').local().format("MM/DD/YYYY");
					var max = moment().add(ui.values[1], 'days').local().format("MM/DD/YYYY");
					amt.val(min + " - " + max);
				},
				stop: function (event, ui) {
					min_date = -ui.values[0];
					max_date = -ui.values[1];
					generateGraph();
				}
			});

			var min = moment().add(sliderRange.slider("values", 0), 'days').local().format("MM/DD/YYYY");
			var max = moment().add(sliderRange.slider("values", 1), 'days').local().format("MM/DD/YYYY");
			amt.val(min + " - " + max);

			// FORM

			$('#intake').change(function () {
				options["intake"] = this.checked;
				generateGraph();
			});

			$('#vacancy').change(function () {
				options["vacancy"] = this.checked;
				generateGraph();
			});

			$('#output').change(function () {
				options["output"] = this.checked;
				generateGraph();
			});

			$('#all').change(function () {
				options["all"] = this.checked;
				generateGraph();
			});

			$('#completed').change(function () {
				options["completed"] = this.checked;
				generateGraph();
			});

			$('#initiated').change(function () {
				options["initiated"] = this.checked;
				generateGraph();
			});

			$('#reservations').change(function () {
				options["reservations"] = this.checked;
				generateGraph();
			});

			$('#missed_reservations').change(function () {
				options["missed_reservations"] = this.checked;
				generateGraph();
			});

			$("#services").change(function () {
				service_type = $("#services option:selected").val();
				generateGraph();
			});

			generateGraph();
		});
	</script>
	<div class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
		<?php
			$PAGE_TITLE = "Statistics";
			require "../nav.php";
		?>
		<main class="mdl-layout__content mdl-color--grey-100">
			<div class="mdl-grid">
				<canvas id="myChart" style="width:100px; height: 100px;"></canvas>

				<div style="width: 40%; margin-top: 30px; margin-left: 30px; margin-bottom: 50px; padding-right: 50%">
					<p>
						<label style="font-size: 18pt" for="amount">Day range:</label>
						<input type="text" id="amount" readonly
							   style="border:0; color:#f6931f; font-size: 18pt; background-color: transparent; font-weight:bold;">
					</p>
					<div id="slider-range"></div>
					<p style="font-size: 18pt; margin-top: 20px">
						Viewing Data for
						<i>
							<?php
							$name = $mysqli->query("SELECT * FROM $coc_or_host WHERE id=$provider_id")->fetch_assoc()["name"];
							echo $name;
							?>
						</i>
					</p>
				</div>

				<div style="margin: 50px; width: 100%; display: inherit;">
					<div style="width: 33%">
						<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="intake">
							<input type="checkbox" id="intake" class="mdl-checkbox__input" checked>
							<span class="mdl-checkbox__label">Homeless Taken In</span>
						</label>

						<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="vacancy">
							<input type="checkbox" id="vacancy" class="mdl-checkbox__input">
							<span class="mdl-checkbox__label">Vacancies</span>
						</label>

						<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="output">
							<input type="checkbox" id="output" class="mdl-checkbox__input">
							<span class="mdl-checkbox__label">Homeless Moved Out</span>
						</label>
					</div>

					<div style="width: 33%; margin-right: auto; margin-left: auto">
						<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="initiated">
							<input type="checkbox" id="initiated" class="mdl-checkbox__input">
							<span class="mdl-checkbox__label">Services Started, Not Completed</span>
						</label>

						<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="completed">
							<input type="checkbox" id="completed" class="mdl-checkbox__input">
							<span class="mdl-checkbox__label">Services Completed</span>
						</label>

						<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="all">
							<input type="checkbox" id="all" class="mdl-checkbox__input">
							<span class="mdl-checkbox__label">All Services</span>
						</label>

						<select style="height: 35px; margin-top: 10px" id="services">
							<option value="all">all services</option>
							<?php
							$mysqli = getDB();

							$services = $mysqli->query("SELECT * FROM services")->fetch_all(MYSQLI_ASSOC);
							for ($i = 0; $i < count($services); $i++) {
								$service = $services[$i]["name"];
								$id = $services[$i]["id"];
								echo "<option value='$id'>$service</option>";
							}
							?>
						</select>
					</div>

					<div style="width: 33%; float: right;">
						<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="reservations">
							<input type="checkbox" id="reservations" class="mdl-checkbox__input">
							<span class="mdl-checkbox__label">Reservations (Showed Up)</span>
						</label>

						<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="missed_reservations">
							<input type="checkbox" id="missed_reservations" class="mdl-checkbox__input">
							<span class="mdl-checkbox__label">Reservations (Missed)</span>
						</label>
					</div>
				</div>

				<div id="srk" style="width: 100%; margin-right: 50px; margin-left: 50px" class="mdh-expandable-search">
					<i class="material-icons">search</i>
					<form action="#tableWrap" method="GET">
						<input type="text" placeholder="search other providers" name="query" size="1">
					</form>
				</div>

				<?php if (isset($search_results)) : ?>
					<div style="width: 100%; margin: 50px" id="tableWrap">
						<table id="tabel" style="width: 100%;"
							   class="mdl-data-table mdl-js-data-table mdl-data-table mdl-shadow--2dp">
							<thead>
							<tr>
								<th style="text-align: center">Name</th>
							</tr>
							</thead>
							<tbody id="table-body">
							<?php
							for ($i = 0; $i < count($search_results); $i++) {
								$row = $search_results[$i];
								$name = $row["name"];
								$coc_or_host = $row["is_coc"] == 1 ? "coc" : "host";
								$provider_id = $row["id"];
								echo "<tr><td style='text-align: center'><a href='index.php?coc_or_host=$coc_or_host&provider_id=$provider_id'>$name</a></td></tr>";
							}
							?>
							</tbody>
						</table>
					</div>
				<?php endif; ?>

			</div>
		</main>
	</div>
	<script src="https://code.getmdl.io/1.1.3/material.min.js"></script>
</body>
</html>
