<?php

header("Access-Control-Allow-Origin: *");

session_start();

$db_server;
$db_username;
$db_password;
$db_name;

if (getenv("db_server")) {
	$db_server = getenv("db_server");
	$db_username = getenv("db_user");
	$db_password = getenv("db_pass");
	$db_name = getenv("db_name");
} else if (file_exists($_SERVER["DOCUMENT_ROOT"] . '/sql-auth.json')) {
	$credFile = file_get_contents($_SERVER["DOCUMENT_ROOT"] . '/sql-auth.json');
	$creds = json_decode($credFile, true);

	$db_server = $creds["server"];
	$db_username = $creds["user"];
	$db_password = $creds["pass"];
	$db_name = $creds["name"];
}

$mysqli = new mysqli($db_server, $db_username, $db_password, $db_name);

function getDB()
{
	global $db_username, $db_password, $db_server, $db_name;

	if (isset($mysqli) && $mysqli instanceof mysqli) {
		if (!($mysqli->errno) && ($mysqli->ping()))
			return $mysqli;
	}

	return ($mysqli = new mysqli($db_server, $db_username, $db_password, $db_name));
}

function userExists($username)
{
	$mysqli = getDB();
	$statement = $mysqli->prepare("SELECT * FROM login_accounts WHERE username = ?");
	$statement->bind_param("s", $username);
	$statement->execute();
	$result = $statement->get_result();
	if ($result) {
		return $result->num_rows > 0;
	}
	return false;
}

function validateUser($username, $password)
{
	$mysqli = getDB();
	$statement = $mysqli->prepare("SELECT * FROM login_accounts WHERE username = ? AND password = ?");
	$statement->bind_param("ss", $username, $password);
	$statement->execute();
	$result = $statement->get_result();

	if (!$result) {
		return false;
	} else {
		return $result->fetch_assoc();
	}
}

function getUserType($user_id)
{
	$mysqli = getDB();
	return $mysqli->query("SELECT * FROM login_accounts WHERE id=$user_id")->fetch_assoc()["user_type"];
}

function getUserData($username, $password)
{
	$mysqli = getDB();
	$statement = $mysqli->prepare("SELECT * FROM login_accounts WHERE username = ? AND password = ?");
	$statement->bind_param("ss", $username, $password);
	$statement->execute();

	if (!$statement) {
		return false;
	}

	$result = $statement->get_result()->fetch_assoc();
	$user_type = $result["user_type"];
	$table_id = $result["table_id"];

	$user_row = $mysqli->query("SELECT * FROM $user_type WHERE id=$table_id")->fetch_assoc();
	return $user_row;
}

function getUsersName($login_id)
{
	$mysqli = getDB();
	$statement = $mysqli->prepare("SELECT * FROM login_accounts WHERE id=?");
	$statement->bind_param("i", $login_id);
	$statement->execute();

	if (!$statement) {
		return false;
	}

	$result = $statement->get_result()->fetch_assoc();
	$user_type = $result["user_type"];
	$table_id = $result["table_id"];

	if ($user_type !== "clientNoAuth") {
		$user_row = $mysqli->query("SELECT * FROM $user_type WHERE id=$table_id")->fetch_assoc();

		if ($user_row) {
			return $user_type === "client" ? $user_row["First_Name"] . " " . $user_row["Last_Name"] : $user_row["name"];
		}
	}

	return "Guest";
}

function getClient($id)
{
	$mysqli = getDB();
	$statement = $mysqli->prepare("SELECT * FROM client WHERE id=?");
	$statement->bind_param('i', $id);
	$statement->execute();
	$result = $statement->get_result();
	return $result ? $result->fetch_assoc() : false;
}

function newClient($first_name, $middle_name, $last_name, $dob, $gender)
{
	$mysqli = getDB();
	if ($gender == "Male") {
		$gender = 1;
	} else {
		$gender = 0;
	}
	$statement = $mysqli->prepare("INSERT INTO client (First_Name, Middle_Name, Last_Name, DOB, Gender)
									VALUES (?, ?, ?, ?, ?)");
	$statement->bind_param("ssssi", $first_name, $middle_name, $last_name, $dob, $gender);
	$statement->execute();
	return true;
}

function getCurrentServices($client_id)
{
	$mysqli = getDB();
	return $mysqli->query("SELECT * FROM provided_services WHERE client_id=$client_id AND completed=0")->fetch_assoc();
}

function getServiceHistory($client_id)
{
	$mysqli = getDB();
	return $mysqli->query("SELECT * FROM provided_services WHERE client_id=$client_id AND completed=1")->fetch_assoc();
}

function getHost($id)
{
	$mysqli = getDB();
	$statement = $mysqli->prepare("SELECT * FROM host WHERE id=?");
	$statement->bind_param('i', $id);
	$statement->execute();
	$result = $statement->get_result();
	return $result ? $result->fetch_assoc() : false;
}
