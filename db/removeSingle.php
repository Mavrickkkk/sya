<?php
session_start();
if (isset($_SESSION["role"]) && $_SESSION["role"]==2 || $_SESSION["role"]==3) {
    include("../db/connex.inc.php");
    $idcom = connex("myparam");
    $id = $_GET["id"];
    $request = "SELECT * FROM single WHERE id=$id";
    $result = @mysqli_query($idcom, $request);
    while ($row = mysqli_fetch_assoc($result)) {
        $fileCoverTmp = $row["fileCover"];
        $fileCover = "../db/cover/" . $fileCoverTmp;
        if (file_exists($fileCover)) {
            unlink($fileCover);
        }
        $fileTrackTmp = $row["fileTrack"];
        $fileTrack = "../db/track/" . $fileTrackTmp;
        if (file_exists($fileTrack)) {
            unlink($fileTrack);
        }
    }
    $request2 = "DELETE FROM single WHERE id=$id";
    mysqli_query($idcom, $request2);
    header('Location: ../index.php');
    exit();
} else {
        header("Location: ../index.php");
        exit();
    }
?>