<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
    session_start();
    include("db/connex.inc.php");
    $idcom=connex("myparam");
    $requestmonth = "SELECT * from month WHERE month=0";
    $resultmonth = @mysqli_query($idcom, $requestmonth);
    while ($rowmonth = mysqli_fetch_assoc($resultmonth)) {
        $month=$rowmonth["month"];
    }
    $requestvisitors = "UPDATE month SET visitors = visitors + 1 WHERE month = $month";
    @mysqli_query($idcom, $requestvisitors);
    $random = random_int(1, 17);
?>
<html>
<head>
    <title>SYA - HOME</title>
    <meta charset="utf-8"/>
    <meta name="description" content="SPREAD YOUR ARTS - Gallerie d'art virtuelle, envoyez vos illustrations, photos et singles"/>
    <link rel="icon" href="pics/favicon.png"/>
    <link rel="stylesheet" href="style/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="sticky-bar">
        <div class="bar-content">
            <div class="bar-title">SYA</div>
            <ul class="bar-links">
                <li><a href="https://www.paypal.com/donate/?hosted_button_id=8XFJ7GKL2Y53W">don</a></li>
                <li><a href="browse/verified.php">parcourir</a></li>
              <?php
              	if (isset($_SESSION["name"]))
                	echo "<li><a href=\"add/send.php?type=illustration\">add</a></li>";
              ?>
                <?php
                    if (!isset($_SESSION["username"])){
                ?>
                <li><a href="connect/login.php">se connecter</a></li>
                <?php
                    }else {
                ?>
                <li><a href="connect/profile.php?username=<?php echo $_SESSION["username"]; ?>"><?php echo $_SESSION["name"]; ?></a></li>
                <?php
                    }
                ?>
            </ul>
        </div>
    </div>
    <div class="centered height">
        <a class=" milieu title">
            <span class="titleFirst">spread your</span>
            <span class="titleSecond"> arts.</span>
        </a>
        <a class="subtitle">gallerie d'art virtuelle</a>
    </div>

    <div class="info-bar" style="display: none;">
        <div class="info-controls">
            <a href="#" class="info-control prev">◄</a>
        </div>
        <div class="info-content">
            <a href="https://www.instagram.com/spreadyourarts/" class="info-link">suit nous sur instagram</a>
            <a href="news/lefoot.php" class="info-link">"LE FOOT" 25/02</a>
            <a href="https://youtu.be/HXBRgh2n8Kw?si=bRl_1BD5gVzd9lyT" class="info-link">trailer "les fleurs" saison 0</a>
        </div>
        <div class="info-controls">
            <a href="#" class="info-control next">►</a>
        </div>
    </div>
