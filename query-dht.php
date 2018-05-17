<?php
	// Step 0 - Query the latest row of data to get the last datetime //
	require 'database-credentials.php';
	// database-credentials.php should simply define vars for mysqli(...)
	$sqlConn = new mysqli(
		$sqlServer, $sqlUser, $sqlPass, $sqlDb, $sqlPort);
	if($sqlConn->connect_error)
	{
		die("Connection failed: " . $sqlConn->connect_error);
	}
	// select the latest row added to the database //
	$sqlQuery = "SELECT datetime, `temperature-celsius`, humidity, voltage FROM `sensor-0` WHERE id = (SELECT MAX(id) FROM `sensor-0` WHERE 1);";
	$latestRow = NULL;
	if($sqlResult = $sqlConn->query($sqlQuery))
	{
		if($sqlResult->num_rows > 0)
		{
			$latestRow = $sqlResult->fetch_assoc();
			//while($row = $sqlResult->fetch_assoc())
			//{
			//	echo "date:". $row["datetime"] . " | temp=" . $row["temperature-celsius"] . "C | humidity=" . $row["humidity"] . "%<br>";
			//}
		}
		else
		{
			die("unable to query last row of data.");
		}
	}
	else
	{
		die("query failed! error:" . $sqlConn->error);
	}
	//echo "date:". $latestRow["datetime"] . " | temperature=" . $latestRow["temperature-celsius"] . "C | humidity=" . $latestRow["humidity"] . "%<br>";
	//print_r($latestRow);
	//echo gettype($latestRow["datetime"]);
	// Step 1 - Query all the rows between NOW and a certain time range //
	$dateLast = date_create($latestRow["datetime"]);
	if(!$dateLast)
	{
		die("Failed to create DateTime from query!");
	}
	//echo "dateLast       = ".$dateLast->format('Y-m-d H:i:s')."<br>";
	$dateRange = new DateInterval('P1D');
	$dateRangeBegin = new DateTime($dateLast->format('Y-m-d H:i:s'));
	$dateRangeBegin->sub($dateRange);
	//echo "dateRangeBegin = ".$dateRangeBegin->format('Y-m-d H:i:s')."<br>";
	$sqlQuery = "SELECT datetime, `temperature-celsius`, humidity, voltage FROM `sensor-0` WHERE datetime BETWEEN '".$dateRangeBegin->format('Y-m-d H:i:s')."' AND '".$dateLast->format('Y-m-d H:i:s')."';";
	//echo "sqlQuery = $sqlQuery";
	$outputData = array();
	if($sqlResult = $sqlConn->query($sqlQuery))
	{
		if($sqlResult->num_rows > 0)
		{
			while($row = $sqlResult->fetch_assoc())
			{
				array_push($outputData, array(
					$row["datetime"],
					(float)$row["temperature-celsius"],
					(float)$row["humidity"],
					(float)$row["voltage"]));
				//echo "id:". $row["id"] . " | date:". $row["datetime"] . " | temp=" . $row["temperature-celsius"] . "C | humidity=" . $row["humidity"] . "%<br>";
			}
		}
		else
		{
			die("empty query for date range.");
		}
	}
	else
	{
		die("query failed! error:" . $sqlConn->error);
	}
	$sqlConn->close();
	// Step 2 - find all the rows which are closest to evenly-spaced points
	//	determined by a "resolution" variable //
	$dataResolution = 800;///TODO: make this a variable that you can pass to this script via $.get somehow?...
	// Step 3 - remove all the rows that are not the "resolution" values //
	$dataSize = count($outputData);
	$cullMod = (int)($dataSize / $dataResolution);
	$outputDataCulled = NULL;
	if($cullMod > 0)
	{
		$outputDataCulled = array();
		for($i = 0; $i < $dataSize; $i++)
		{
			if($i % $cullMod == 0)
			{
				array_push($outputDataCulled, $outputData[$i]);
			}
		}
	}
	else
	{
		$outputDataCulled = $outputData;
	}
	// Step 4 - convert the data into JSON and echo it //
	echo json_encode($outputDataCulled);
?>