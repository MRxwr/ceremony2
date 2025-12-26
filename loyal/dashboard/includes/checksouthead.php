<?php
require ("config.php");
require ("translate.php");
require ("functions.php");
if ( isset($_COOKIE[$cookieSession."A"]) && !empty($_COOKIE[$cookieSession."A"]) ){
	session_start ();
	$svdva = $_COOKIE[$cookieSession."A"];
	if ( $user = selectDBNew("employees", [$svdva], "`keepMeAlive` LIKE ? AND `hidden` != '2' AND `status` = '0'", "")){
		$userID = $user[0]["id"];
		$email = $user[0]["email"];
		$username = "{$user[0]["firstName"]} {$user[0]["lastName"]}";
		$empUsername = "{$user[0]["firstName"]} {$user[0]["lastName"]}";
		$userType = $user[0]["empType"];
		$_SESSION[$cookieSession."A"] = $email;	
		$SystemId = $user[0]["systemId"];
	}else{
		header("Location: logout.php");die();
	}
}else{
	header("Location: logout.php");die();
}