<img src="pics/fleur1.png" class="flower1">
    <div class="centered">
        <a href="about/about.php" class="mainText4">C'EST QUOI CE SITE</a>
    </div>
    <img src="pics/fleur2.png" class="flower2">

    <div class="centered">
        <a class="mainText3" href="browse/verified.php">ILLUSTRATIONS</a>
    </div>
    <div class="singleContent">
    <?php
    $requestSearch = "SELECT * from illustration WHERE type=3 AND month=$month";
    $resultSearch = @mysqli_query($idcom, $requestSearch);
    while ($rowSearch = mysqli_fetch_assoc($resultSearch)) {
        $coverFolder = "db/illustration/";
        $pic = $rowSearch["pic"];
        $id=$rowSearch["id"];
        $name = $rowSearch["name"];
        $username = $rowSearch["username"];
        $request2 = "SELECT name FROM user WHERE username='$username'";
        $result2 = @mysqli_query($idcom, $request2);
        while ($row2 = mysqli_fetch_assoc($result2)) {
            $nameUser=$row2["name"];
            echo "<div class=\"picContent\">";
            echo "<a class=\"presents\" href=\"../connect/profile.php?username=$username\">$nameUser presente</a>";
        }
        echo "<a href=\"browse/illustration.php?id=$id\"><img src=" . $coverFolder . $pic . " class=\"pic\"></a>";
        echo "<a class=\"picName\">" . $name . "</a>";
        if (isset($_SESSION["role"]) && $_SESSION["role"]==3) {
            echo "<div class\"xvtContent\">";
            echo "<a class=\"x\" href=\"db/removeIllustration.php?id=" . $rowSearch["id"] . "\">SUPPRIMER</a>";
            echo "</div>";
            echo "<div class\"xvtContent\">";
            echo "<a class=\"v\" href=\"db/verifyIllustration.php?id=" . $rowSearch["id"] . "\">VERIFIED</a>";
            echo "</div>";
            echo "<div class\"xvtContent\">";
            echo "<a class=\"top\" href=\"db/iotw.php?id=" . $rowSearch["id"] . "\">TOP</a>";
            echo "</div>";
        }
        echo "</div>";
    }
    ?>
    </div>
    <div class="centered">
        <a class="mainText3" href="/browse/verified.php?type=single">SINGLE</a>
    </div>
    <div class="singleContent">
    <?php
        $requestSearch3 = "SELECT * from single WHERE type=3 AND month=$month";
        $resultSearch3 = @mysqli_query($idcom, $requestSearch3);
        while ($rowSearch3 = mysqli_fetch_assoc($resultSearch3)) {
            $coverFolder = "db/cover/";
            $fileCover = $rowSearch3["fileCover"];
            $id=$rowSearch3["id"];
            $nameSingle = $rowSearch3["name"];
            $username = $rowSearch3["username"];
            $request4 = "SELECT name FROM user WHERE username='$username'";
            $result4 = @mysqli_query($idcom, $request4);
            while ($row4 = mysqli_fetch_assoc($result4)) {
                $name=$row4["name"];
                echo "<div class=\"picContent\">";
                echo "<a class=\"presents\" href=\"../connect/profile.php?username=$username\">$name presente</a>";
            }
            echo "<a href=\"browse/single.php?id=$id\"><img src=" . $coverFolder . $fileCover . " class=\"pic\"></a>";
            echo "<a class=\"picName\">" . $nameSingle . "</a>";
            if (isset($_SESSION["role"]) && $_SESSION["role"]==3) {
                echo "<div class\"xvtContent\">";
                echo "<a class=\"x\" href=\"db/removeSingle.php?id=" . $rowSearch3["id"] . "\">SUPPRIMER</a>";
                echo "</div>";
                echo "<div class\"xvtContent\">";
                echo "<a class=\"v\" href=\"db/verifySingle.php?id=" . $rowSearch3["id"] . "\">VERIFIED</a>";
                echo "</div>";
                echo "<div class\"xvtContent\">";
                echo "<a class=\"top\" href=\"db/sotw.php?id=" . $rowSearch3["id"] . "\">TOP</a>";
                echo "</div>";
            }
            echo "</div>";
        }
    ?>
    </div>
    <img src="pics/fleur1.png" class="flower3">
    <div class="centered-top">
        <a class="mainText">NEWS</a>
    </div>
    <a href="news/lefoot.php"><img  src="pics/thumbnail.png" class="pic1"></a>
    <div class="centered">
        <a class="subtitle">25 Février 14h00</a>
    </div>
    <?php
    if (isset($_SESSION["role"]) && $_SESSION["role"]==3){
    ?>
        <div class="centered-top">
            <a class="mainText" href="moderate/user.php">MODERATION</a>
        </div>
    <?php
    }
    ?>
	<footer>
    <div class="footer-content">
        <a href="about/contact.php">contactez moi</a>
        <?php
        if ($random==1)
            echo "<a>c'est la page d'accueil quoi...</a>";
        if ($random==2)
            echo "<a href=\"https://bandcamp.com/\">bandcamp est meilleur</a>";
        if ($random==3)
            echo "<a href\"https://www.youtube.com/watch?v=TGgcC5xg9YI\">see you again</a>";
        if ($random==4)
            echo "<a>tu es aimé</a>";
        if ($random==5)
            echo "<a>appelle tes proches</a>";
        if ($random==6)
            echo "<a>si seulement tu étais là avant</a>";
        if ($random==7)
            echo "<a>tu nous a manqué</a>";
        if ($random==8)
            echo "<a>made in France</a>";
        if ($random==9)
            echo "<a>@2023</a>";
        if ($random==10)
            echo "<a>merci d'avoir ramené le soleil</a>";
        if ($random==11)
            echo "<a>no longer confused but don't tell anybody</a>";
        if ($random==12)
            echo "<a href=\"https://www.youtube.com/watch?v=IVzzw7Vkiyg\">crack rock, crack rock</a>";
        if ($random==13)
            echo "<a href=\"https://www.youtube.com/watch?v=0gvEfIbGJxQ\">my earthquake</a>";
        if ($random==14)
            echo "<a>on est toujours des enfants dans le fond</a>";
        if ($random==15)
            echo "<a>la manière la plus simple de vous exprimer (vraiment)</a>";
        if ($random==16)
            echo "<a>bon ce message est pas important</a>";
        if ($random==17)
            echo "<a>en fait les messages sont aléatoires</a>";
        ?>
        <a>v1.0</a>
    </div>
</footer>
</body>
</html>