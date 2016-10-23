<?php

require 'coc-helper.php';

if (isset($_POST)) {
	if (isset($_POST['userID']) && isset($_POST['cocID']) && isset($_POST['type']) && isset($_POST['cancel'])) {
		$cancel = json_decode($_POST['cancel']);
		if ($cancel) {
			return;
		} else {
			newReservation($_POST['userID'], $_POST['type'], $_POST['cocID']);
			echo 'true';
			return;
		}
	}

	echo 'false';
	return;
}
