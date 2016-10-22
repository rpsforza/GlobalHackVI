<?php

require 'db_manager.php';


if (isset($_SESSION["user"])) {
	header('Location: /dash'); 
} else {
	header('Location: /login'); 
}

?>