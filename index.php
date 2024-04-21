<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include("db/connex.inc.php");
$idcom = connex("myparam");
$requestmonth = "SELECT * from month WHERE month=0";
$resultmonth = @mysqli_query($idcom, $requestmonth);
while ($rowmonth = mysqli_fetch_assoc($resultmonth)) {
    $month = $rowmonth["month"];
}
$requestvisitors = "UPDATE month SET visitors = visitors + 1 WHERE month = $month";
@mysqli_query($idcom, $requestvisitors);
?>
<html>
<head>
    <title>spread your arts.</title>
    <meta charset="utf-8"/>
    <meta name="description" content="Gallerie d'art virtuelle"/>
    <link rel="icon" href="pics/favicon.png"/>
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="style/styleForm.css">
    <link rel="stylesheet" href="style/styleMenu.css">
    <script src="js/hamburger.js"></script>
    <script src="js/apparition.js"></script>
</head>
<body>
<div id="menuToggle">
    <input type="checkbox"/>
    <span></span>
    <span></span>
</div>
<div id="menu">
    <a class="titleSecond" href="./">menu.</a>
    <p class="noMargin"> retourner au menu </p>
    <?php
    if (isset($_SESSION["username"]) && $_SESSION["username"] != "") {
        ?>
        <a class="titleSecond" href="connect/profile.php?username=<?php echo $_SESSION["username"]; ?>">mon profil.</a>
        <p class="noMargin"> connecté en tant que <?php echo $_SESSION["name"]; ?> </p>
        <?php
    } else {
        ?>
        <a class="titleSecond" href="connect/login.php">mon profil.</a>
        <p class="noMargin"> me connecter </p>
        <?php
    }
    ?>
    <a class="titleSecond" href="add/send.php">ajouter.</a>
    <p class="noMargin"> envoyer votre illustration </p>
    <a class="titleSecond" href="browse/verified.php">parcourir.</a>
    <p class="noMargin"> explorer le meilleur de SYA </p>
    <a class="titleSecond" href="soutenir/soutiens.php">soutenir.</a>
    <p class="noMargin"> obtenir les dernières fonctionnalités </p>
    <?php
    if (isset($_SESSION["role"]) && $_SESSION["role"] == 2 || $_SESSION["role"] == 3) {
        ?>
        <a class="titleSecond" href="../soutenir/soutiens.php">unmoderate.</a>
        <p class="noMargin"> les illustrations non modérées </p>
        <a class="titleSecond" href="moderate/user.php">modération.</a>
        <p class="noMargin">gestion des utilisateurs </p>
        <?php
    }
    ?>
</div>


<div class="height">
    <a class="centered milieu mainTitle marginBottom">
        <span class="titleFirst">spread your </span>
        <span> </span>
        <span class="titleSecond"> arts.</span>
    </a>
    <a class="centered subtitle">galerie d'art virtuelle</a>
</div>
<div class="marginTop">
    <a class="title left">artiste.</a>
    <a class="rightTitle">du mois</a>
</div>
<div class="centered">
    <?php
    $requestADM = "SELECT * from user WHERE adm=1 ";
    $resultADM = @mysqli_query($idcom, $requestADM);
    while ($rowSearchADM = mysqli_fetch_assoc($resultADM)) {
        $usernameADM = $rowSearchADM["username"];
        $profilePicFolder = "db/profilePic/";
        $profilePic = $rowSearchADM["profilePicPath"];
        echo "<div class=\"centered animate-on-scroll\">";
        echo "<a href=\"connect/profile.php?username=$usernameADM\"><img src=" . $profilePicFolder . $profilePic . " class=\"profilePic\"></a>";
        echo "</div>";
        echo "</div>";
        echo "<div class=\"centered animate-on-scroll lilMarginTop width\">";
        $requestSearch2 = "SELECT * from illustration WHERE username='$usernameADM' ORDER BY date DESC LIMIT 3";
        $resultSearch2 = @mysqli_query($idcom, $requestSearch2);
        while ($rowSearch2 = mysqli_fetch_assoc($resultSearch2)) {
            $coverFolder2 = "db/illustration/";
            $pic2 = $rowSearch2["pic"];
            $id2 = $rowSearch2["id"];
            echo "<div class=\"illustrationContainer animate-on-scroll\">";
            echo "<a href=\"browse/illustration.php?id=$id2\"><img src=" . $coverFolder2 . $pic2 . " class=\"illustration\"></a>";
            echo "</div>";
        }
        echo "</div>";
    }
    ?>
