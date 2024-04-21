<?php
session_start();
include("../db/connex.inc.php");
$idcom = connex("myparam");
?>
<html>
<head>
    <title>sya - moderation</title>
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
        <form action="user.php" method="POST">
            <div class="centered">
                <input class="input" type="text" name="name" placeholder="username">
            </div>
            <div class="centered marginBottom">
            <input class="submitForm text" type="submit" value="send">
            </div>
        </form>
        <?php
        if (isset($_SESSION["role"]) && $_SESSION["role"] == 2 || $_SESSION["role"] == 3) {
            if (isset($_POST["name"])) {
                $search = '%' . $_POST["name"] . '%';
                $requestSearch = "SELECT * from user WHERE username LIKE ?";
                $stmt = mysqli_prepare($idcom, $requestSearch);
                mysqli_stmt_bind_param($stmt, "s", $search);
                mysqli_stmt_execute($stmt);
                $resultSearch = mysqli_stmt_get_result($stmt);
            } else {
                $requestSearch = "SELECT * from user";
                $resultSearch = @mysqli_query($idcom, $requestSearch);
            }

            if ($resultSearch) {
                while ($rowSearch = mysqli_fetch_assoc($resultSearch)) {
                    $username = htmlspecialchars($rowSearch["username"]);
                    $role = $rowSearch["role"];
                    echo "<div class=\"centered\">";
                    echo "<a class=\"subtitle white\" href=\"../connect/profile.php?username=$username\">" . $username . "</a>";
                    if ($role == 1)
                        echo "<a class=\"subtitle\"> (user)</a>";
                    if ($role == 2)
                        echo "<a class=\"subtitle\"> (modo)</a>";
                    if ($role == 3)
                        echo "<a class=\"subtitle\"> (owner)</a>";
                    echo "<a class='buttonBRed subtitle' href=\"../db/removeUser.php?username=" . $username . "\">supprimer</a>";
                    echo "<a class='buttonBBlue subtitle' href=\"../db/modoUser.php?username=" . $username . "\">modo</a>";
                    echo "<a class='buttonBGreen subtitle' href=\"../db/unmodoUser.php?username=" . $username . "\">unmodo</a></br>";
                    echo "</div>";
                }
            } else {
                echo "<p>Error executing the search query.</p>";
            }
            $requestVisitors = "SELECT visitors from month";
            $resultVisitors = @mysqli_query($idcom, $requestVisitors);
            while ($rowVisitor = mysqli_fetch_assoc($resultVisitors)) {
                echo "<div class=\"centered lilMarginTop\">";
                echo "<a class='subtitle'>".$rowVisitor["visitors"]." visiteurs</a>";
                echo "</div>";
            }
        } else {
            echo "you can't be there";
        }
        ?>
    </div>
</div>
</body>
</html>
