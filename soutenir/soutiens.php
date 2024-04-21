<?php
session_start();
include("../db/connex.inc.php");
$idcom = connex("myparam");
$requestmonth = "SELECT * from month WHERE month=0";
$resultmonth = @mysqli_query($idcom, $requestmonth);
while ($rowmonth = mysqli_fetch_assoc($resultmonth)) {
    $month = $rowmonth["month"];
}

$requestUsers = "SELECT * from user";
$resultUsers = @mysqli_query($idcom, $requestUsers);

if (isset($_POST["submit"])) {
    $username = $_POST["username"];
    $soutiens = $_POST["soutiens"];

    $stmt = mysqli_prepare($idcom, "UPDATE user SET soutiens=? WHERE username=?");
    mysqli_stmt_bind_param($stmt, "is", $soutiens, $username);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: ./soutiens.php");
        exit();
    } else {
        echo "Erreur lors de la mise à jour de l'utilisateur.";
    }
}
?>
<html>
<head>
    <title>soutiens.</title>
    <meta charset="utf-8"/>
    <meta name="description" content="Partie vérifiée de SYA, trouvez vos prochaines illustrations préférés."/>
    <link rel="icon" href="../pics/favicon.png"/>
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="../style/styleForm.css">
    <link rel="stylesheet" href="../style/styleMenu.css">
    <script src="../js/hamburger.js"></script>
    <script src="../js/apparition.js"></script>
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
    <a class="titleSecond" href="soutiens.php">soutenir.</a>
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
<div class="centered lilMarginTop marginBottom">
    <a class="donateButton" href="https://www.paypal.com/donate/?hosted_button_id=8XFJ7GKL2Y53W">faire un don</a>
</div>
<div class="centered lilMarginTop marginBottom">
    <a class="rightTitle">n'oubliez pas d'indiquer votre identifiant sur le don</a>
</div>
<div class="left lilMarginTop">
    <a class="title">nos contributeurs.</a>
</div>
<div class="left lilMarginTop">
    <?php
    $request = "SELECT * from user WHERE soutiens>=1";
    $result = @mysqli_query($idcom, $request);
    while ($rowSearch = mysqli_fetch_assoc($result)) {
        $username = $rowSearch["username"];
        $name = $rowSearch["name"];
        echo "<a class=\"buttonWB text\" href=\"../connect/profile.php?username=$username\">$name</a>";
    }
    ?>
</div>
<?php
if ($_SESSION["role"] == 3) {
    ?>
    <div class="centered">
        <div class="formulaire">
            <a class="white title">ajouter.</a>

            <div class="left lilMarginTop">
                <form action="soutiens.php" method="post">
                    <div class="form-group lilMarginTop white">
                        <label for="username">Nom d'utilisateur</label>
                        <select class="input" name="username" id="username" required>
                            <option value="">-- Sélectionnez un utilisateur --</option>
                            <?php
                            while ($rowUser = mysqli_fetch_assoc($resultUsers)) {
                                $username = htmlspecialchars($rowUser["username"]);
                                echo "<option value=\"$username\">$username</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group lilMarginTop white">
                        <label for="soutiens">soutiens</label>
                        <input class="input" type="number" name="soutiens" id="soutiens" required>
                    </div>
                    <div class="form-group centered lilMarginTop">
                        <button class="submitForm text" type="submit" name="submit">Mettre à jour</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php
}
?>
<div class="left marginTop">
    <a class="title">objectifs.</a>
</div>
<div class="left lilMarginTop">
    <a class="rightTitle">afficher vos illustrations dans la rue</a>
</div>
<div class="left lilMarginTop">
    <a class="rightTitle">financer une application mobile</a>
</div>
<div class="left lilMarginTop">
    <a class="rightTitle">financer un serveur dédié à sya pour être indépendant et pouvois améliorer les technologies du
        site</a>
</div>
<div class="left lilMarginTop">
    <a class="rightTitle">création de concepts sous forme de vidéos</a>
</div>
<div class="left marginTop">
    <a class="title">récompenses.</a>
    <a class="rightTitle">à partir de 2€</a>
</div>
<div class="left lilMarginTop">
    <a class="rightTitle">la possibilité d'ajouter vos réseaux sur votre profil</a>
</div>
<div class="left lilMarginTop">
    <a class="rightTitle">ce joli petit coeur qui accompagnera votre nom comme ceci :</a>
</div>
<div class="centered lilMarginTop marginBottom">
    <a class="buttonWB text">
        Mavrick
        <span class="mini"></span>
    </a>
</div>
<div class="lilMarginTop">
    <a class="centered subtitle">merci. je vous aimes</a>
</div>
</body>
</html>