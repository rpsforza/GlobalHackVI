<?php

require 'db_manager.php';

if(isset($_POST["GroupID"]) && isset($_POST["Message"]) && isset($_POST["UserID"])) {
	sendMessage($_POST["GroupID"], $_POST["Message"], time(), $_POST["UserID"]);
}

?>