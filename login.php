<?php
//login.php
if(isset($_POST['submit']))
{
$username = $_POST["username"];
$password = $_POST["password"];
$driverID;
$success = false;

$serverName = "studentwebfilesus.ipagemysql.com";
$serverUser = "cmeier";
$serverPW = "cmeier";
$dbname = "cmeier";

$conn = new mysqli($serverName, $serverUser, $serverPW, $dbname);
if ($conn->connect_error)
{
	die("Connection Failed: ". $conn->connect_error);
}

$sql = "SELECT Username, Password FROM Login WHERE Username = ?"; //AND Password = ?"; //get hashed pw

if($stmt = $conn->prepare($sql))
{
	$stmt->bind_param("s", $username); //, $password);	
	$stmt->execute();	
	$stmt->bind_result($boundUsername, $boundPW);
	
	$usernames = array();
	$passwords = array();
	while ($stmt->fetch())
	{
		$usernames[] = $boundUsername;
		$passwords[] = $boundPW;
	}
	
	if(!empty($usernames)) //pw_ver(plaintextPW, hashedPW)
	{
		if(password_verify($password, $passwords[0]))
		{
			$success = true;
		}
	}
}


//$result = $conn->query($sql);

if($success)
{
	//$_SESSION['use'] = $username;
	$sql = "SELECT DriverID FROM Login WHERE Username = '$usernames[0]' AND Password = '$passwords[0]'";
	$result = $conn->query($sql);
	while($row = $result->fetch_assoc())
	{
		$driverID = $row["DriverID"];	
	}
	
	$sql = "UPDATE Drivers SET ClockedIn = 1 WHERE DriverID = '$driverID'";
	$conn->query($sql);
	
	echo '<script> window.open("OrdersPage.html", "_self");</script>';
}
else
{
        echo "invalid username or password";
}

$conn->close();
}


?>

<html>
	<head>
	</head>
	
	<body>
<form action="" method="Post">
Username:<br /><input name="username" type="text" /><br /> 
Password:<br /><input name="password" type="password" /> </br>
<input type="submit" name = "submit" value="Submit" />
</form>
<button onclick="location.href = 'OrdersPage.html';">Orders Page</button>
</body>
</html>