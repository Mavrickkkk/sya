<?php
session_start();
include("../db/connex.inc.php");
$idcom = connex("myparam");
if (isset($_POST["submit"])) {
    if (!empty($_POST["username"]) && !empty($_POST["password"])) {
        $username = $_POST["username"];
        $passwordUs = $_POST["password"];

        $stmt = mysqli_prepare($idcom, "SELECT * FROM user WHERE username=?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);

        if ($result = mysqli_stmt_get_result($stmt)) {
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    if (password_verify($passwordUs, $row["password"])) {
                        $_SESSION["username"] = $row["username"];
                        $_SESSION["name"] = $row["name"];
                        $_SESSION["role"] = $row["role"];
                        header('Location: ../index.php');
                        exit();
                    } else {
                        echo "<a class='error-message'>Incorrect password</a>";
                    }
                }
            } else {
                echo "<a class='error-message'>Username does not exist</a>";
            }
        } else {
            echo "<a class='error-message'>Database error</a>";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "<a class='error-message'>Please complete all fields</a>";
    }
}
?>

<html>
<head>
    <title>se connecter.</title>
    <link rel="icon" href="../pics/favicon.png"/>
    <link rel="stylesheet" href="../style/styleConnect.css">
    <meta charset="utf-8"/>
</head>
<body>
<div class="sticky-bar">
    <div class="bar-content">
        <a href="../index.php"></a>
        <ul class="bar-links">
            <li><a class="links">cette page est en travaux</a></li>
            <li><a href="../browse/verified.php" class="links">parcourir</a></li>
            <li><a href="../" class="links">menu</a></li>
            <?php
            if (!isset($_SESSION["username"])){
                ?>
                <?php
            }else {
                ?>
                <li><a class="links">quoi</a></li>
                <?php
            }
            ?>
        </ul>
    </div>
</div>
<div class="logoContainer">
    <a href="../index.php"><img src="../pics/logo4.png" class="logo"></a>
</div>
<div class="textCenter"><a href="register.php" class="new">je suis nouveau</a></div>
<div class="centered-container">
    <form action="login.php" method="POST">
        <a>nom d'utilisateur :</a> <input class="search" type="text" name="username"></br>
        <a>mot de passe :</a> <input class="search" type="password" name="password"></br>
        <div class="submitButton"><input class="sendButton" type="submit" name="submit" value="se connecter"></div>
    </form>
</div>
</body>
</html>
