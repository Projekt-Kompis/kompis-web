<?php
include "incl/all.php";
session_start();
if(!isset($_GET['id']) || empty($_GET['id']))
  exit("TODO: add a better error here");

$type = MainLib::getListingType($db, $_GET['id']);

$_SESSION["part_{$type}"] = $_GET['id'];
header("Location: create_assembly.php");
?>