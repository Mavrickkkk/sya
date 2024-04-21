<?php
session_start();
include("../db/connex.inc.php");
$idcom = connex("myparam");
$id = $_GET["id"];
?>
<html>
<head>
    <title>illustration.</title>
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
<div class="centered totalHeight midHeight">
    <?php
    $request = "SELECT * from illustration WHERE id=$id";
    $result = @mysqli_query($idcom, $request);
    while ($row = mysqli_fetch_assoc($result)) {
        $picFolder = "../db/illustration/";
        $filePic = $row["pic"];
        $nameIllustration = $row["name"];
        $username = $row["username"];
        $description = $row["description"];
        $type = $row["type"];
        $request2 = "SELECT * FROM user WHERE username='$username'";
        $result2 = @mysqli_query($idcom, $request2);
        while ($row2 = mysqli_fetch_assoc($result2)) {
            $name = $row2["name"];
            $soutiens = $row2["soutiens"];
            echo "<div class=\"illustrationContainer lilMarginTop\">";
            echo "<img src=" . $picFolder . $filePic . " class=\"illustration big\">";
            echo "</div></div>";

        }
    }
    ?>
    <div class="centered">
        <div class="formulaire centered block">
            <?php echo "<p class=\"title centerText\">" . $nameIllustration . "</p></br>";
            if ($description != "")
                echo "<p class=\"rightTitle centerText\">$description</p>";
            else
                echo "<p class=\"rightTitle centerText\">Cette illustration ne contient pas de description.</p>";
            ?>
            <div class="centered lilMarginTop">
                <a class="buttonWB text centerText" href="../connect/profile.php?username=<?php echo $username; ?>">
                    <?php echo $name ?>
                    <?php
                    if ($soutiens >= 2)
                        echo "<span class=\"mini\"></span>"
                    ?>
                </a>
            </div>
        </div>
    </div>
        <div class="centered lilMarginTop">
            <a class="buttonBBlue text" href="<?php echo $picFolder . $filePic; ?>" download>Télécharger.</a>
            <?php
            if ((isset($_SESSION["username"]) && $username == $_SESSION["username"]) || $_SESSION["role"] == 3) {
                echo "<a href=\"modifier.php?id=$id\" class=\"buttonRed text\">Modifier.</a>";
            }
            ?>
        </div>
        <?php
        if ($_SESSION["role"] == 3) {
            ?>
            <div class="centered lilMarginTop">
                <a class="buttonBBlue text" href="../db/verifyIllustration.php?id=<?php echo $id ?>">verified.</a>
                <a class="buttonBRed text" href="../db/unmoderateIllustration.php?id=<?php echo $id ?>">unmoderate.</a>
            </div>
            <div class="centered lilMarginTop">
                <a class="buttonBGreen text" href="../db/iotw.php?id=<?php echo $id ?>">Top.</a>
            </div>
            <?php
        }
        ?>
        <div class="marginBottom"></div>
</body>
</html>