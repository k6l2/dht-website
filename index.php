<html>
	<head>
		<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script type="text/javascript">
			google.charts.load('current', {'packages':['corechart']});
			google.charts.setOnLoadCallback(beginChartUpdates);
			var chartSensor0T;
			var chartSensor0H;
			var chartSensor0Voltage;
			var optionsTemp = {
				title: 'Sensor 0 Temperature',
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
			var optionsHumid = {
				title: 'Sensor 0 Humidity',
				titleTextStyle: {
					color: 'white'},
				backgroundColor: '#101010',
				chartArea: {
					backgroundColor: '#000000',
					width: 800,
					height: 125 },
				colors: ['blue'],
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
				var dataSensor0T       = new google.visualization.DataTable();
				var dataSensor0H       = new google.visualization.DataTable();
				var dataSensor0Voltage = new google.visualization.DataTable();
				dataSensor0T.addColumn('datetime', 'Date');
				dataSensor0T.addColumn('number'  , 'Temperature - Celsius');
				dataSensor0H.addColumn('datetime', 'Date');
				dataSensor0H.addColumn('number'  , 'Humidity - %');
				dataSensor0Voltage.addColumn('datetime', 'Date');
				dataSensor0Voltage.addColumn('number'  , 'Voltage');
				$.get("query-dht.php", function(data){
					/*
						data[i][0] = Date
						data[i][1] = Temperature(C)
						data[i][2] = Humidity
						data[i][3] = Voltage
					*/
					var sensorJsonT       = JSON.parse(data);
					var sensorJsonH       = JSON.parse(data);
					var sensorJsonVoltage = JSON.parse(data);
					for(var i = 0; i < sensorJsonT.length; i++){
						sensorJsonT[i][0] = new Date(sensorJsonT[i][0]);
						// delete humidity & voltage from temperature graph
						sensorJsonT[i].splice(2, 2);
						sensorJsonH[i][0] = new Date(sensorJsonH[i][0]);
						// delete temperature & voltage from humidity graph
						sensorJsonH[i].splice(3, 1);
						sensorJsonH[i].splice(1, 1);
						sensorJsonVoltage[i][0] = new Date(sensorJsonVoltage[i][0]);
						// delete temperature & humidity from voltage graph
						sensorJsonVoltage[i].splice(1,2);
					}
					console.log('sensorJsonT.length='+sensorJsonT.length);
					dataSensor0T.addRows(sensorJsonT);
					dataSensor0H.addRows(sensorJsonH);
					dataSensor0Voltage.addRows(sensorJsonVoltage);
					// Clear the chart first before drawing again to prevent memory leaks! //
					if(chartSensor0T){
						chartSensor0T.clearChart();
					}
					if(chartSensor0H){
						chartSensor0H.clearChart();
					}
					if(chartSensor0Voltage){
						chartSensor0Voltage.clearChart();
					}
					// Instatiate charts here //
					///TODO: figure out why only initializing this once breaks the chart explorer functionality
					chartSensor0T = new google.visualization.LineChart(
						document.getElementById('chart-sensor-0-temp'));
					chartSensor0H = new google.visualization.LineChart(
						document.getElementById('chart-sensor-0-humid'));
					chartSensor0Voltage = new google.visualization.LineChart(
						document.getElementById('chart-sensor-0-voltage'));
					chartSensor0T.draw(dataSensor0T,
						optionsTemp);
					chartSensor0H.draw(dataSensor0H,
						optionsHumid);
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
		<div id="chart-sensor-0-temp" style="width: 900px; height: 200px; margin: auto;">LOADING SENSOR 0 TEMPERATURE DATA...</div>
		<div id="chart-sensor-0-humid" style="width: 900px; height: 200px; margin: auto;">LOADING SENSOR 0 HUMIDITY DATA...</div>
		<div id="chart-sensor-0-voltage" style="width: 900px; height: 200px; margin: auto;">LOADING SENSOR 0 VOLTAGE DATA...</div>
	</body>
</html>