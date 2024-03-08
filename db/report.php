<?php
session_start();
include("../db/connex.inc.php");
$idcom=connex("myparam");
$type=$_GET["type"];
$request = "INSERT INTO report (type) VALUES (\"$type\")";
$result = @mysqli_query($idcom, $request);
if (mysqli_query($idcom, $request)) {
    header('Location: ../index.php');
    exit();
}
?>