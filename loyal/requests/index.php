<?php 
require_once("../dashboard/includes/config.php");
require_once("../dashboard/includes/functions.php");

if( isset($_GET["a"]) && searchFile("views","api{$_GET["a"]}.php") ){
	require_once("views/".searchFile("views","api{$_GET["a"]}.php"));
}else{
	require_once("views/apiHome.php");
}

?>
