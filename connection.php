<?php
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "Budgetease";

if(!$con = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname))
{

	die("failed to connect!");
}


if(isset($_POST['logout'])) {
session_start();
session_unset();
session_destroy();
header("Location: signup.php"); 
exit();
}


?>