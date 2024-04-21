<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include("../db/connex.inc.php");
$idcom = connex("myparam");
$username = $_GET["username"];
$id = $_GET["id"];
if ((isset($_SESSION["role"]) && $_SESSION["role"]==2 || $_SESSION["role"]==3) || $username==$_SESSION["username"]) {

    $deletePostQuery = "DELETE FROM post WHERE id='$id'";
    $deletePostResult = mysqli_query($idcom, $deletePostQuery);
    if ($deletePostResult) {
        header("Location: ../connect/profile.php?username=$username");
        exit();
    }
}
else {
    header("Location: ../index.php");
    exit();
}
?>
