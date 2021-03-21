<?php
	$rowCount = $_POST["rowCountTimes"];
	$driverID = $_POST["driverID1"];
	
	$servername = "studentwebfilesus.ipagemysql.com";
	$server = "cmeier";
	
	$conn = new mysqli($servername, $server, $server, $server);
	if($conn->connect_error)
	{
		die("Connection Failed: " . $conn->connect_error);	
	}
	else
	{
		for($x = 0; $x < $rowCount; $x++)
		{			
			$time = $_POST["timeCell" . ($x + 1)];
			$conn->query("UPDATE GrandOrders SET Status='IN_PROCESS', DriverID='$driverID' WHERE Date='$time'");
		}
		
		$conn->close();
	}
	$url = 'OrdersPage.html';
	header( "Location: $url" );

?>