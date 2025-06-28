<?php 
require_once("dashboard/includes/config.php");
require_once("dashboard/includes/functions.php");
require_once("templates/theme1/header.php");

// get viewed page from pages folder \\
if( isset($_GET["v"]) && searchFile("views","blade{$_GET["v"]}.php") ){
	require_once("views/".searchFile("views","blade{$_GET["v"]}.php"));
}else{
	require_once("views/bladeHome.php");
}

require("templates/theme1/footer.php");
?>
