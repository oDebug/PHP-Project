<?php
	$driverID = $_POST["driverID3"];
	$driverName = "";
	$canClose = 1;
	$dataRow = "";
	$driverIDHidden = "<input type='hidden' name='driverIDHidden' value=$driverID id='hiddenDriverID'>";
	$driverIDHidden2 = "<input type='hidden' name='driverIDHidden2' value=$driverID id='hiddenDriverID2'>";
	$servername = "studentwebfilesus.ipagemysql.com";
	$server = "cmeier";
	
	$conn = new mysqli($servername, $server, $server, $server);
	
	if($conn->connect_error)
	{
		die("Connection Failed: " . $conn->connect_error);	
	}
	else
	{
		$result = $conn->query("SELECT Nick FROM Drivers WHERE DriverID = '$driverID'");
		if($result->num_rows > 0)
		{
			
			
			while($row = $result->fetch_assoc())
			{
				//var_dump($result->fetch_assoc());
				$driverName = $row["Nick"];
				//echo $row["Nick"];
				break;
			}
		}
		
		$result = $conn->query("SELECT * FROM GrandOrders WHERE DriverID = '$driverID' AND Status = 'IN_PROCESS'");
		if($result->num_rows > 0)
		{
			//empty strings are considered false, as is the number 0
			$canClose = 0;
		}
		$canCloseHidden = "<input type='hidden' name='canCloseHidden' value=$canClose id='hiddenCanClose'>";
	
		$result = $conn->query("SELECT GrandOrders.TicketID, GrandOrders.Date, Drivers.Nick FROM GrandOrders INNER JOIN Drivers ON GrandOrders.DriverID WHERE GrandOrders.DriverID = '$driverID' AND GrandOrders.Settled = 'NO' AND Drivers.DriverID = '$driverID'");
		if($result->num_rows > 0)
		{
			while($row = mysqli_fetch_array($result))
			{
				$dataRow = $dataRow . "<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td><input type='checkbox' name='cboxOrders'></td></tr>";				
			}
		}
		
		$conn->close();
	}
	
?>

<html>
<head>
<style>
	th 
	{
		border: 1px solid black;
		text-align: left;
		padding: 8px;
	}
	
	tr:nth-child(even)
	{
		background-color: #dddddd;
	}
	.grid-container 
	{
		  display: grid;
		  grid-template-columns: 30% auto auto;
		  grid-template-rows: 800px;
		  background-color: #2196F3;
		  padding: 10px;
	}
	.grid-container-inner
	{
		  display: grid;
		  grid-template-columns: auto auto auto;
		  grid-template-rows: 700px;
		  background-color: #2196F3;
		  padding: 10px;
	}
	.grid-item 
	{
	  background-color: rgba(255, 255, 255, 0.8);
	  border: 1px solid rgba(0, 0, 0, 0.8);
	  padding: 2px;
	  font-size: 30px;
	  text-align: left;
	}
	.grid-item-table
	{
	  background-color: rgba(255, 255, 255, 0.8);
	  border: 1px solid rgba(0, 0, 0, 0.8);
	  padding: 20px;
	  font-size: 30px;
	  text-align: left;
	}
	.grid-item-button
	{
	  background-color: rgba(255, 255, 255, 0.8);
	  border: 1px solid rgba(0, 0, 0, 0.8);
	  padding: 2px;
	  font-size: 30px;
	  text-align: left;
	}
</style>
</head>
<body>
<?php echo $driverName; ?>
<div class="grid-item-table">
		<table id="driverOrders">
			<tr>
				<th>Ticket #</th><th>Date</th><th>Driver</th><th>Select</th>
			</tr>
			<?php echo $dataRow; ?>
		</table>
	</div>
	<br>
	<button onclick="unassign()">Unassign</button> <button onclick="closeShift()">Close Shift</button>
<button onclick="location.href = 'OrdersPage.html';">Orders Page</button>
	
	<form action="Unassign.php" method="post" id="frmUnassign">
			<table id="tblUnassign">
			</table>
			<input type="hidden" name="rowCountTimes" value="0" id="hiddenRowCountTimes">
			<?php echo $driverIDHidden; ?>
			<?php echo $canCloseHidden; ?>
		</form>
		<form action="CloseShift.php" method="post" id="frmCloseShift">
			<?php echo $driverIDHidden2; ?>
		</form>
	<script>
	
function unassign() //Use the Time data in our Orders table to update records in database, then submit form
{

	var postTable = document.getElementById("tblUnassign");
	var row; //= table.insertRow(0); //Where are we inserting new Row.
	var cell;
	var hasChecks = false;
	
	var cboxes = document.getElementsByName("cboxOrders");
	var times = [];
	var form = document.getElementById('frmUnassign');	
	
	for (var i = 0; i < cboxes.length; i++)
	{
		if(cboxes[i].checked)
		{
			times.push(cboxes[i].parentElement.parentElement.children[1].innerHTML); //gets time string
			hasChecks = true;
		}
	}

	if(hasChecks)
	{
		var hiddenRowCountTimes = document.getElementById("hiddenRowCountTimes");
		hiddenRowCountTimes.value = "" + times.length;		
		
		for (var i = 0; i < times.length; i++) //for each time in our times array,
		{
			row = postTable.insertRow(0); //add a row in our tblAssign
			cell = row.insertCell(0); //add a cell to that row
			
			cell.innerHTML = times[i] + " <input type='hidden' name='timeCell" + (i+1) + "' value='" + times[i] + "'>"; //Fill that cell with a time
		}
		
		form.submit();
	}

}

function closeShift()
{
	var canClose = document.getElementById("hiddenCanClose").value;
	var driverID = document.getElementById("hiddenDriverID").value;
	var form = document.getElementById('frmCloseShift');
	
	if(canClose)
	{
		form.submit();
		//$conn->query("UPDATE GrandOrders SET Settled='YES' WHERE DriverID=");
	}
	else
	{
	
	}
}
	
	</script>
</body>
</html>