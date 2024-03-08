<?php
session_start();
if (isset($_SESSION["role"]) && $_SESSION["role"]==2 || $_SESSION["role"]==3) {
    include("../db/connex.inc.php");
    $idcom = connex("myparam");
    $id = $_GET["id"];
    $request = "SELECT * FROM illustration WHERE id=$id";
    $result = @mysqli_query($idcom, $request);
    while ($row = mysqli_fetch_assoc($result)) {
        $fileCoverTmp = $row["pic"];
        $fileCover = "../db/illustration/" . $fileCoverTmp;
        if (file_exists($fileCover)) {
            unlink($fileCover);
        }
    }
    $request2 = "DELETE FROM illustration WHERE id=$id";
    mysqli_query($idcom, $request2);
    header('Location: ../index.php');
    exit();
} else {
    header("Location: ../index.php");
    exit();
}
?>