<?php
include "incl/all.php";
session_start();
$_SESSION['part'] = [];
if(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER']))
	header("Location: {$_SERVER['HTTP_REFERER']}");
else
	header("Location: /");