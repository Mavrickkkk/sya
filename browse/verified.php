<?php
session_start();
include("../db/connex.inc.php");
$idcom = connex("myparam");
$requestmonth = "SELECT * from month WHERE month=0";
$resultmonth = @mysqli_query($idcom, $requestmonth);
while ($rowmonth = mysqli_fetch_assoc($resultmonth)) {
    $month = $rowmonth["month"];
}
?>
<html>
<head>
    <title>verified.</title>
    <meta charset="utf-8"/>
    <meta name="description" content="Partie vérifiée de SYA, trouvez vos prochaines illustrations préférés."/>
    <link rel="icon" href="../pics/favicon.png"/>
    <link rel="stylesheet" href="../style/style.css">
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

<div class="marginTop">
    <a class="title left">verified.</a>
    <a class="rightTitle">par vous, pour vous</a>
</div>
<div class="centered">
    <div class="blue"></div>
</div>
<div class="centered">
    <div class="blue2"></div>
</div>
<div class="centered marginBottom">
    <div class="blue3"></div>
</div>

<div class="centered width">
    <?php
    $nbrelt = 14;
    $page = isset($_GET["page"]) ? $_GET["page"] : 1;
    $gap = ($page - 1) * $nbrelt;

    $requestSearch = "SELECT * FROM illustration WHERE type=2 AND month=$month ORDER BY date DESC LIMIT ? OFFSET ?";
    $stmt = mysqli_prepare($idcom, $requestSearch);
    mysqli_stmt_bind_param($stmt, "ii", $nbrelt, $gap);

    mysqli_stmt_execute($stmt);
    $resultSearch = mysqli_stmt_get_result($stmt);

    while ($rowSearch = mysqli_fetch_assoc($resultSearch)) {
        $picFolder = "../db/illustration/";
        $filePic = $rowSearch["pic"];
        $id = $rowSearch["id"];
        $name = $rowSearch["name"];
        $username = $rowSearch["username"];
        echo "<div class=\"illustrationContainer animate-on-scroll\">";
        echo "<a href=\"illustration.php?id=$id\"><img src=" . $picFolder . $filePic . " class=\"illustration\"></a>";
        echo "</div>";
    }
    mysqli_stmt_close($stmt);
    ?>
</div>
<?php
$countRequest = "SELECT COUNT(*) as total FROM illustration WHERE type=2";
$countResult = mysqli_query($idcom, $countRequest);
$countRow = mysqli_fetch_assoc($countResult);
$totalElements = $countRow['total'];
$totalPages = ceil($totalElements / $nbrelt);
echo "<div class=\"pageChoice centered\">";
for ($i = 1; $i <= $totalPages; $i++) {
    echo "<a href='verified.php?type=$type&page=$i'>$i</a> ";
}
echo "</div>";
?>

<div class="centered animate-on-scroll">
    <a href="../soutenir/soutiens.php"><img src="../pics/soutenir.jpg" class="soutenir marginTop marginBottom"></a>
</div>
<a class="centered subtitle">depuis 2023</a>
</body>
</html>