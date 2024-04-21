<?php
session_start();
if (isset($_SESSION["role"]) && $_SESSION["role"]==2 || $_SESSION["role"]==3) {
    include("connex.inc.php");
    $idcom=connex("myparam");

    if (isset($_GET["username"])) {
        $username =$_GET["username"];
        $request = "UPDATE user SET adm=1 WHERE username='$username'";

        if (mysqli_query($idcom, $request)) {
            header("Location: ../connect/profile.php?username=$username");
            exit();
        } else {
            echo "Erreur lors de la mise à jour : " . mysqli_error($idcom);
        }
    } else {
        echo "Nom d'utilisateur non spécifié dans la requête.";
    }
}
else {
    header("Location: ../");
    exit();
}
?>
