<?php
session_start();
include("../db/connex.inc.php");
$idcom = connex("myparam");
$username = $_GET["username"];

$requestUser = "SELECT * FROM user WHERE username=?";
$stmt = mysqli_prepare($idcom, $requestUser);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$resultUser = mysqli_stmt_get_result($stmt);

if ($resultUser) {
    $rowUser = mysqli_fetch_assoc($resultUser);
    $name = $rowUser["name"];
    $soutiens = $rowUser["soutiens"];
    $profilePic = $rowUser["profilePicPath"];
    $adm = $rowUser["adm"];
    if ($soutiens >= 1) {
        $insta = $rowUser["instagram"];
        $youtube = $rowUser["youtube"];
    } else {
        $insta = "";
        $youtube = "";
    }
} else {
    echo "SQL Error";
    exit();
}

if (isset ($_POST["submitPost"])) {
    $text = $_POST["text"];
    $insertPostQuery = "INSERT INTO post (text, username, date) VALUES (?, ?, NOW())";
    $insertPostStmt = mysqli_prepare($idcom, $insertPostQuery);
    mysqli_stmt_bind_param($insertPostStmt, "ss", $text, $username);

    if (mysqli_stmt_execute($insertPostStmt)) {
    } else {
        $error_message = "<a class='subtitle white centered'>problème serveur, veuillez réessayer ultérieurement</a>";
    }
}
?>
<html>
<head>
    <title>profil.</title>
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
        <a class="titleSecond" href="../connect/profile.php?username=<?php echo $_SESSION["username"]; ?>">mon
            profil.</a>
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
    if (isset($_SESSION["role"]) && $_SESSION["role"] == 2 || $_SESSION["role"] == 3) {
        ?>
        <a class="titleSecond" href="../soutenir/soutiens.php">unmoderate.</a>
        <p class="noMargin"> les illustrations non modérées </p>
        <a class="titleSecond" href="../moderate/user.php">modération.</a>
        <p class="noMargin">gestion des utilisateurs </p>
        <?php
    }
    ?>
