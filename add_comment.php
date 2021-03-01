<?php
include "incl/all.php";
session_start();
if(!isset($_POST['id']) || empty($_POST['id']) || !isset($_POST['content']) || empty($_POST['content']))
  exit("TODO: add a better error here");

if(AccountManager::isAccountLoggedIn()){
	if(CommentManager::createAssemblyComment($db, AccountManager::getLoggedinAccountID(), $_POST['id'], $_POST['content']))
		if(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER']))
			header("Location: {$_SERVER['HTTP_REFERER']}");
		else
			header("Location: /");
	else
		echo "Something went wrong";
}
?>