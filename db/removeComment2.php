<?php
session_start();
if (isset($_SESSION["role"]) && $_SESSION["role"]==2 || $_SESSION["role"]==3) {
    include("../db/connex.inc.php");
    $idcom = connex("myparam");
    $id = $_GET["id"];
    $request = "SELECT * FROM comment WHERE id=$id";
    $result = @mysqli_query($idcom, $request);
    while ($row = mysqli_fetch_assoc($result)) {
        $idIllustration = $row["idIllustration"];
        $request2 = "DELETE FROM comment WHERE id=$id";
        mysqli_query($idcom, $request2);
        header("Location: ../browse/illustration.php?id=$idIllustration");
        exit();
    }
}
else {
    header("Location: ../index.php");
    exit();
}
?>