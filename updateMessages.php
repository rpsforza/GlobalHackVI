<?php

require 'db_manager.php';

if(isset($_POST)) {
	echo json_encode(getMessages($_POST["Group"]));
} else {
	echo "Error 9c";
}
?>