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
                        header('Location: ../');
                        exit();
                    } else {
                        $error_message = "<a class='subtitle white centered'>Mot de passe incorrect</a>";
                    }
                }
            } else {
                $error_message = "<a class='subtitle white centered'>Ce nom d'utilisateur n'existe pas</a>";
            }
        } else {
            $error_message = "<a class='subtitle white centered'>Erreur dans la base de donnée</a>";
        }

        mysqli_stmt_close($stmt);
    } else {
        $error_message = "<a class='subtitle white centered'>Veuillez remplir tous les champs</a>";
    }
}
?>

<html>
<head>
    <title>se connecter.</title>
    <link rel="icon" href="../pics/favicon.png"/>
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="../style/styleForm.css">
    <link rel="stylesheet" href="../style/styleMenu.css">
    <script src="../js/hamburger.js"></script>
    <script src="../js/apparition.js"></script>
    <meta charset="utf-8"/>
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
        <p class="title">Me connecter.</p></br>
        <p class="subtitle white">Heureux de vous revoir, vous nous avez manqué(e)</p>
        <form action="login.php" method="POST">
            <p class="marginV textSection">nom d'utilisateur </p> <input class="input" type="text" name="username" required></br>
            <p class="marginV textSection">mot de passe </p> <input class="input" type="password" name="password" required></br>
            <div class="centered"><input class="submitForm text" type="submit" name="submit" value="se connecter"></div>
        </form>
    </div>
</div>
<div class="lilMarginTop">
<?php
if (isset($error_message)) {
    echo $error_message;
}
?>
</div>
<div class="centered lilMarginTop"><a href="register.php" class="subtitle white">je suis nouveau</a></div>
</body>
</html>
