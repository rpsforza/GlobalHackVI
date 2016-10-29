<!-- FOR IMPORT: NAVBAR AND TITLE BAR -->
<!-- To use: define variable $PAGE_TITLE to the title of the page -->

<?php
// order matters: index of element corresponds to place in navbar of that element
$NAV_INDEX = [
	"coc" => [
		"Dashboard", "Profile", "Services", "Housing", "Statistics"
	],
	"host" => [
		"Dashboard", "Profile", "Services", "Housing", "Statistics"
	],
	"client" => [
		"Dashboard", "Profile", "Services", "Housing"
	],
	"clientNoAuth" => [
		"Services", "Housing"
	]
];
$NAV_DATA = [
	"Dashboard" => [
		"icon" => "dashboard",
		"link" => "../dash"
	],
	"Profile" => [
		"icon" => "account_box",
		"link" => "../profile"
	],
	"Services" => [
		"icon" => "domain",
		"link" => "../services"
	],
	"Housing" => [
		"icon" => "home",
		"link" => "../housing"
	],
	"Statistics" => [
		"icon" => "timeline",
		"link" => "../statistics"
	]
]
?>

<!-- make sure to define $PAGE_TITLE before requiring this page
	$PAGE_TITLE should be indentical to one of the options in $NAV_INDEX -->
<header class="demo-header mdl-layout__header mdl-color--grey-100 mdl-color-text--grey-600">
	<div class="mdl-layout__header-row">
		<span class="mdl-layout-title"><?php if (isset($PAGE_TITLE)) echo $PAGE_TITLE; ?></span>
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
					$name = "<a href='../login/' style='color: inherit;'>Login</a>";
				}
				echo $name; ?></span>
			<div class="mdl-layout-spacer"></div>
			<?php if (isset($_SESSION["user_id"])) {
				echo "<button id=\"accbtn\" class=\"mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon\"><i class=\"material-icons\" role=\"presentation\">arrow_drop_down</i><span class=\"visuallyhidden\">Logout</span></button><ul class=\"mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect\" for=\"accbtn\"><li class=\"mdl-menu__item\"><a id=\"logoutbuttonnav\" href=\"../logout/\">Logout</a></li></ul>";
			}
			?>
		</div>
	</header>
	<nav class="demo-navigation mdl-navigation mdl-color--blue-grey-800">
		<?php
		$user_type = isset($_SESSION["user_type"]) ? $_SESSION["user_type"] : "clientNoAuth";
		// see top - one of the arrays: coc, host, client, clientNoAuth
		$nav_list = $NAV_INDEX[$user_type];
		// index of this element in corresponding nav_list
		$nav_list_index = array_search($PAGE_TITLE, $NAV_INDEX[$user_type]);
		foreach ($nav_list as $li) {
			$active = "";
			$link = $NAV_DATA[$li]["link"];
			$icon = $NAV_DATA[$li]["icon"];
			if (isset($PAGE_TITLE) && $li == $PAGE_TITLE) $active = "active-nav ";
			echo "<a href='$link' class='$active mdl-navigation__link'><i class='mdl-color-text--blue-grey-400 material-icons' role='presentation'>$icon</i>$li</a>";
		}
		?>
		<div class="mdl-layout-spacer"></div>
		<a class="mdl-navigation__link" href="../faq/"><i class="mdl-color-text--blue-grey-400 material-icons"
														  role="presentation">help_outline</i><span>FAQ</span></a>
	</nav>
</div>