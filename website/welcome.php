<?php
session_start();

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
	header('Locaton: thrift.php');
	exit;
}

else {
	header('Location: login.php');
	exit;
}