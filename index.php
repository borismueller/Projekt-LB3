hi

<form method="post" action="index.php">
	<input type="text" name="name">
	<input type="password" name="password">
	<input type="submit" name="submit">
</form>

<form method="post" action="index.php">
	<input type="submit" name="logout" value="logout">
</form>

<form method="post" action="index.php">
	<input type="text" name="new_name">
	<input type="password" name="new_password">
	<input type="submit" name="create_user" value="crete user">
</form>


<?php
// Start new session
session_start();

if (isset($_POST["submit"])) {
	// Create new log file, overwrite if it exists
	fopen("php.log", "w") or die("Unable to open log file!");

	$name = $_POST["name"];
	$password = $_POST["password"];

	error_log($name, 3, "php.log");
	error_log("\n", 3, "php.log");
	error_log($password. "\n", 3, "php.log");

	get_password($name, $password);
}

if (isset($_POST["create_user"])) {
	error_log("new user", 3, "php.log");

	$username = $_POST["new_name"];
	$password = $_POST["new_password"];

	create_user($username, $password);
}

if (isset($_POST["logout"])) {
	error_log("\n someone logged out \n" , 3, "php.log");
	echo "bye\n\n";
	session_destroy();
}

if (isset($_GET["dir"])) {
	if (isset($_SESSION["username"])) {
		get_dir();
	}
}

// Create Connection to database

function create_connection() {
	$servername = "localhost";
	$username = "root";
	$password = ""; // don't steal my password pls
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

function create_user($name, $password) {
	$pass = $password;
	$password = password_hash($password, PASSWORD_DEFAULT);

	error_log("\n old pass log " . $password, 3, 'php.log');

	/*if (password_verify($pass, $password)) {
		echo "heck ";
	}*/

	$conn = create_connection();

	$stmt = $conn->prepare("INSERT INTO users (name, password) VALUES (?, ?)");

	if ($stmt) {
		$stmt->bind_param('ss', $name, $password);
		$stmt->execute();

		login($name);
	}
}


// Get password from database and check login

function get_password($name, $password) {
	$conn = create_connection();

 	$stmt = $conn->prepare("SELECT password FROM users WHERE name = ? ");

	if($stmt) {
		$stmt->bind_param('s', $name);
		$stmt->execute();
		$result = $stmt->bind_result($res);
		$result = $stmt->get_result();
		$result = $result->fetch_object();
		
		error_log("new pass log" . $result->password, 3, 'php.log');

		if(password_verify($password, $result->password)) {
			login($name);
		} else {
			echo "\n oh no wrong password";
		}
		if (!$result) {
			echo "Something went wrong in the DB";
		}
		$stmt->close();
	}
	$conn->close(); //TODO: CLOSING CONNECTION HERE
} 

function login($name)
{
	echo "login";
	$_SESSION["username"] = $name;

	echo $_SESSION["username"];
}

function get_dir() {
	$get = $_GET["dir"];
	error_log($get. "\n", 3, "php.log");

	if (strlen($get) > 1) {
		echo "Parameters can only be one letter. \n\n";
		echo system("dir");
	} else {
		echo system("dir /" . $get);
	}
}