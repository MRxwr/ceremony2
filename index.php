<?php 
require_once("dashboard/includes/config.php");
require_once("dashboard/includes/functions.php");
if( isset($_REQUEST["systemCode"]) && !empty($_REQUEST["systemCode"]) && $vendor = selectDBNew("events",[$_REQUEST["systemCode"]],"`code` LIKE ? AND `hidden` = '0' AND `status` = '0'","") ){
	$systemCode = $_REQUEST["systemCode"];
}else{
	header("Location: default.php");die();
}

require_once("templates/theme1/header.php");

if( isset($_GET["v"]) && searchFile("views","blade{$_GET["v"]}.php") ){
	require_once("views/".searchFile("views","blade{$_GET["v"]}.php"));
}else{
	require_once("views/bladeHome.php");
}

//require("templates/theme1/footer.php");
?>
