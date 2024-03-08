<?php
session_start();
if (isset($_SESSION["role"]) && $_SESSION["role"]==2 || $_SESSION["role"]==3) {
include("connex.inc.php");
$idcom = connex("myparam");

if (isset($_GET["username"])) {
    $username = mysqli_real_escape_string($idcom, $_GET["username"]);
    $request = "UPDATE user SET role=1 WHERE username='$username'";

    if (mysqli_query($idcom, $request)) {
        header('Location: ../moderate/user.php');
        exit();
    } else {
        echo "Erreur lors de la mise à jour : " . mysqli_error($idcom);
    }
} else {
    echo "Nom d'utilisateur non spécifié dans la requête.";
}
}
else {
    header("Location: ../index.php");
    exit();
}
?>