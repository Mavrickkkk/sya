<?php
session_start();
if (isset($_SESSION["role"]) && $_SESSION["role"]==2 || $_SESSION["role"]==3) {
    session_start();
    include("../db/connex.inc.php");
    $idcom = connex("myparam");

    if (isset($_GET["username"])) {
        $usernameToDelete = $_GET["username"];

        $checkUserQuery = "SELECT * FROM user WHERE username='$usernameToDelete'";
        $checkUserResult = mysqli_query($idcom, $checkUserQuery);

        if (mysqli_num_rows($checkUserResult) > 0) {
            $deleteUserQuery = "DELETE FROM user WHERE username='$usernameToDelete'";
            $deleteUserResult = mysqli_query($idcom, $deleteUserQuery);
            if ($deleteUserResult) {
                header("Location: ../moderate/user.php");
                exit();
            }
        }
    }
}
else {
    header("Location: ../index.php");
    exit();
}
?>
