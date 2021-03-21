<?php
	$driverID = $_POST["driverID2"];
	
	$servername = "studentwebfilesus.ipagemysql.com";
	$server = "cmeier";
	
	$conn = new mysqli($servername, $server, $server, $server);
	if($conn->connect_error)
	{
		die("Connection Failed: " . $conn->connect_error);	
	}
	else
	{
		$conn->query("UPDATE GrandOrders SET Status='CLOSED' WHERE DriverID='$driverID'");
		
		$conn->close();
	}
	
	$url = 'OrdersPage.html';
	header( "Location: $url" );
?>