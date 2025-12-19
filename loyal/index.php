<?php 
require_once("dashboard/includes/config.php");
require_once("dashboard/includes/functions.php");

require_once("template/header.php");

if( isset($_GET["v"]) && searchFile("views","blade{$_GET["v"]}.php") ){
	require_once("views/".searchFile("views","blade{$_GET["v"]}.php"));
}else{
	require_once("views/bladeHome.php");
}

require_once("template/footer.php");
?>
