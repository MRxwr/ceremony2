<?php 
require_once("dashboard/includes/config.php");
require_once("dashboard/includes/functions.php");
if( isset($_REQUEST["systemCode"]) && !empty($_REQUEST["systemCode"]) && $event = selectDBNew("events",[$_REQUEST["systemCode"]],"`code` LIKE ? AND `hidden` = '0' AND `status` = '0'","") ){
	$systemCode = $_REQUEST["systemCode"];
	$event = $event[0];
	$category = selectDB("categories","`id` = '{$event["categoryId"]}'");
	if( isset($_GET["i"]) && !empty($_GET["i"]) && $invitee = selectDBNew("invitees",[$_GET["i"]],"`code` LIKE ? AND `eventId` = '{$event["id"]}'","") ){
	}else{
		//header("Location: default.php");die();
	}
}else{
	header("Location: default.php");die();
}

require_once("templates/theme1/header.php");

if( isset($_GET["v"]) && searchFile("views","blade{$_GET["v"]}.php") ){
	require_once("views/".searchFile("views","blade{$_GET["v"]}.php"));
}else{
	require_once("views/bladeHome.php");
}
?>