</div>
<?php
if ($soutiens < 5) {
    ?>
    <div class="marginTop animate-on-scroll">
        <a class="title left"><?php echo $name ?>.</a>
        <a class="rightTitle">mon profil.</a>
    </div>

    <div class="centered animate-on-scroll">
        <div class="tiffany"></div>
    </div>
    <div class="centered animate-on-scroll">
        <div class="tiffany2"></div>
    </div>
    <div class="centered animate-on-scroll">
        <div class="tiffany3"></div>
    </div>
    <div class="left lilMarginTop animate-on-scroll">
        <?php
        if ($username == $_SESSION["username"]) { ?>
            <a class="buttonRed backTiffany text" href="modifier.php?username=<?php echo $username ?>">modifier.</a>
            <a class="buttonWB text" href="disconnect.php">deconnexion</a>
            <?php
        }
        if ($youtube != "" || $insta != "") {
            ?>
            <a class="buttonWB text" href="https://www.instagram.com/<?php echo $insta ?>">instagram</a>
            <a class="buttonWB text" href="https://youtube.com/<?php echo $youtube ?>">youtube</a>
            <?php
        }
        ?>
        <?php
        if ($soutiens >= 2) {
            ?>
            <a class="buttonWB text">
                soutiens
                <span class="mini"></span>
            </a>
            <?php
        }
        if (isset($_SESSION["role"]) && $_SESSION["role"] == 2 || $_SESSION["role"] == 3) {
            echo "<a class='buttonBGreen subtitle' href=\"../db/adm.php?username=$username\"> ADM </a>";
            echo "<a class='buttonBRed subtitle' href=\"../db/unadm.php?username=$username\"> unADM </a>";
        }
        ?>

    </div>
    <div class="centered width">
        <?php
        $nbrelt = 14;
        $page = isset($_GET["page"]) ? $_GET["page"] : 1;
        $gap = ($page - 1) * $nbrelt;
        $request = "SELECT * from illustration WHERE username=\"$username\" ORDER BY date DESC LIMIT $gap, $nbrelt";
        $result = @mysqli_query($idcom, $request);
        while ($row = mysqli_fetch_assoc($result)) {
            $picFolder = "../db/illustration/";
            $filePic = $row["pic"];
            $id = $row["id"];
            $namePic = $row["name"];
            $username = $row["username"];
            echo "<div class=\"illustrationContainer animate-on-scroll\">";
            echo "<a href=\"../browse/illustration.php?id=$id\"><img src=" . $picFolder . $filePic . " class=\"illustration\"></a>";
            echo "</div>";
        }
        ?>
    </div>
    <?php
    $countRequest = "SELECT COUNT(*) as total FROM illustration WHERE username=?";
    $stmt = mysqli_prepare($idcom, $countRequest);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $countResult = mysqli_stmt_get_result($stmt);
    $countRow = mysqli_fetch_assoc($countResult);
    $totalElements = $countRow['total'];
    $totalPages = ceil($totalElements / $nbrelt);
    echo "<div class=\"pageChoice centered marginBottom\">";
    for ($i = 1;
         $i <= $totalPages;
         $i++) {
        echo "<a href='profile.php?username=" . htmlspecialchars($username) . "&page=$i'>$i</a> ";
    }
    echo "</div>";
} else {
    ?>
    <div class="centered animate-on-scroll lilMarginTop">
        <img class="profilePic" src="../db/profilePic/<?php echo $profilePic ?>"/>
    </div>
    <div class="centered animate-on-scroll">
        <p class="title marginV"><?php echo $name ?>.</p>
    </div>
    <div class="centered animate-on-scroll">
    <?php
    if ($adm == 1)
        echo "<p class=\"buttonBTiffany text\">Artiste du mois</p>";
    if ($username == $_SESSION["username"]) { ?>
        <a class="buttonBTiffany text" href="modifier.php?username=<?php echo $username ?>">modifier.</a>
        </div>
        <div class="centered lilMarginTop animate-on-scroll">
        <a class="buttonBRed text" href="disconnect.php">deconnexion</a>
        <?php
    }
    if ($youtube != "" || $insta != "") {
        ?>
        <a class="buttonBBlue text" href="https://www.instagram.com/<?php echo $insta ?>">instagram</a>
        <a class="buttonBRed text" href="https://youtube.com/<?php echo $youtube ?>">youtube</a>
        <?php
    }
    if ($soutiens >= 2) {
        ?>
        <p class="buttonWB text">
            soutiens
            <span class="mini"></span>
        </p>
        <?php
    }
    if (isset($_SESSION["role"]) && $_SESSION["role"] == 2 || $_SESSION["role"] == 3) {
        echo "<a class='buttonBGreen text' href=\"../db/adm.php?username=$username\"> ADM </a>";
        echo "<a class='buttonBRed text' href=\"../db/unadm.php?username=$username\"> unADM </a>";
    }
    ?>
    </div>
    <div class="left marginTop animate-on-scroll">
        <p class="title">post.</p>
        <?php
        if ($username == $_SESSION["username"]) {
            ?>
            }
            <div class="left formulaire">
                <form action="profile.php?username=<?php echo htmlspecialchars($username); ?>" method="post">
                    <p class="marginV textSection">Ajouter un post.</p>
                    <input type="text" name="text" minlength="2" maxlength="255" class="input" required>
                    <div class="form-group lilMarginTop centered marginBottom">
                        <button class="submitForm text" type="submit" name="submitPost">ajouter</button>
                    </div>
                </form>
                <?php
                if (isset($error_message)) {
                    echo $error_message;
                }
                ?>
            </div>
            <?php
        }
        ?>
    </div>
    <?php
    $requestPost = "SELECT * from post WHERE username=\"$username\" ORDER BY date DESC LIMIT 8";
    $resultPost = @mysqli_query($idcom, $requestPost);
    while ($rowPost = mysqli_fetch_assoc($resultPost)) {
        $id = $rowPost["id"];
        $text = $rowPost["text"];
        $date = $rowPost["date"];

        $formattedDate = date('d/m/Y', strtotime($date));
        echo "<div class=\"centered lilMarginTop animate-on-scroll\">";
        echo "<div class=\"block\">";
        echo "<p>$formattedDate</p>";
        echo "<div class=\"post\">";
        echo "<p class=\"textSection centerText\">$text</p>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
        if ($username == $_SESSION["username"]) {
            echo "<div class=\"centered lilMarginTop animate-on-scroll\">";
            echo "<a class=\"buttonBRed subtitle\" href='../db/removePost.php?username=$username&id=$id'>supprimer</a>";
            echo "</div>";
        }
    }
    ?>

    <div class="left marginTop animate-on-scroll">
        <p class="title">illustrations.</p>
    </div>
    <div class="centered width">
        <?php
        $nbrelt = 14;
        $page = isset($_GET["page"]) ? $_GET["page"] : 1;
        $gap = ($page - 1) * $nbrelt;
        $request = "SELECT * from illustration WHERE username=\"$username\" ORDER BY date DESC LIMIT $gap, $nbrelt";
        $result = @mysqli_query($idcom, $request);
        while ($row = mysqli_fetch_assoc($result)) {
            $picFolder = "../db/illustration/";
            $filePic = $row["pic"];
            $id = $row["id"];
            $namePic = $row["name"];
            $username = $row["username"];
            echo "<div class=\"illustrationContainer animate-on-scroll\">";
            echo "<a href=\"../browse/illustration.php?id=$id\"><img src=" . $picFolder . $filePic . " class=\"illustration\"></a>";
            echo "</div>";
        }
        ?>
    </div>
    <?php
    $countRequest = "SELECT COUNT(*) as total FROM illustration WHERE username=?";
    $stmt = mysqli_prepare($idcom, $countRequest);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $countResult = mysqli_stmt_get_result($stmt);
    $countRow = mysqli_fetch_assoc($countResult);
    $totalElements = $countRow['total'];
    $totalPages = ceil($totalElements / $nbrelt);
    echo "<div class=\"pageChoice centered marginBottom\">";
    for ($i = 1;
         $i <= $totalPages;
         $i++) {
        echo "<a href='profile.php?username=" . htmlspecialchars($username) . "&page=$i'>$i</a> ";
    }
    echo "</div>";

}
?>
</body>
</html>