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
?>
<html>
<head>
    <title>spread your arts.</title>
    <meta charset="utf-8"/>
    <meta name="description" content="Gallerie d'art virtuelle"/>
    <link rel="icon" href="pics/favicon.png"/>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <nav>
        <a href="add/send.php" class="nav-item">ajouter.</a>
        <a href="browse/verified.php" class="nav-item">parcourir.</a>
        <?php
            if (isset($_SESSION["username"]) && $_SESSION["username"]!=""){
        ?>
            <a class="nav-item" href="connect/profile.php?username=<?php echo $_SESSION["username"]; ?>">mon compte.</a>
        <?php
            } else {
                ?>
            <a class="nav-item" href="connect/login.php">mon compte.</a>
        <?php
            }
        ?>
    </nav>
    <div class="height">
        <a class="centered milieu mainTitle marginBottom">
            <span class="titleFirst">spread your </span>
            <span> </span>
            <span class="titleSecond"> arts.</span>
        </a>
        <a class="centered subtitle">gallerie d'art virtuelle</a>
    </div>

    <div class="centered">
        <img src="pics/home.jpg" class="homepic">
    </div>
    <div class="marginTop">
        <a class="title left">verified.</a>
        <a class="rightTitle">les meilleures illustrations de SYA</a>
    </div>
    <div class="centered width">
        <?php
        $requestSearch = "SELECT * from illustration WHERE type=2";
        $resultSearch = @mysqli_query($idcom, $requestSearch);
        while ($rowSearch = mysqli_fetch_assoc($resultSearch)) {
            $coverFolder = "db/illustration/";
            $pic = $rowSearch["pic"];
            $id=$rowSearch["id"];
            $name = $rowSearch["name"];
            $username = $rowSearch["username"];
            $request2 = "SELECT name FROM user WHERE username='$username'";
            $result2 = @mysqli_query($idcom, $request2);
            echo "<div class=\"illustrationContainer\">";
                echo "<a href=\"browse/illustration.php?id=$id\"><img src=" . $coverFolder . $pic . " class=\"illustration\"></a>";
            echo "</div>";
        }
        ?>
    </div>
    <div class="centered width marginTop">
        <a href="browse/verified.php"><img src="pics/verified.jpg" class="choicePic"></a>
        <a href="browse/unmoderate.php"><img src="pics/unmoderate.jpg" class="choicePic"></a>
    </div>
    <div class="marginTop marginBottom">
        <a class="title EL left">rejoignez la </a>
        <a class="title">communauté.</a>
    </div>
    <div class="centered width">
        <a href="https://instagram.com/spreadyourarts"><img src="pics/insta.jpg" class="reseaux marginBottom"></a>
        <a href="https://youtube.com/@spreadyourarts"><img src="pics/youtube.jpg" class="reseaux"></a>
    </div>
    <div class="centered">
        <a href="soutenir/soutiens.php"><img src="pics/soutenir.jpg" class="soutenir marginTop marginBottom"></a>
    </div>
    <a class="centered subtitle">par Mavrick</a>
    <a class="centered subtitle">depuis 2023</a>
</body>
</html>