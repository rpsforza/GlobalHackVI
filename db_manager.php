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
	if (!$statement) {
		return false;
	} else {
		return $statement->get_result()->fetch_assoc()["id"];
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

	$statement->store_result();
	$result = $statement->fetch_assoc();
	$user_type = $result["user_type"];
	$table_id = $result["table_id"];

	$user_row = $mysqli->query("SELECT * FROM `$user_type` WHERE id=$table_id")->fetch_assoc();
	return $user_row;
}
