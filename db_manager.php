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

// Users, General User Actions, Get User Information\

// TODO MYSQL SHA1 for Password Encrption
function addUser($firstname, $lastname, $email, $dateofbirth, $ssn, $username, $password)
{
	$mysqli = getDB();

	$insert = $mysqli->prepare("INSERT INTO users (ID, FirstName, LastName, Email, DateOfBirth, SSN, Username, Password) VALUES (?,?,?,?,?,?,?,?)");

	$insert->bind_param('isssssss', $x = 0, $firstname, $lastname, $email, $dateofbirth, $ssn, $username, $password);
	$insert->execute();
}

function getUser($Identifier, $Method)
{
	$mysqli = getDB();

	switch ($Method) {
		case 0: // By User ID
			$statement = $mysqli->prepare("SELECT * FROM users WHERE ID='$Identifier'");
			break;
		case 1: // By Email Address
			$statement = $mysqli->prepare("SELECT * FROM users WHERE Email='$Identifier'");
			break;
		case 2: // By Username
			$statement = $mysqli->prepare("SELECT * FROM users WHERE Username='$Identifier'");
			break;
		default:
			$statement = $mysqli->prepare("SELECT * FROM users WHERE Username='$Identifier'");
	}

	$statement->execute();
	$result = $statement->get_result();
	$resultArray = $result->fetch_all(MYSQLI_NUM);

	return $resultArray;
}

function validateUser($Email, $Password)
{
	if (getUser($Email, 1)[0][7] == $Password) {
		return true;
	} else {
		return false;
	}
}

function getUsersName($Identifier)
{
	$x = getUser($Identifier, 0);
	return $x[0][1] . " " . $x[0][2];
}
