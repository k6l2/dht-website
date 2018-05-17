<html>
	<head>
		<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script type="text/javascript">
			google.charts.load('current', {'packages':['corechart']});
			google.charts.setOnLoadCallback(beginChartUpdates);
			var chartSensor0;
			var chartSensor0Voltage;
			var options = {
				title: 'Sensor 0 Temperature & Humidity',
				titleTextStyle: {
					color: 'white'},
				backgroundColor: '#101010',
				chartArea: {
					backgroundColor: '#000000',
					width: 800,
					height: 125 },
				colors: ['red', 'blue', 'yellow'],
				hAxis: {
					textStyle:{
						color: 'white'} },
				vAxis: {
					textStyle:{
						color: 'white'} },
				legend: { 
					position: 'bottom',
					textStyle:{
						color: 'white'}  },
				explorer: {
					actions: ['dragToZoom', 'rightClickToReset'],
					maxZoomIn: 0.025 },
				curveType: 'function'
			};
			var optionsVoltage = {
				title: 'Sensor 0 Voltage',
				titleTextStyle: {
					color: 'white'},
				backgroundColor: '#101010',
				chartArea: {
					backgroundColor: '#000000',
					width: 800,
					height: 125 },
				colors: ['yellow'],
				hAxis: {
					textStyle:{
						color: 'white'} },
				vAxis: {
					textStyle:{
						color: 'white'} },
				legend: { 
					position: 'bottom',
					textStyle:{
						color: 'white'}  },
				explorer: {
					actions: ['dragToZoom', 'rightClickToReset'],
					maxZoomIn: 0.025 },
				curveType: 'function'
			};
			function drawChart() {
				var dataSensor0 = new google.visualization.DataTable();
				var dataSensor0Voltage = new google.visualization.DataTable();
				dataSensor0.addColumn('datetime', 'Date');
				dataSensor0.addColumn('number'  , 'Temperature - Celsius');
				dataSensor0.addColumn('number'  , 'Humidity - %');
				dataSensor0Voltage.addColumn('datetime', 'Date');
				dataSensor0Voltage.addColumn('number'  , 'Voltage');
				$.get("query-dht.php", function(data){
					var sensorJson        = JSON.parse(data);
					var sensorJsonVoltage = JSON.parse(data);
					for(var i = 0; i < sensorJson.length; i++){
						sensorJson[i][0] = new Date(sensorJson[i][0]);
						sensorJson[i].splice(3, 1);
						sensorJsonVoltage[i][0] = new Date(sensorJsonVoltage[i][0]);
						sensorJsonVoltage[i].splice(1,2);
					}
					console.log('sensorJson.length='+sensorJson.length);
					dataSensor0.addRows(sensorJson);
					dataSensor0Voltage.addRows(sensorJsonVoltage);
					// Clear the chart first before drawing again to prevent memory leaks! //
					if(chartSensor0){
						chartSensor0.clearChart();
					}
					if(chartSensor0Voltage){
						chartSensor0Voltage.clearChart();
					}
					// Instatiate charts here //
					///TODO: figure out why only initializing this once breaks the chart explorer functionality
					chartSensor0 = new google.visualization.LineChart(
						document.getElementById('chart-sensor-0'));
					chartSensor0Voltage = new google.visualization.LineChart(
						document.getElementById('chart-sensor-0-voltage'));
					chartSensor0.draw(dataSensor0, 
						//google.charts.Line.convertOptions(options));
						options);
					chartSensor0Voltage.draw(dataSensor0Voltage,
						optionsVoltage);
				});
			}
			function beginChartUpdates(){
				drawChart();
				setInterval(drawChart, 10000);
				$('div').last().remove();
			}
		</script>
		<style>
			body{
				background-color: #000000;
				color			: #CCCCCC;
			}
		</style>
		<title>Kyle-Dev</title>
	</head>
	<body>
		<div id="chart-sensor-0" style="width: 900px; height: 200px; margin: auto;">LOADING SENSOR 0 TEMPERATURE/HUMIDITY DATA...</div>
		<div id="chart-sensor-0-voltage" style="width: 900px; height: 200px; margin: auto;">LOADING SENSOR 0 VOLTAGE DATA...</div>
	</body>
</html>