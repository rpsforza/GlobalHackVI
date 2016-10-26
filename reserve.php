<?php

require 'coc-helper.php';

if (isset($_POST)) {
	if (isset($_POST['userID']) && isset($_POST['cocID']) && isset($_POST['type']) && isset($_POST['cancel'])) {
		$cancel = json_decode($_POST['cancel']);

		if ($cancel) {
			echo cancelReservation(intval($_POST['userID']), $_POST['type'], intval($_POST['cocID']));
		} else {
			echo newReservation(intval($_POST['userID']), $_POST['type'], intval($_POST['cocID']));
		}

		return;
	}

	echo 'failed';
}
