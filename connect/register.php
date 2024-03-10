<?php
session_start();
include("../db/connex.inc.php");
$idcom = connex("myparam");
if (isset ($_POST["submit"])) {
    $username = strtolower($_POST["username"]);
    $name = $_POST["name"];
    $passwordUs = password_hash($_POST["password"], PASSWORD_DEFAULT);

    if (!preg_match("/^[A-Za-z]+$/", $username)) {
        echo "<a class='error-message'>Username must contain only letters</a>";
    } else {
        if (isset($_POST["submit"])) {
            $recaptchaResponse = $_POST['g-recaptcha-response'];

            // Vérifier le reCAPTCHA côté serveur
            $recaptchaSecretKey = "6Lfc3VApAAAAAF8Ev1vXIiivUGGDvuIOusqvwj1Z";
            $recaptchaVerifyUrl = "https://www.google.com/recaptcha/api/siteverify?secret=$recaptchaSecretKey&response=$recaptchaResponse";
            $recaptchaResponseData = json_decode(file_get_contents($recaptchaVerifyUrl));

            if (!$recaptchaResponseData->success) {
                echo "<a class='error-message'>reCAPTCHA verification failed</a>";
                exit();
            } else {
                $checkUsernameQuery = "SELECT * FROM user WHERE username = ?";
                $checkUsernameStmt = mysqli_prepare($idcom, $checkUsernameQuery);
                mysqli_stmt_bind_param($checkUsernameStmt, "s", $username);
                mysqli_stmt_execute($checkUsernameStmt);
                mysqli_stmt_store_result($checkUsernameStmt);

                if (mysqli_stmt_num_rows($checkUsernameStmt) > 0) {
                    echo "<a class='error-message'>'$username' already exists</a>";
                } else {
                    $insertUserQuery = "INSERT INTO user (username, name, password, role) VALUES (?, ?, ?, 1)";
                    $insertUserStmt = mysqli_prepare($idcom, $insertUserQuery);
                    mysqli_stmt_bind_param($insertUserStmt, "sss", $username, $name, $passwordUs);

                    if (mysqli_stmt_execute($insertUserStmt)) {
                        $_SESSION["username"] = $username;
                        $_SESSION["name"] = $name;
                        $_SESSION["role"] = 1;
                        header('Location: ../index.php');
                        exit();
                    } else {
                        echo "<a class='error-message'>Error adding to the database</a>";
                    }
                }
            }
        }

        mysqli_stmt_close($checkUsernameStmt);
        mysqli_stmt_close($insertUserStmt);
    }
}
?>

<html>
<head>
    <title>créer un compte.</title>
    <link rel="icon" href="../pics/favicon.png"/>
    <link rel="stylesheet" href="../style/styleConnect.css">
    <meta charset="utf-8"/>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
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
            if (!isset($_SESSION["username"])) {
                ?>
                <?php
            } else {
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
<div class="textCenter"><a href="login.php" class="new">j'ai déjà un compte</a></div>
<div class="centered-container">
    <form action="register.php" method="POST">
        <a>identifiant (doit être unique) : </a><input class="search" type="text" name="username" minlength="3" maxlength="25" pattern="[A-Za-z]+" title="Only letters are allowed" required></br>
        <a>nom :</a> <input class="search" type="text" name="name" minlength="3" maxlength="25" required></br>
        <a>mot de passe :</a> <input class="search" type="password" name="password" minlength="3" maxlength="60" required></br>
        <div class="g-recaptcha" data-sitekey="6Lfc3VApAAAAABcT3XdJXHL0mbbc8EuxM5HJpzt1"></div>
        <div class="submitButton"><input class="sendButton" type="submit" name="submit" value="submit"></div>
    </form>
</div>
</body>
</html>