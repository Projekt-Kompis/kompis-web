<?php
include "incl/all.php";
session_start();
if(!isset($_GET['id']) || empty($_GET['id']) || !isset($_GET['points']) || empty($_GET['points']))
  exit("TODO: add a better error here");

if(AccountManager::isAccountLoggedIn() && AssemblyManager::rateAssembly($db, $_GET['points'], $_GET['id'], AccountManager::getLoggedinAccountID()) > 0)
	if(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER']))
		header("Location: {$_SERVER['HTTP_REFERER']}");
	else
		header("Location: /");
else
	echo "Something went wrong";
?>