</div>


<div class="marginTop">
    <a class="title left">illustration.</a>
    <a class="rightTitle">de la semaine</a>
</div>
<div class="centered lilMarginTop midHeight">
    <?php
    $requestSearch = "SELECT * from illustration WHERE type=3 LIMIT 1";
    $resultSearch = @mysqli_query($idcom, $requestSearch);
    while ($rowSearchTop = mysqli_fetch_assoc($resultSearch)) {
        $coverFolder = "db/illustration/";
        $pic = $rowSearchTop["pic"];
        $id = $rowSearchTop["id"];
        $name = $rowSearchTop["name"];
        $description = $rowSearchTop["description"];
        $username = $rowSearchTop["username"];
        $request3 = "SELECT name FROM user WHERE username='$username'";
        $result3 = @mysqli_query($idcom, $request3);
        echo "<div class=\"illustrationContainer animate-on-scroll\">";
        echo "<a href=\"browse/illustration.php?id=$id\"><img src=" . $coverFolder . $pic . " class=\"illustration medium\"></a>";
        echo "</div>";
        echo "<div class=\"formulaire desc left animate-on-scroll\">";
        echo "<p class=\"textSection centerText\">" . $name . "</p>";
        echo "<div class=\"lilMarginTop\"><p class=\"rightTitle centerText\">" . $description . "</p></div>";
        echo "</div>";
    }
    ?>
</div>
<div class="marginTop">
    <a class="title left">verified.</a>
    <a class="rightTitle">les meilleures illustrations de SYA</a>
</div>
<div class="centered width">
    <?php
    $requestSearch = "SELECT * from illustration WHERE type=2 ORDER BY date DESC LIMIT 15";
    $resultSearch = @mysqli_query($idcom, $requestSearch);
    while ($rowSearch = mysqli_fetch_assoc($resultSearch)) {
        $coverFolder = "db/illustration/";
        $pic = $rowSearch["pic"];
        $id = $rowSearch["id"];
        $name = $rowSearch["name"];
        $username = $rowSearch["username"];
        $request2 = "SELECT name FROM user WHERE username='$username'";
        $result2 = @mysqli_query($idcom, $request2);
        echo "<div class=\"illustrationContainer animate-on-scroll\">";
        echo "<a href=\"browse/illustration.php?id=$id\"><img src=" . $coverFolder . $pic . " class=\"illustration\"></a>";
        echo "</div>";
    }
    ?>
</div>
<div class="centered lilMarginTop">
    <a class="buttonBorderW" href="add/send.php"> ajouter mon illustration</a>
</div>
<div class="centered width marginTop animate-on-scroll">
    <a href="browse/verified.php"><img src="pics/verified.jpg" class="choicePic"></a>
</div>
<!--<div class="centered marginTop">
    <img src="pics/home.jpg" class="homepic animate-on-scroll">
</div>!-->
<div class="marginTop centered marginBottom animate-on-scroll">
    <a class="textSection EL left white">rejoignez la communauté</a>
</div>
<div class="centered width animate-on-scroll">
    <a href="https://instagram.com/spreadyourarts"><img src="pics/insta.jpg" class="reseaux marginBottom"></a>
</div>
<div class="centered animate-on-scroll">
    <a href="soutenir/soutiens.php"><img src="pics/soutenir.jpg" class="soutenir marginBottom"></a>
</div>
<a class="centered subtitle">par Mavrick</a>
<a class="centered subtitle">depuis 2023</a>
</body>
</html>