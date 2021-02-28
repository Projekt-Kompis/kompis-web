<?php
include "incl/all.php";
session_start();
if(!isset($_GET['id']) || empty($_GET['id']))
  exit("TODO: add a better error here");

$key = array_search($_GET['id'], $_SESSION['part']);
if(!($key === false))
	unset($_SESSION['part'][$key]);

header("Location: create_assembly.php");
?>