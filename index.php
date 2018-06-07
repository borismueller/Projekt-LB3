hi

<form method="post" action="index.php">
<input type="text" name="name">
<input type="password" name="password">
<input type="submit">
</form>

<?php
fopen("php.log", "w") or die("Unable to open log file!");

$name = $_POST["name"];
$password = $_POST["password"];

error_log($name, 3, "php.log");
error_log("\n", 3, "php.log");
error_log($password, 3, "php.log");

get_password($name, $password);



function create_connection() {
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "user";

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);

	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	    return false;
	} 
	echo "Connected successfully";

	return $conn;
}

function get_password($name, $password) {
	$conn = create_connection();

 	$stmt = $conn->prepare("SELECT password FROM users WHERE name = ? ");

	if($stmt) {
		$stmt->bind_param('s', $name);
		$stmt->execute();
		$result = $stmt->bind_result($res);
		$result = $stmt->get_result();
		$result = $result->fetch_object();
		echo $result->password;
		if($password == $result->password) {
			login();
		}
		if (!$result) {
			echo "aaaaaaaaaaaaaa";
		}
		$stmt->close();
	}
	$conn->close(); //TODO: CLOSING CONNECTION HERE
} 

function login()
{
	echo "aaaaaaaaaaaaaaaaaa";
}