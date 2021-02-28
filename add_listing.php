<?php
include "incl/all.php";
session_start();
if(!isset($_GET['id']) || empty($_GET['id']))
  exit("TODO: add a better error here");

$_SESSION['part'][] = $_GET['id'];
header("Location: create_assembly.php");
?>