<?php
session_start();
if (isset($_SESSION["role"]) && $_SESSION["role"]==2 || $_SESSION["role"]==3) {
    include("connex.inc.php");
    $idcom=connex("myparam");
    $id=$_GET["id"];
    $request="UPDATE illustration SET type=3 WHERE id=$id";
    mysqli_query($idcom, $request);
    header('Location: ../index.php');
    exit();
}
else {
    header("Location: ../index.php");
    exit();
}
?>