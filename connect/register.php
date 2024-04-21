<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include("../db/connex.inc.php");
$idcom = connex("myparam");
if (isset ($_POST["submit"])) {
    $username = strtolower($_POST["username"]);
    $name = $_POST["name"];
    $passwordUs = password_hash($_POST["password"], PASSWORD_DEFAULT);

    if (!preg_match("/^[A-Za-z]+$/", $username)) {
        $error_message = "<a class='subtitle white centered'>l'identifiant ne peut contenir que des lettres</a>";
    } else {
        if (isset($_POST["submit"])) {
            $recaptchaResponse = $_POST['g-recaptcha-response'];

            $recaptchaSecretKey = "6Lfc3VApAAAAAF8Ev1vXIiivUGGDvuIOusqvwj1Z";
            $recaptchaVerifyUrl = "https://www.google.com/recaptcha/api/siteverify?secret=$recaptchaSecretKey&response=$recaptchaResponse";
            $recaptchaResponseData = json_decode(file_get_contents($recaptchaVerifyUrl));

            if (!$recaptchaResponseData->success) {
                $error_message = "<a class='subtitle white centered'>erreur de vérification du captcha</a>";
                exit();
            } else {
                $checkUsernameQuery = "SELECT * FROM user WHERE username = ?";
                $checkUsernameStmt = mysqli_prepare($idcom, $checkUsernameQuery);
                mysqli_stmt_bind_param($checkUsernameStmt, "s", $username);
                mysqli_stmt_execute($checkUsernameStmt);
                mysqli_stmt_store_result($checkUsernameStmt);

                if (mysqli_stmt_num_rows($checkUsernameStmt) > 0) {
                    $error_message = "<a class='subtitle white centered'>'$username' existe déjà</a>";
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
                        $error_message = "<a class='subtitle white centered'>problème serveur, veuillez réessayer ultérieurement</a>";
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
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="../style/styleForm.css">
    <link rel="stylesheet" href="../style/styleMenu.css">
    <script src="../js/hamburger.js"></script>
    <script src="../js/apparition.js"></script>
    <meta charset="utf-8"/>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
<div id="menuToggle">
    <input type="checkbox"/>
    <span></span>
    <span></span>
</div>
<div id="menu">
    <a class="titleSecond" href="../">menu.</a>
    <p class="noMargin"> retourner au menu </p>
    <?php
    if (isset($_SESSION["username"]) && $_SESSION["username"] != "") {
        ?>
        <a class="titleSecond" href="../connect/profile.php?username=<?php echo $_SESSION["username"]; ?>">mon profil.</a>
        <p class="noMargin"> connecté en tant que <?php echo $_SESSION["name"]; ?> </p>
        <?php
    } else {
        ?>
        <a class="titleSecond" href="../connect/login.php">mon profil.</a>
        <p class="noMargin"> me connecter </p>
        <?php
    }
    ?>
    <a class="titleSecond" href="../add/send.php">ajouter.</a>
    <p class="noMargin"> envoyer votre illustration </p>
    <a class="titleSecond" href="../browse/verified.php">parcourir.</a>
    <p class="noMargin"> explorer le meilleur de SYA </p>
    <a class="titleSecond" href="../soutenir/soutiens.php">soutenir.</a>
    <p class="noMargin"> obtenir les dernières fonctionnalités </p>
    <?php
    if (isset($_SESSION["role"]) && $_SESSION["role"]==2 || $_SESSION["role"]==3) {
        ?>
        <a class="titleSecond" href="../soutenir/soutiens.php">unmoderate.</a>
        <p class="noMargin"> les illustrations non modérées </p>
        <a class="titleSecond" href="../moderate/user.php">modération.</a>
        <p class="noMargin">gestion des utilisateurs </p>
        <?php
    }
    ?>
</div>
<div class="centered">
    <div class="formulaire">
        <p class="title">Créer un compte.</p></br>
        <p class="subtitle white">Ravis de vous accueillir</p>
        <form action="register.php" method="POST">
            <p class="marginV textSection">identifiant</p>
            <input class="input" type="text" name="username" minlength="3" maxlength="25" pattern="[A-Za-z]+"
                   title="Only letters are allowed" required></br>
            <p class="marginV textSection">nom</p>
            <input class="input" type="text" name="name" minlength="3" maxlength="25" required></br>
            <p class="marginV textSection">mot de passe</p>
            <input class="input" type="password" name="password" minlength="3" maxlength="60" required></br>
            <div class="g-recaptcha lilMarginTop centered" data-sitekey="6Lfc3VApAAAAABcT3XdJXHL0mbbc8EuxM5HJpzt1"></div>
            <div class="centered"><input class="submitForm text" type="submit" name="submit" value="s'inscrire"></div>
        </form>
    </div>
</div>
<div class="lilMarginTop">
    <?php if (isset($error_message)) {
        echo $error_message;
    } ?>
</div>
<div class="centered lilMarginTop marginBottom"><a href="login.php" class="subtitle white">j'ai déjà un compte</a></div>
</body>
</html>
</html>