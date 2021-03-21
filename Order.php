<?php
	$phone = $_POST["phone"];
	$address = $_POST["address"];
	$name = $_POST["name"];
	$type = $_POST["type"];
	$quant = $_POST["quant"];
	$size = $_POST["size"];
	$rowCount = $_POST["rowCount"];
	$coords = $_POST["geo"];
	$custID = -1;
	$grandID;
	//var_dump($_POST);
	//echo "phone: " . $phone;
	//echo "<br>add: " . $address;
	//echo "<br>name: " . $name;
	//echo "<br>type: " . $type;
	//echo "<br>quant: " . $quant;
	//echo "<br>size: " . $size;
	//echo "<br>";
	
	
	
	
	
	$servername = "studentwebfilesus.ipagemysql.com";
	$server = "cmeier";
	
	$conn = new mysqli($servername, $server, $server, $server);
	if($conn->connect_error)
	{
		die("Connection Failed: " . $conn->connect_error);	
	}
	else
	{ 
		$sql = "SELECT CustID FROM Customers WHERE ? = Name AND ? = Address"; //Try to find customer
		
		if($stmt = $conn->prepare($sql))
		{
			$stmt->bind_param("ss", $name, $address);
			
			$stmt->execute();
			
			$stmt->bind_result($boundCustID);
			
			$custIDs = array();
			while ($stmt->fetch())
			{
				$custIDs[] = $boundCustID;
			}
			
			if(!empty($custIDs))
			{
				$custID = $custIDs[0];
			}
		}
		
		
		//$result = $conn->query($sql);
		
		/*
		if($result->num_rows > 0)
		{
			//echo "we got rows";
			while($row = $result->fetch_assoc())
			{
				$custID = $row["CustID"];
				//echo $row["CustID"];
				
			}
		}
		else
		{
			//echo "no rows";
		}
		*/
		
		if($custID < 0) //if no customer, create record
		{
			$sql = "INSERT INTO Customers(Name, Address, Phone, Coordinates) VALUES (?, ?, ?, ?)";
			
			if($stmt = $conn->prepare($sql))
			{
				$stmt->bind_param("ssis", $name, $address, $phone, $coords);				
				$stmt->execute();
			}
			
			/*
			$conn->query($sql);
			$result = $conn->query("SELECT CustID FROM Customers WHERE '$name' = Name AND '$address' = Address"); //Find customer's generated ID
			//echo 'NO ROWS<br>';
			while($row = $result->fetch_assoc())
			{
				$custID = $row["CustID"];
				//echo $row["CustID"];
				
			}
			*/
			
			$sql = "SELECT CustID FROM Customers WHERE ? = Name AND ? = Address"; //Try to find customer
		
			if($stmt = $conn->prepare($sql))
			{
				$stmt->bind_param("ss", $name, $address);
				
				$stmt->execute();
				
				$stmt->bind_result($boundCustID);
				
				$custIDs = array();
				while ($stmt->fetch())
				{
					$custIDs[] = $boundCustID;
				}
				
				if(!empty($custIDs))
				{
					$custID = $custIDs[0];
				}
			}
			
		}
		
		$phptime = time();
		//echo 'NO ROWS<br>';
		
		//if($result->num_rows
		/*while($row = $result->fetch_assoc())
		{
			$custID = $row['CustID'];
			echo "custID: " . $custID;
		}*/
		$date = date("Y-m-d H:i:s", $phptime);
		//echo "date: " . $date;

		
		
		
		$result = $conn->query("SELECT MAX(TicketID) AS 'test' FROM GrandOrders");
		$ticket;
		$ticketID;
		
		if($result->num_rows > 0)
		{
			while($row = $result->fetch_assoc())
			{
				$ticket = $row["test"];
			}
			$ticketID = (int)$ticket + 1;
		}
		else
		{
			$ticketID = 1;
		}
		
		//echo "<br> RowCount: " . $rowCount . "<br>";
		
		$conn->query("INSERT INTO GrandOrders (TicketID, Date, Status, CustID, Settled) VALUES ('$ticketID', '$date', 'OPEN', $custID, 'NO')"); //Insert data into grand orders db
		
		$result = $conn->query("SELECT GrandID FROM GrandOrders WHERE '$ticketID' = TicketID AND '$date' = Date"); //Get the generated grandID		
		while($row = $result->fetch_assoc())
		{
			$grandID = $row["GrandID"];
			//echo "GrandID " . $row["GrandID"];			
		}
		
		
		for($x = 0; $x < $rowCount; $x++)
		{
			//$strType = "typeCell" . ($x + 1);
			//echo $strType;
			
			$type = $_POST["typeCell" . ($x + 1)];
			$quant = $_POST['quantCell' . ($x + 1)];
			$size = $_POST['sizeCell' . ($x + 1)];
			
			//echo "<br>type: " . $type;
			//echo "<br>quant: " . $quant;
			//echo "<br>size: " . $size . "<br>";
			
			
			$conn->query("INSERT INTO Orders (GrandID, PizzaID, Quantity, Size) VALUES ('$grandID', '$type', '$quant', '$size')"); //INSERT order into DB
		}
		
		
		
		
		//$conn->query("INSERT INTO Orders (Date, PizzaID, CustID, Quantity, Size, Status) VALUES ('$date', '$type', '$custID', '$quant', '$size', 'OPEN')"); //INSERT order into DB
		
		$conn->close();
	
	}
$url = 'OrdersPage.html';
	header( "Location: $url" );

?>