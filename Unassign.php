<?php
	$rowCount = $_POST["rowCountTimes"];
	$driverID = $_POST["driverIDHidden"];
	//$canClose = $_POST["canCloseHidden"];
	
	$servername = "studentwebfilesus.ipagemysql.com";
	$server = "cmeier";
	
	$conn = new mysqli($servername, $server, $server, $server);
	if($conn->connect_error)
	{
		die("Connection Failed: " . $conn->connect_error);	
	}
	else
	{
		
		//Check to see if any orders are still IN_PROCESS
		//NEVERMIND, DONE IN PREVIOUS PAGE
		
		
		for($x = 0; $x < $rowCount; $x++)
		{			
			$time = $_POST["timeCell" . ($x + 1)];
			$conn->query("UPDATE GrandOrders SET Status='OPEN', DriverID=0 WHERE Date='$time'");
		}
		
		$conn->close();
	}
	$url = 'OrdersPage.html';
	header( "Location: $url" );

?>