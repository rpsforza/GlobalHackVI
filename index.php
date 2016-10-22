<?php

require 'db_manager.php';


if (isset($_SESSION["user_id"])) {
	header('Location: /dash');
} else {
	header('Location: /login');
}
