<!DOCTYPE html>
<html>
<head>
	<title>LB3</title>
	<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

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

		if(strlen($name) > 0 && strlen($password) > 0) {
			error_log("in passw\n",3, "php.log");
			get_password($name, $password);
		} else {
			//echo '<script>alert("please enter Password and Username")</script>';
			// just ignore it
		}
	}

	if (isset($_POST["create_user"])) {
		error_log("new user", 3, "php.log");

		$username = $_POST["new_name"];
		$password = $_POST["new_password"];

		if(isset($name) && isset($password)) {
			create_user($username, $password);		}
		} else {
			//echo '<script>alert("please enter Password and Username")</script>';
			// just ignore it
		}

	if (isset($_GET["logout"])) {
		error_log("\n someone logged out \n" , 3, "php.log");
		echo "bye\n\n";
		session_unset("username");
		session_destroy();
		header('Location: /');
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
		//echo "Connected successfully";

		return $conn;
	}

	function create_user($name, $password) {
		$pass = $password;
		$password = password_hash($password, PASSWORD_DEFAULT);

		error_log("\n old pass log " . $password, 3, 'php.log');

		$conn = create_connection();

		$stmt = $conn->prepare("SELECT name FROM users WHERE name = ?");
		if($stmt) {
			$stmt->bind_param('s', $name);
			$stmt->execute();
			$result = $stmt->get_result();
			$result = $result->fetch_object();

			if (!isset($result)) {
				$stmt = $conn->prepare("INSERT INTO users (name, password) VALUES (?, ?)");
				if ($stmt) {
					$stmt->bind_param('ss', $name, $password);
					$stmt->execute();

					login($name);
				}
			} else {
				echo 'username already exists';
			}
		}
	}


	// Get password from database and check login

	function get_password($name, $password) {
		$conn = create_connection();

	 	$stmt = $conn->prepare("SELECT password FROM users WHERE name = ? ");

		if($stmt) {
			$stmt->bind_param('s', $name);
			$stmt->execute();
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
		$conn->close();
	} 

	function login($name)
	{
		echo "login";
		$_SESSION["username"] = $name;

		echo htmlspecialchars($_SESSION["username"]);
	}

	function get_dir() {
		$get = $_GET["dir"];
		error_log($get. "\n", 3, "php.log");

		if (strlen($get) != 1 ) {
			echo "Parameters can only be one letter.\n\n";
			echo system("tree");
		} else {
			echo system("dir /" . $get);
		}
	}?>

	<div class="container"><?php
	// form handling

	if (isset($_GET['reg']) && !isset($_SESSION['username'])) {
		echo '	<div class="form-wrapper">
					<h1>Register</h1>
					<form method="post" action="/">
						<input type="text" name="new_name" placeholder="Username">
						<input type="password" name="new_password" placeholder="Password">
						<div>
							<input type="submit" name="create_user" value="Create user">
							<button><a href="/">Login</a></button>
						</div>
					</form>
				</div>';
	} elseif (!isset($_SESSION['username'])) {
		echo '	<div class="form-wrapper">
					<h1>Login</h1>
					<form method="post" action="/">
						<input type="text" name="name" placeholder="Username">
						<input type="password" name="password" placeholder="Password">
						<div>
							<input type="submit" name="submit" value="Login">
							<button><a href="/?reg">Register</a></button>
						</div>
					</form>
				</div>';
	} else {
		echo '<a href="/?logout" name="logout">Logout</a>';
	}?>
	</div>
</body>
</html>