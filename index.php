<?php 
require_once("dashboard/config.php");
require_once("dashboard/functions.php");
require_once("templates/theme1/header.php");

// get viewed page from pages folder \\
if( isset($_GET["v"]) && searchFile("views","blade{$_GET["v"]}.php") ){
	require_once("views/".searchFile("views","blade{$_GET["v"]}.php"));
}else{
	require_once("views/bladeHome.php");
}

require("templates/theme1/footer.php");
?>
