<?php
	//CloseShift.php
	$driverID = $_POST["driverIDHidden2"]; //candidate for cleanup
	
	$servername = "studentwebfilesus.ipagemysql.com";
	$server = "cmeier";
	
	$conn = new mysqli($servername, $server, $server, $server);
	if($conn->connect_error)
	{
		die("Connection Failed: " . $conn->connect_error);	
	}
	else
	{	
		$conn->query("UPDATE GrandOrders SET Settled='YES' WHERE DriverID='$driverID'"); //Settle all orders for this driver
		$conn->query("UPDATE Drivers SET ClockedIn=0 WHERE DriverID='$driverID'"); //Clock out driver
		
		$conn->close();
	}
	$url = 'OrdersPage.html';
	header( "Location: $url" );

?>