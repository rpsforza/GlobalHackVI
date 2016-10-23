<?php

header("Access-Control-Allow-Origin: *");

session_start();

$db_server = "us-cdbr-iron-east-04.cleardb.net";
$db_username = "b50c03721510b5";
$db_password = "15258fda";
$db_name = "heroku_0e49192e673a1d3";

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

	$user_row = $mysqli->query("SELECT * FROM $user_type WHERE id=$table_id")->fetch_assoc();

	if ($user_row) {
		return $user_type === "client" ? $user_row["First_Name"] . " " . $user_row["Last_Name"] : $user_row["name"];
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
