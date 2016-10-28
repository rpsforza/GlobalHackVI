<!doctype html>
<?php

require '../db_manager.php';

if (isset($_SESSION["user_id"])) {

} else {
	header('Location: ../login');
}

?>
<html lang="en">
<head>
	<?php require "../header.php"; ?>
</head>
<body>
	<div class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
		<header class="demo-header mdl-layout__header mdl-color--grey-100 mdl-color-text--grey-600">
			<div class="mdl-layout__header-row">
				<span class="mdl-layout-title">Frequently Asked Quesitons</span>
				<div class="mdl-layout-spacer"></div>

				<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" id="hdrbtn">
					<i class="material-icons">more_vert</i>
				</button>
				<ul class="mdl-menu mdl-js-menu mdl-js-ripple-effect mdl-menu--bottom-right" for="hdrbtn">
					<li class="mdl-menu__item">About</li>
					<li class="mdl-menu__item">Contact</li>
					<li class="mdl-menu__item">Legal information</li>
				</ul>
			</div>
		</header>
		<div class="demo-drawer mdl-layout__drawer mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
			<header class="demo-drawer-header">
				<img id="logoname" src="../img/name2.png"/>
				<div class="demo-avatar-dropdown">
					<span><?php if (isset($_SESSION["user_id"])) {
							$name = getUsersName($_SESSION["user_id"]);
						} else {
							$name = "<a href=\"../login/\">Login</a>";
						}
						echo $name; ?></span>
					<div class="mdl-layout-spacer"></div>
					<?php if (isset($_SESSION["user_id"])) {
						echo "<button id=\"accbtn\" class=\"mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon\"><i class=\"material-icons\" role=\"presentation\">arrow_drop_down</i><span class=\"visuallyhidden\">Logout</span></button><ul class=\"mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect\" for=\"accbtn\"><li class=\"mdl-menu__item\"><a id=\"logoutbuttonnav\" href=\"../logout/\">Logout</a></li></ul>";
					}

					?>
				</div>
			</header>
			<?php
			if (isset($_SESSION["user_id"])) {
				$userType = getUserType($_SESSION["user_id"]);
			} else {
				$userType = "clientNoAuth";
			}
			switch ($userType) { // [alt text, mdl font icon, current page]
				case "clientNoAuth":
					$a = [["services", "domain", false], ["housing", "home", false]];
					break;
				case "client":
					$a = [["dash", "dashboard", false], ["profile", "account_box", false], ["services", "domain", false], ["housing", "home", false]];
					break;
				case "coc":
					$a = [["dash", "dashboard", false], ["profile", "account_box", false], ["services", "domain", false], ["housing", "home", false], ["availability", "people", false], ["statistics", "timeline", false]];
					break;
				case "host":
					$a = [["dash", "dashboard", false], ["profile", "account_box", false], ["services", "domain", false], ["availability", "people", false], ["statistics", "timeline", false]];
					break;
				default:
					$a = [["dash", "dashboard", false], ["services", "domain", false], ["housing", "home", false]];
					break;
			}

			?>
			<nav class="demo-navigation mdl-navigation mdl-color--blue-grey-800">
				<?php
				foreach ($a as $arr) {
					$active = "";
					if ($arr[2]) $active = "active-nav ";
					echo "<a href=../" . $arr[0] . " class=\"" . $active . "mdl-navigation__link\"><i class=\"mdl-color-text--blue-grey-400 material-icons\" role=\"presentation\">" . $arr[1] . "</i>" . ucwords($arr[0]) . "</a>";
				}
				?>
				<div class="mdl-layout-spacer"></div>
				<a class="active-nav mdl-navigation__link" href="../faq/"><i
						class="mdl-color-text--blue-grey-400 material-icons"
						role="presentation">help_outline</i><span>FAQ</span></a>
			</nav>
		</div>
		<main class="mdl-layout__content mdl-color--grey-100">
			<div class="mdl-grid">

			</div>
		</main>
	</div>
	<script src="https://code.getmdl.io/1.1.3/material.min.js"></script>
</body>
</html>
