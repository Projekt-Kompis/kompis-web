<?php
include "incl/all.php";
session_start();
if(!isset($_POST['username']) || empty($_POST['username']) || !isset($_POST['password']) || empty($_POST['password']))
  exit("TODO: add a better error here");


$accountID = AccountManager::verifyPassword($db, $_POST['username'], $_POST['password']);
if($accountID > 0){
	$_SESSION['accountID'] = $accountID;
	if(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER']))
		header("Location: {$_SERVER['HTTP_REFERER']}");
	else
		header("Location: /");
}else
	switch($accountID){
		case -2:
			echo "username not registered";
			break;
		case -3:
			echo "password bad :((";
			break;
		default:
			echo "something went wrong";
			break;
	}
?>