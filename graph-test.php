<html>
<head>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	
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
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.3.0/Chart.bundle.min.js"></script>
</head>
<body>
	<canvas id="myChart" width="1000" height="600"></canvas>
<script>
var ctx = document.getElementById("myChart");
var scatterChart = new Chart(ctx, {
    type: 'line',
    data: {
        datasets: [
	        {
	        	label: 'Scatter Dataset',
	            data: [{
	                x: -10,
	                y: 0
	            }, {
	                x: 0,
	                y: 10
	            }, {
	                x: 10,
	                y: 5
	            }]
	        },
	        {
	        	label: 'Scatter Dataset 2',
	        	data: [{
	        		x: -10,
	        		y: 9
	        	}, {
	        		x: 5,
	        		y: 3
	        	}, {
	        		x: 10,
	        		y: 14
	        	}],
	        	backgroundColor: [
                	'rgba(255, 99, 132, 0.2)',
                ]
	        }
        ]
    },
    options: {
    	responsive: false,
        scales: {
            xAxes: [{
                type: 'linear',
                position: 'bottom',
                scaleLabel: {
                	display: true,
                	labelString: 'time'
                }
            }]
        }
    }
});

</script>

 <script>
  $( function() {
    $( "#slider-range" ).slider({
      range: true,
      min: 0,
      max: 500,
      values: [ 75, 300 ],
      slide: function( event, ui ) {
        $( "#amount" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
      }
    });
    $( "#amount" ).val( "$" + $( "#slider-range" ).slider( "values", 0 ) +
      " - $" + $( "#slider-range" ).slider( "values", 1 ) );
  } );
  </script>
</head>
<body>
 
<p>
  <label for="amount">Price range:</label>
  <input type="text" id="amount" readonly style="border:0; color:#f6931f; font-weight:bold;">
</p>
 
<div id="slider-range"></div>
</body>
</html>
