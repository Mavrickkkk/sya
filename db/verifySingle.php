<?php
session_start();
if (isset($_SESSION["role"]) && $_SESSION["role"]==2 || $_SESSION["role"]==3) {
    include("connex.inc.php");
    $idcom=connex("myparam");
    $id=$_GET["id"];
    $request="UPDATE single SET type=2 WHERE id=$id";
    mysqli_query($idcom, $request);
    header('Location: ../browse/verified.php?type=single');
    exit();
}
else {
    header("Location: ../index.php");
    exit();
}
?>