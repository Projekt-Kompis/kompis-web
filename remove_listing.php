<?php
include "incl/all.php";
session_start();
if(!isset($_GET['type']) || empty($_GET['type']))
  exit("TODO: add a better error here");

$_SESSION["part_{$_GET['type']}"] = "";
header("Location: create_assembly.php");
?>